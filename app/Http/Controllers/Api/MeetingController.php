<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\MeetingSchedule;
use App\Models\FypGroup;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    // Faculty: naya meeting schedule karna
    public function store(Request $request)
    {
        $request->validate([
            'group_id'     => 'required|exists:fyp_groups,id',
            'meeting_date' => 'required|date',
            'meeting_time' => 'nullable',
            'agenda'       => 'nullable|string|max:255',
        ]);

        $group = FypGroup::findOrFail($request->group_id);
        if ((int) $group->supervisor_id !== (int) $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'You are not the supervisor of this group.'], 403);
        }

        $meeting = MeetingSchedule::create([
            'group_id'     => $group->id,
            'scheduled_by' => $request->user()->id,
            'meeting_date' => $request->meeting_date,
            'meeting_time' => $request->meeting_time,
            'agenda'       => $request->agenda,
            'status'       => 'scheduled',
        ]);

        // Group ke students ko notification bhejna
        $timeText = $request->meeting_time ? ' at ' . $request->meeting_time : '';
        foreach ($group->members as $member) {
            if ($member->user_id) {
                \App\Helpers\NotificationHelper::send(
                    $member->user_id,
                    'New Meeting Scheduled',
                    'Your supervisor scheduled a meeting on ' . $request->meeting_date . $timeText . ($request->agenda ? ' — ' . $request->agenda : '') . '.',
                    'info',
                    '/student/dashboard'
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'Meeting scheduled successfully!', 'meeting' => $meeting]);
    }

    // Faculty: ek meeting ko update karna (date/time/agenda/status)
    public function update(Request $request, $id)
    {
        $meeting = MeetingSchedule::findOrFail($id);
        $group = $meeting->group;

        if ((int) $group->supervisor_id !== (int) $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'You are not the supervisor of this group.'], 403);
        }

        $request->validate([
            'meeting_date' => 'sometimes|required|date',
            'meeting_time' => 'nullable',
            'agenda'       => 'nullable|string|max:255',
            'status'       => 'nullable|in:scheduled,completed,cancelled',
        ]);

        $meeting->update($request->only(['meeting_date', 'meeting_time', 'agenda', 'status']));

        return response()->json(['success' => true, 'message' => 'Meeting updated successfully!', 'meeting' => $meeting]);
    }

    // Faculty: meeting delete karna
    public function destroy(Request $request, $id)
    {
        $meeting = MeetingSchedule::findOrFail($id);
        $group = $meeting->group;

        if ((int) $group->supervisor_id !== (int) $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'You are not the supervisor of this group.'], 403);
        }

        // Meeting ke sath jo attendance records hain wo bhi delete kar dein
        \App\Models\Attendance::where('meeting_id', $id)->delete();
        $meeting->delete();

        return response()->json(['success' => true, 'message' => 'Meeting deleted successfully!']);
    }

    // Faculty: apne ek group ki saari meetings (schedule + history dono)
    public function facultyMeetings(Request $request, $groupId)
    {
        $group = FypGroup::findOrFail($groupId);
        if ((int) $group->supervisor_id !== (int) $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $meetings = MeetingSchedule::where('group_id', $groupId)
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->get();

        return response()->json(['success' => true, 'meetings' => $meetings]);
    }

    // Student: apni group ki meetings
    public function studentMeetings(Request $request)
    {
        $student = $request->user();

        $membership = \App\Models\GroupMember::where('user_id', $student->id)
            ->where('status', 'accepted')
            ->first();

        if (!$membership) {
            return response()->json(['success' => true, 'meetings' => []]);
        }

        $meetings = MeetingSchedule::where('group_id', $membership->fyp_group_id)
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->get();

        return response()->json(['success' => true, 'meetings' => $meetings]);
    }

    // Faculty/Student dono: ek meeting ki attendance status (kis ne mark ki, kaun present/absent)
    public function meetingAttendance(Request $request, $meetingId)
    {
        $meeting = MeetingSchedule::findOrFail($meetingId);
        $user = $request->user();

        $group = $meeting->group;
        $isSupervisor = (int) $group->supervisor_id === (int) $user->id;
        $isMember = \App\Models\GroupMember::where('fyp_group_id', $group->id)
            ->where('user_id', $user->id)->where('status', 'accepted')->exists();

        if (!$isSupervisor && !$isMember) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $records = \App\Models\Attendance::where('meeting_id', $meetingId)
            ->with('student')
            ->get()
            ->map(fn($r) => [
                'student_id'   => $r->student_id,
                'student_name' => $r->student?->name,
                'status'       => $r->status,
                'remarks'      => $r->remarks,
            ]);

        return response()->json(['success' => true, 'meeting' => $meeting, 'attendance' => $records]);
    }
}
