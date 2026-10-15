<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhaseSchedule;
use App\Models\StudentPhaseMark;
use Illuminate\Http\Request;

class PhaseEvaluationController extends Controller
{
    // Faculty ke assigned schedules
    public function mySchedules()
    {
        $schedules = PhaseSchedule::with([
            'phase', 'group.department', 'group.members.user.studentProfile', 'evaluators.evaluator'
        ])
        ->whereHas('evaluators', function ($q) {
            $q->where('evaluator_id', auth()->id());
        })
        ->orderBy('schedule_date')
        ->get()
        ->map(function ($s) {
            $isEvaluated = StudentPhaseMark::where('phase_schedule_id', $s->id)
                ->where('evaluator_id', auth()->id())
                ->exists();

            return [
                'id'            => $s->id,
                'phase'         => $s->phase?->name,
                'group_code'    => $s->group?->group_code,
                'department'    => $s->group?->department?->name,
                'schedule_date' => $s->schedule_date,
                'schedule_time' => $s->schedule_time,
                'room'          => $s->room,
                'status'        => $s->status,
                'is_evaluated'  => $isEvaluated,
                'panel'         => $s->evaluators->map(fn($e) => [
                    'id'   => $e->evaluator?->id,
                    'name' => $e->evaluator?->name,
                ]),
                'students'      => $s->group?->members->map(fn($m) => [
                    'id'       => $m->user?->id,
                    'name'     => $m->user?->name,
                    'roll_no'  => $m->user?->studentProfile?->student_id,
                ]),
            ];
        });

        return response()->json(['success' => true, 'data' => $schedules]);
    }

    public function show(PhaseSchedule $schedule)
    {
        $isAssigned = $schedule->evaluators()
            ->where('evaluator_id', auth()->id())
            ->exists();

        if (!$isAssigned) {
            return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
        }

        $schedule->load(['phase', 'group.members.user.studentProfile', 'panel']);

        $existingMarks = StudentPhaseMark::where('phase_schedule_id', $schedule->id)
            ->where('evaluator_id', auth()->id())
            ->get()
            ->groupBy('student_id');

        return response()->json([
            'success'  => true,
            'schedule' => [
                'id'            => $schedule->id,
                'phase'         => $schedule->phase?->name,
                'total_marks'   => $schedule->phase?->total_marks,
                'convert_to'    => $schedule->phase?->convert_to_marks,
                'group_code'    => $schedule->group?->group_code,
                'schedule_date' => $schedule->schedule_date,
                'room'          => $schedule->room,
            ],
            'students'       => $schedule->group?->members->map(fn($m) => [
                'id'      => $m->user?->id,
                'name'    => $m->user?->name,
                'roll_no' => $m->user?->studentProfile?->student_id,
            ]),
            'existing_marks' => $existingMarks,
        ]);
    }

