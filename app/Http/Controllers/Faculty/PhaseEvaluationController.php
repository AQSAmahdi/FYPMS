<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\PhaseSchedule;
use App\Models\StudentPhaseMark;
use App\Models\ScheduleEvaluator;
use Illuminate\Http\Request;

class PhaseEvaluationController extends Controller
{
    public function index()
    {
        $schedules = PhaseSchedule::with([
            'phase',
            'group.members.user'
        ])
        ->whereHas('evaluators', function ($q) {
            $q->where('evaluator_id', auth()->id());
        })
        ->orderBy('schedule_date')
        ->get();

        $marks = StudentPhaseMark::where('evaluator_id', auth()->id())->get();

        return view('faculty.phase-evaluation', compact('schedules', 'marks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phase_id'    => 'required',
            'group_id'    => 'required',
            'schedule_id' => 'required',
            'students'    => 'required|array',
        ]);

        $schedule = PhaseSchedule::findOrFail($request->schedule_id);
        $phase    = $schedule->phase;

        $scheduledDate = \Carbon\Carbon::parse($schedule->schedule_date)->startOfDay();
        $windowEnd     = $scheduledDate->copy()->addDays(2)->endOfDay();
        $now           = now();

        if ($now->lt($scheduledDate)) {
            return back()->withErrors(['schedule_id' => 'You cannot submit marks before the scheduled evaluation date (' . $scheduledDate->format('d M Y') . ').']);
        }

        if ($now->gt($windowEnd)) {
            return back()->withErrors(['schedule_id' => 'The submission window for this evaluation closed on ' . $windowEnd->format('d M Y') . '. Please contact the Focal Person to reschedule this evaluation.']);
        }

        foreach ($request->students as $item) {
            if (empty($item['marks'])) continue;

            $marksObtained = min((float)$item['marks'], (float)$phase->total_marks);
            $converted = $phase->total_marks > 0
                ? round(($marksObtained * $phase->convert_to_marks) / $phase->total_marks, 2)
                : 0;

            StudentPhaseMark::updateOrCreate(
                [
                    'phase_schedule_id' => $schedule->id,
                    'student_id'        => $item['student_id'],
                    'evaluator_id'      => auth()->id(),
                    'phase_id'          => $phase->id,
                    'criteria_name'     => 'Overall',
                ],
                [
                    'max_marks'       => $phase->total_marks,
                    'marks_obtained'  => $marksObtained,
                    'converted_marks' => $converted,
                ]
            );
        }

        // Mark this evaluator as having submitted, then check if everyone is done
        ScheduleEvaluator::where('phase_schedule_id', $schedule->id)
            ->where('evaluator_id', auth()->id())
            ->update(['submitted_at' => now()]);

        $this->checkAndFinalizeResult($schedule);

        $schedule->load('group.members.user');
        if ($schedule->group) {
            foreach ($schedule->group->members as $member) {
                \App\Helpers\NotificationHelper::send(
                    $member->user_id,
                    'Evaluation Marks Submitted',
                    'Your marks for phase "' . ($phase->name ?? 'Evaluation') . '" have been submitted by the evaluator.',
                    'info',
                    '/student/dashboard'
                );
            }
        }

        return redirect()->route('faculty.phase-evaluation')
                         ->with('success', 'Marks submitted successfully!');
    }

    /**
     * Once every assigned evaluator has fully submitted marks for this schedule,
     * average their totals, mark status completed, and set pass/fail result.
     */
    private function checkAndFinalizeResult(PhaseSchedule $schedule)
    {
        $schedule->load('phase', 'group.members');
        $evaluatorIds = ScheduleEvaluator::where('phase_schedule_id', $schedule->id)
            ->pluck('evaluator_id');

        if ($evaluatorIds->isEmpty()) {
            return;
        }

        $studentIds = $schedule->group->members->pluck('user_id');

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
