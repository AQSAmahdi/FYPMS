<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use App\Models\PhaseSchedule;
use App\Models\FypPhase;
use App\Models\FypGroup;
use App\Models\ScheduleEvaluator;
use Illuminate\Http\Request;

class PhaseScheduleController extends Controller
{
    public function index()
    {
        $schedules    = PhaseSchedule::with(['phase', 'group'])->orderBy('created_at', 'desc')->get();
        $phases       = FypPhase::all();
        $groups       = FypGroup::all();
        $sessions    = \App\Models\AcademicSession::orderBy('name')->get();
        $departments = \App\Models\Department::orderBy('name')->get();
        return view('focal.phase-schedules.index', compact('schedules', 'phases', 'groups', 'sessions', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phase_id'      => 'required|exists:fyp_phases,id',
            'group_id'      => 'required|exists:fyp_groups,id',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required',
            'room'          => 'nullable|string|max:100',
        ]);

        $existing = PhaseSchedule::where('group_id', $request->group_id)
            ->where('phase_id', $request->phase_id)
            ->first();
        if ($existing) {
            return back()->withErrors(['group_id' => 'This group already has a schedule for this phase.'])->withInput();
        }

        $schedule = PhaseSchedule::create([
            'phase_id'      => $request->phase_id,
            'group_id'      => $request->group_id,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
            'room'          => $request->room,
            'status'        => 'scheduled',
        ]);

        $this->notifyGroupAndSupervisor($schedule);

        return redirect()->route('focal.phase-schedules.index')->with('success', 'Schedule added!');
    }

    public function update(Request $request, PhaseSchedule $schedule)
    {
        $request->validate([
            'phase_id'      => 'required|exists:fyp_phases,id',
            'group_id'      => 'required|exists:fyp_groups,id',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required',
            'room'          => 'nullable|string|max:100',
        ]);
        $schedule->update([
            'phase_id'      => $request->phase_id,
            'group_id'      => $request->group_id,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
            'room'          => $request->room,
        ]);

        $this->notifyGroupAndSupervisor($schedule, true);

        return redirect()->route('focal.phase-schedules.index')->with('success', 'Schedule updated!');
    }

    private function notifyGroupAndSupervisor(PhaseSchedule $schedule, $isUpdate = false)
    {
        $schedule->load(['phase.panel.facultys', 'group.members.user', 'group.supervisor']);
        $group = $schedule->group;
        if (!$group) return;

        // Auto-link the group's assigned supervisor as the evaluator for this schedule
        if ($group->supervisor_id) {
            ScheduleEvaluator::firstOrCreate([
                'phase_schedule_id' => $schedule->id,
                'evaluator_id'      => $group->supervisor_id,
            ]);
        }

        // Auto-link all panel members as evaluators (used for Defence-type phases)
        if ($schedule->phase && $schedule->phase->panel) {
            foreach ($schedule->phase->panel->facultys as $facultyMember) {
                ScheduleEvaluator::firstOrCreate([
                    'phase_schedule_id' => $schedule->id,
                    'evaluator_id'      => $facultyMember->id,
                ]);

                \App\Helpers\NotificationHelper::send(
                    $facultyMember->id,
                    ($schedule->phase->name ?? 'Phase') . ' Evaluation Assigned',
                    'You have been assigned to evaluate group "' . $group->group_code . '" for "'
                        . ($schedule->phase->name ?? 'Phase') . '" on '
                        . \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y')
                        . ' at ' . $schedule->schedule_time
                        . ($schedule->room ? ' in ' . $schedule->room : '') . '.',
                    'info',
                    '/faculty/dashboard'
                );
            }
        }

        $phaseName = $schedule->phase->name ?? 'Phase';
        $action = $isUpdate ? 'rescheduled' : 'scheduled';
        $message = 'Your "' . $phaseName . '" evaluation has been ' . $action . ' on '
            . \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y')
            . ' at ' . $schedule->schedule_time
            . ($schedule->room ? ' in ' . $schedule->room : '') . '.';

        foreach ($group->members as $member) {
            \App\Helpers\NotificationHelper::send(
                $member->user_id,
                $phaseName . ' Evaluation ' . ucfirst($action),
                $message,
                'info',
                '/student/dashboard'
            );
        }

        if ($group->supervisor_id) {
            \App\Helpers\NotificationHelper::send(
                $group->supervisor_id,
                $phaseName . ' Evaluation ' . ucfirst($action),
                'Evaluation for group "' . $group->group_code . '" has been ' . $action . ' on '
                    . \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y')
                    . ' at ' . $schedule->schedule_time
                    . ($schedule->room ? ' in ' . $schedule->room : '') . '.',
                'info',
                '/faculty/dashboard'
            );
        }
    }

    public function destroy(PhaseSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('focal.phase-schedules.index')->with('success', 'Schedule deleted!');
    }

    public function allowLateUpload($id)
    {
        $schedule = PhaseSchedule::findOrFail($id);
        $schedule->update([
            'late_upload_allowed'    => true,
            'late_upload_allowed_by' => auth()->id(),
            'late_upload_allowed_at' => now(),
        ]);

        $schedule->load('group.members.user', 'phase');
        if ($schedule->group) {
            foreach ($schedule->group->members as $member) {
                \App\Helpers\NotificationHelper::send(
                    $member->user_id,
                    'Late Upload Permission Granted',
                    'You have been granted special permission to upload the document for phase "' . ($schedule->phase->name ?? 'Phase') . '". Please upload as soon as possible.',
                    'info',
                    '/student/phase-documents'
                );
            }
        }

        return back()->with('success', 'Late upload permission granted for ' . ($schedule->group->group_code ?? 'this group') . '.');
    }
}
