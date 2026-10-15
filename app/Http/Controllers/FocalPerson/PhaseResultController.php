<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use App\Models\PhaseSchedule;
use App\Models\FypGroup;
use Illuminate\Http\Request;

class PhaseResultController extends Controller
{
    public function index()
    {
        $schedules = PhaseSchedule::with(['phase', 'group.supervisor', 'group.members.user'])
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        $supervisors = \App\Models\User::where('role', 'faculty')->orWhere('role', 'supervisor')->orderBy('name')->get();

        return view('focal.phase-results.index', compact('schedules', 'supervisors'));
    }

    public function assignSupervisor(Request $request, PhaseSchedule $schedule)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        if ($schedule->result !== 'pass') {
            return back()->withErrors(['supervisor_id' => 'Supervisor can only be assigned after a passing result.']);
        }

        $group = $schedule->group;
        if (!$group) {
            return back()->withErrors(['supervisor_id' => 'Group not found.']);
        }

        $group->update([
            'supervisor_id' => $request->supervisor_id,
            'status'        => 'supervisor_assigned',
        ]);

        \App\Helpers\NotificationHelper::send(
            $request->supervisor_id,
            'New Group Assigned',
            'You have been assigned as supervisor for group "' . $group->group_code . '".',
            'info',
            '/faculty/dashboard'
        );

        foreach ($group->members as $member) {
            \App\Helpers\NotificationHelper::send(
                $member->user_id,
                'Supervisor Assigned',
                'A supervisor has been assigned to your group "' . $group->group_code . '".',
                'success',
                '/student/dashboard'
            );
        }

        return back()->with('success', 'Supervisor assigned successfully!');
    }

    public function retry(Request $request, PhaseSchedule $schedule)
    {
        $request->validate([
            'schedule_date' => 'required|date',
            'schedule_time' => 'required',
            'room'          => 'nullable|string|max:100',
        ]);

        if ($schedule->result !== 'fail') {
            return back()->withErrors(['schedule_date' => 'Retry is only allowed after a failing result.']);
        }

        $newSchedule = PhaseSchedule::create([
            'phase_id'        => $schedule->phase_id,
            'group_id'        => $schedule->group_id,
            'panel_id'        => $schedule->panel_id,
            'schedule_date'   => $request->schedule_date,
            'schedule_time'   => $request->schedule_time,
            'room'            => $request->room,
            'status'          => 'scheduled',
            'attempt_number'  => $schedule->attempt_number + 1,
            'result'          => 'pending',
        ]);

        // Re-link panel members + supervisor as evaluators for the retry
        $newSchedule->load('phase.panel.facultys', 'group.supervisor');
        if ($newSchedule->group && $newSchedule->group->supervisor_id) {
            \App\Models\ScheduleEvaluator::firstOrCreate([
                'phase_schedule_id' => $newSchedule->id,
                'evaluator_id'      => $newSchedule->group->supervisor_id,
            ]);
        }
        if ($newSchedule->phase && $newSchedule->phase->panel) {
            foreach ($newSchedule->phase->panel->facultys as $facultyMember) {
                \App\Models\ScheduleEvaluator::firstOrCreate([
                    'phase_schedule_id' => $newSchedule->id,
                    'evaluator_id'      => $facultyMember->id,
                ]);
            }
        }

        return back()->with('success', 'Retry scheduled successfully!');
    }
}