    public function store(Request $request, PhaseSchedule $schedule)
    {
        $isAssigned = $schedule->evaluators()
            ->where('evaluator_id', auth()->id())
            ->exists();

        if (!$isAssigned) {
            return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
        }

        $scheduledDate = \Carbon\Carbon::parse($schedule->schedule_date)->startOfDay();
        $windowEnd     = $scheduledDate->copy()->addDays(2)->endOfDay();
        $now           = now();

        if ($now->lt($scheduledDate)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot submit marks before the scheduled evaluation date (' . $scheduledDate->format('d M Y') . ').'
            ], 422);
        }

        if ($now->gt($windowEnd)) {
            return response()->json([
                'success' => false,
                'message' => 'The submission window for this evaluation closed on ' . $windowEnd->format('d M Y') . '. Please contact the Focal Person to reschedule this evaluation.'
            ], 422);
        }

        $request->validate([
            'marks'     => 'required|array',
            'max_marks' => 'required|array',
        ]);

        $phase = $schedule->phase;

        foreach ($request->marks as $studentId => $criteriaMarks) {
            foreach ($criteriaMarks as $criteriaName => $marksObtained) {
                $maxMarks      = $request->max_marks[$criteriaName] ?? 0;
                $marksObtained = min((float)$marksObtained, (float)$maxMarks);
                $converted     = $phase->total_marks > 0
                    ? round(($marksObtained * $phase->convert_to_marks) / $phase->total_marks, 2)
                    : 0;

                StudentPhaseMark::updateOrCreate(
                    [
                        'phase_schedule_id' => $schedule->id,
                        'student_id'        => $studentId,
                        'evaluator_id'      => auth()->id(),
                        'criteria_name'     => $criteriaName,
                    ],
                    [
                        'phase_id'        => $phase->id,
                        'max_marks'       => $maxMarks,
                        'marks_obtained'  => $marksObtained,
                        'converted_marks' => $converted,
                    ]
                );
            }
        }

        // Check if this evaluator has now submitted marks for ALL students in the group
        $schedule->load('group.members');
        $studentIds = $schedule->group->members->pluck('user_id');
        $evaluatorDoneForAll = true;
        foreach ($studentIds as $sid) {
            $hasMarks = StudentPhaseMark::where('phase_schedule_id', $schedule->id)
                ->where('evaluator_id', auth()->id())
                ->where('student_id', $sid)
                ->exists();
            if (!$hasMarks) { $evaluatorDoneForAll = false; break; }
        }

        if ($evaluatorDoneForAll) {
            \App\Models\ScheduleEvaluator::where('phase_schedule_id', $schedule->id)
                ->where('evaluator_id', auth()->id())
                ->update(['submitted_at' => now()]);
        }

        $this->checkAndFinalizeResult($schedule);

        // Notify students in the group
        $schedule->load('group.members.user', 'phase');
        foreach ($schedule->group->members as $member) {
            \App\Helpers\NotificationHelper::send(
                $member->user_id,
                'Evaluation Marks Submitted',
                'Your marks for phase "' . $schedule->phase->name . '" have been submitted by the evaluator.',
                'info',
                '/student/dashboard'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Marks submitted successfully!'
        ]);
    }

    /**
     * Once every assigned evaluator has fully submitted marks for this schedule,
     * average their totals, mark status completed, and set pass/fail result.
     */
    private function checkAndFinalizeResult(PhaseSchedule $schedule)
    {
        $schedule->load('phase', 'group.members');
        $evaluatorIds = \App\Models\ScheduleEvaluator::where('phase_schedule_id', $schedule->id)
            ->pluck('evaluator_id');

        if ($evaluatorIds->isEmpty()) {
            return;
        }

        $studentIds = $schedule->group->members->pluck('user_id');

        // Confirm every evaluator has submitted marks for every student
        foreach ($evaluatorIds as $evalId) {
            foreach ($studentIds as $sid) {
                $exists = StudentPhaseMark::where('phase_schedule_id', $schedule->id)
                    ->where('evaluator_id', $evalId)
                    ->where('student_id', $sid)
                    ->exists();
                if (!$exists) {
                    return; // not everyone has submitted yet
                }
            }
        }

        // Everyone has submitted - compute the group's average percentage
        $phase = $schedule->phase;
        $allMarks = StudentPhaseMark::where('phase_schedule_id', $schedule->id)->get();

        $totalConverted = $allMarks->sum('converted_marks');
        $countEntries    = $evaluatorIds->count() * max($studentIds->count(), 1);
        $maxPossible      = ($phase->convert_to_marks ?? 0) * $countEntries;

        $averagePercent = $maxPossible > 0
            ? round(($totalConverted / $maxPossible) * 100, 2)
            : 0;

        $result = $averagePercent >= 50 ? 'pass' : 'fail';

        $schedule->update([
            'status'         => 'completed',
            'average_marks'  => $averagePercent,
            'result'         => $result,
        ]);
    }
}