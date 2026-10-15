<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\FypGroup;
use App\Models\MeetingSchedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Student: apni attendance dekhna
    public function myAttendance(Request $request)
    {
        $student = $request->user();

        $membership = \App\Models\GroupMember::where('user_id', $student->id)
            ->where('status', 'accepted')
            ->first();

        if (!$membership) {
            return response()->json(['success' => true, 'attendance' => []]);
        }

        $records = Attendance::where('group_id', $membership->fyp_group_id)
            ->where('student_id', $student->id)
            ->with('meeting')
            ->orderBy('meeting_date', 'desc')
            ->get()
            ->map(fn($r) => [
                'meeting_id'   => $r->meeting_id,
                'meeting_date' => $r->meeting_date,
                'agenda'       => $r->meeting?->agenda,
                'status'       => $r->status,
                'remarks'      => $r->remarks,
            ]);

        return response()->json(['success' => true, 'attendance' => $records]);
    }

    // Faculty: apne supervised groups ki list (attendance mark karne ke liye)
    public function myGroups(Request $request)
    {
        $groups = FypGroup::where('supervisor_id', $request->user()->id)
            ->with('members.user')
            ->get()
            ->map(function ($g) {
                return [
                    'id'           => $g->id,
                    'group_code'   => $g->group_code,
                    'project_title'=> $g->project_title,
                    'students'     => $g->members->map(fn($m) => [
                        'id'   => $m->user?->id,
                        'name' => $m->user?->name,
                    ]),
                ];
            });

        return response()->json(['success' => true, 'groups' => $groups]);
    }

    // Faculty: attendance mark karna (ek scheduled meeting, sab students)
    public function store(Request $request)
    {
        $request->validate([
            'group_id'                 => 'required|exists:fyp_groups,id',
            'meeting_id'               => 'required|exists:meeting_schedules,id',
            'attendance'               => 'required|array|min:1',
            'attendance.*.student_id' => 'required|exists:users,id',
            'attendance.*.status'     => 'required|in:present,absent,late',
            'attendance.*.remarks'    => 'nullable|string|max:255',
        ]);

        $group = FypGroup::findOrFail($request->group_id);
        if ((int) $group->supervisor_id !== (int) $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'You are not the supervisor of this group.'], 403);
        }

        $meeting = MeetingSchedule::findOrFail($request->meeting_id);
        if ((int) $meeting->group_id !== (int) $group->id) {
            return response()->json(['success' => false, 'message' => 'Meeting does not belong to this group.'], 403);
        }

        foreach ($request->attendance as $entry) {
            Attendance::updateOrCreate(
                [
                    'group_id'   => $group->id,
                    'student_id' => $entry['student_id'],
                    'meeting_id' => $meeting->id,
                ],
                [
                    'marked_by'    => $request->user()->id,
                    'meeting_date' => $meeting->meeting_date,
                    'status'       => $entry['status'],
                    'remarks'      => $entry['remarks'] ?? null,
                ]
            );
        }

        $meeting->update(['status' => 'completed']);

        return response()->json(['success' => true, 'message' => 'Attendance marked successfully!']);
    }

    // Faculty: ek group ki attendance history
    public function groupHistory(Request $request, $groupId)
    {
        $group = FypGroup::findOrFail($groupId);
        if ((int) $group->supervisor_id !== (int) $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $records = Attendance::where('group_id', $groupId)
            ->with(['student', 'meeting'])
            ->orderBy('meeting_date', 'desc')
            ->get()
            ->groupBy('meeting_id')
            ->map(function ($dayRecords, $meetingId) {
                $first = $dayRecords->first();
                return [
                    'meeting_id'   => $meetingId,
                    'meeting_date' => $first->meeting_date,
                    'agenda'       => $first->meeting?->agenda,
                    'students'     => $dayRecords->map(fn($r) => [
                        'student_id'   => $r->student_id,
                        'student_name' => $r->student?->name,
                        'status'       => $r->status,
                        'remarks'      => $r->remarks,
                    ]),
                ];
            })->values();

        return response()->json(['success' => true, 'data' => $records]);
    }
}
