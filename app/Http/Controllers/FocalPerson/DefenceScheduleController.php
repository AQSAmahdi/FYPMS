<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefenceSchedule;
use App\Models\DefencePanel;
use App\Models\EvaluationCriteria;
use App\Models\FypGroup;
use App\Models\Evaluation;
use App\Models\User;

class DefenceScheduleController extends Controller {

    public function index() {
        $panels = DefencePanel::where('status','active')->with('facultys')->get();

        // If no active panels exist, redirect to panels page first
        if ($panels->isEmpty()) {
            return redirect()->route('focal.defence.panels')
                ->with('warning', 'Please create a defence panel first before scheduling a defence.');
        }

        $schedules = DefenceSchedule::with(['group','panel','faculty','criteria','evaluations'])->get();
        $groups = FypGroup::whereHas('proposal', function($q){ $q->whereIn('status', ['pending', 'approved']); })->with(['department'])->get();
        $facultys = User::whereIn('role',['faculty','supervisor'])->get();

                 
        return view('focal.defence.schedules', compact('schedules','panels','groups','facultys'));
    }

    public function store(Request $request) {
        $request->validate([
            'fyp_group_id'        => 'required|exists:fyp_groups,id',
            'defence_panel_id'    => 'required|exists:defence_panels,id',
            'assigned_teacher_id' => 'required|exists:users,id',
            'defence_date'        => 'required|date',
            'defence_time'        => 'required',
            'room'                => 'required|string|max:100',
            'criteria_name'       => 'required|array|min:1',
            'criteria_name.*'     => 'required|string|max:255',
            'max_marks'           => 'required|array|min:1',
            'max_marks.*'         => 'required|integer|min:1',
        ]);

        // Check if this group already has an active/scheduled defence
        $existing = DefenceSchedule::where('fyp_group_id', $request->fyp_group_id)
            ->where('status', 'scheduled')
            ->first();
        if ($existing) {
            return back()->withErrors(['fyp_group_id' => 'This group already has a scheduled defence on ' . $existing->defence_date . '. Please complete or delete it first.'])->withInput();
        }

        $schedule = DefenceSchedule::create([
            'fyp_group_id'        => $request->fyp_group_id,
            'defence_panel_id'    => $request->defence_panel_id,
            'assigned_teacher_id' => $request->assigned_teacher_id,
            'defence_date'        => $request->defence_date,
            'defence_time'        => $request->defence_time,
            'room'                => $request->room,
            'status'              => 'scheduled',
            'created_by'          => auth()->id(),
        ]);

        foreach($request->criteria_name as $i => $cname) {
            EvaluationCriteria::create([
                'defence_schedule_id' => $schedule->id,
                'criteria_name'       => $cname,
                'max_marks'           => $request->max_marks[$i],
                'order'               => $i,
            ]);
        }

        // Notify group members
        $schedule->load('group.members.user');
        foreach ($schedule->group->members as $member) {
            \App\Helpers\NotificationHelper::send(
                $member->user_id,
                'Defence Scheduled',
                'Your defence has been scheduled on ' . $schedule->defence_date . ' at ' . $schedule->defence_time . ' in Room ' . $schedule->room . '.',
                'info',
                '/student/dashboard'
            );
        }
        return back()->with('success','Defence scheduled successfully!');
    }

    public function destroy($id) {
        DefenceSchedule::findOrFail($id)->delete();
        return back()->with('success','Schedule deleted.');
    }

    public function results() {
        $schedules = DefenceSchedule::with([
            'group.members.user',
            'faculty',
            'criteria.evaluations',
            'evaluations'
        ])->get();
        return view('focal.defence.results', compact('schedules'));
    }

    public function markCompleted($id) {
        $schedule = DefenceSchedule::findOrFail($id);
        $schedule->status = 'completed';
        $schedule->save();

        // group status update
        $group = $schedule->group;
        $totalMarks = $schedule->criteria->sum('max_marks');
        $obtained   = $schedule->evaluations->sum('marks_obtained');
        $percentage = $totalMarks > 0 ? ($obtained/$totalMarks)*100 : 0;
        $group->status = $percentage >= 50 ? 'approved' : 'rejected';
        $group->save();
        // Notify group members of result
        $group->load('members.user');
        $result = $percentage >= 50 ? 'Pass' : 'Fail';
        foreach ($group->members as $member) {
            \App\Helpers\NotificationHelper::send(
                $member->user_id,
                'Defence Result: ' . $result,
                'Your defence evaluation is complete. Your group has ' . $result . 'ed the defence.',
                $percentage >= 50 ? 'success' : 'warning',
                '/student/dashboard'
            );
        }

        return back()->with('success','Defence marked as completed. Group status updated.');
    }

    public function updateResult(Request $request, $id)
    {
        $request->validate([
            'result'  => 'required|in:pass,fail',
            'remarks' => 'nullable|string|max:500',
        ]);
        $schedule = \App\Models\DefenceSchedule::findOrFail($id);
        $schedule->update([
            'result'         => $request->result,
            'result_remarks' => $request->remarks,
        ]);

        // Sync the group's status with the defence result.
        // 'approved' status unlocks the Supervisor Assignment option for Focal Person.
        // We never downgrade a group that already has a supervisor assigned.
        $group = $schedule->group;
        if ($group && $group->status !== 'supervisor_assigned') {
            $group->status = $request->result === 'pass' ? 'approved' : 'rejected';
            $group->save();
        }

        // Notify the group members about the defence outcome
        if ($group) {
            $group->load('members.user');
            foreach ($group->members as $member) {
                \App\Helpers\NotificationHelper::send(
                    $member->user_id,
                    'Defence Result: ' . ucfirst($request->result),
                    'Your defence evaluation is complete. Your group has ' . $request->result . 'ed the defence.',
                    $request->result === 'pass' ? 'success' : 'warning',
                    '/student/dashboard'
                );
            }
        }

        return response()->json(['success' => true]);
    }


    public function allowReAttempt(Request $request, $id)
    {
        $old = \App\Models\DefenceSchedule::findOrFail($id);
        $old->update([
            'failure_reason' => $request->reason ?? '',
        ]);
        // Create new attempt
        $new = $old->replicate();
        $new->attempt_number = $old->attempt_number + 1;
        $new->status         = 'scheduled';
        $new->result         = 'pending';
        $new->result_remarks = null;
        $new->failure_reason = null;
        $new->defence_date   = null;
        $new->defence_time   = null;
        $new->save();
        return response()->json(['success' => true, 'message' => 'Re-attempt scheduled!']);
    }

}