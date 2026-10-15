<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FypGroup;
use App\Models\DefenceSchedule;
use App\Models\DefencePanel;
use App\Models\PanelMember;
use App\Models\PhaseSchedule;
use App\Models\Attendance;
use App\Models\MeetingSchedule;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $supervisor = auth()->user();
        $groups = FypGroup::where('supervisor_id', $supervisor->id)
            ->with(['members.user.studentProfile', 'proposal', 'department', 'session'])
            ->get();

        // Har group ke completed phase results (average only - confidential)
        $groupIds = $groups->pluck('id');
        $phaseResults = PhaseSchedule::with('phase')
            ->whereIn('group_id', $groupIds)
            ->where('status', 'completed')
            ->get()
            ->groupBy('group_id');
        $totalStudents = $groups->sum(function($g){ return $g->members->count(); });
        $defences = DefenceSchedule::where('assigned_teacher_id', $supervisor->id)
            ->with(['group.members.user', 'panel'])
            ->get();
        return view('faculty.dashboard', compact('supervisor', 'groups', 'totalStudents', 'defences', 'phaseResults'));
    }

    public function myPanels()
    {
        $faculty = auth()->user();
        $myPanelIds = PanelMember::where('user_id', $faculty->id)->pluck('defence_panel_id');
        $myPanels = DefencePanel::with('facultys')->whereIn('id', $myPanelIds)->get();
        return view('faculty.my-panels', compact('myPanels', 'faculty'));
    }

    // Attendance page load: groups list + (agar group select ho) meetings, students, history
    public function attendance(Request $request)
    {
        $groups = FypGroup::where('supervisor_id', auth()->id())
            ->with('members.user')
            ->get();

        $selectedGroup = null;
        $meetings = collect();
        $history = collect();

        if ($request->filled('group_id')) {
            $selectedGroup = $groups->firstWhere('id', (int) $request->group_id);

            if ($selectedGroup) {
                $meetings = MeetingSchedule::where('group_id', $selectedGroup->id)
                    ->orderBy('meeting_date', 'desc')
                    ->orderBy('meeting_time', 'desc')
                    ->get();

                $history = Attendance::where('group_id', $selectedGroup->id)
                    ->with('student')
                    ->orderBy('meeting_date', 'desc')
                    ->get()
                    ->groupBy('meeting_date');
            }
        }

        return view('faculty.attendance', compact('groups', 'selectedGroup', 'meetings', 'history'));
    }

    // Manage Meetings page: groups list + (agar group select ho) uski meetings
    public function meetings(Request $request)
    {
        $groups = FypGroup::where('supervisor_id', auth()->id())
            ->with('members.user')
            ->get();
        $selectedGroup = null;
        $meetingsList = collect();
        if ($request->filled('group_id')) {
            $selectedGroup = $groups->firstWhere('id', (int) $request->group_id);
            if ($selectedGroup) {
                $meetingsList = MeetingSchedule::where('group_id', $selectedGroup->id)
                    ->orderBy('meeting_date', 'desc')
                    ->orderBy('meeting_time', 'desc')
                    ->get();
            }
        }
        return view('faculty.meetings', compact('groups', 'selectedGroup', 'meetingsList'));
    }

    // Attendance submit (web se) - meeting_id ke against
    public function markAttendance(Request $request)
    {
        $request->validate([
            'group_id'                 => 'required|exists:fyp_groups,id',
            'meeting_id'               => 'required|exists:meeting_schedules,id',
            'attendance'               => 'required|array|min:1',
            'attendance.*.status'      => 'required|in:present,absent,late',
            'attendance.*.remarks'     => 'nullable|string|max:255',
        ]);

        $group = FypGroup::findOrFail($request->group_id);
        if ($group->supervisor_id !== auth()->id()) {
            abort(403, 'You are not the supervisor of this group.');
        }

        $meeting = MeetingSchedule::findOrFail($request->meeting_id);
        if ($meeting->group_id !== $group->id) {
            abort(403, 'Meeting does not belong to this group.');
        }

        foreach ($request->attendance as $studentId => $entry) {
            Attendance::updateOrCreate(
                [
                    'group_id'     => $group->id,
                    'student_id'   => $studentId,
                    'meeting_id'   => $meeting->id,
                ],
                [
                    'marked_by'    => auth()->id(),
                    'meeting_date' => $meeting->meeting_date,
                    'status'       => $entry['status'],
                    'remarks'      => $entry['remarks'] ?? null,
                ]
            );
        }

        $meeting->update(['status' => 'completed']);

        return redirect()
            ->route('faculty.attendance', ['group_id' => $group->id])
            ->with('success', 'Attendance marked successfully!');
    }
}
