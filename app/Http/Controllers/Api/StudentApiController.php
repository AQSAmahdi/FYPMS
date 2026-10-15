<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FypGroup;
use App\Models\GroupMember;
use App\Models\GroupJoinRequest;
use App\Models\DefenceSchedule;
use App\Models\SystemSetting;

class StudentApiController extends Controller
{
    // Student dashboard - group info, open groups, requests
    public function dashboard(Request $request)
    {
        $student = $request->user();
        $profile = $student->studentProfile;

        // Check if student is in a group
        $myGroup = null;
        $membership = GroupMember::where('user_id', $student->id)
            ->where('status', 'accepted')
            ->first();

        if ($membership) {
            $myGroup = FypGroup::with([
                'members.user.studentProfile',
                'proposal',
                'supervisor'
            ])->find($membership->fyp_group_id);
        }

        // Open groups in same department/session/shift
        $openGroups = collect();
        if ($profile) {
            $openGroups = FypGroup::where('status', 'open')
                ->where('department_id', $profile->department_id)
                ->where('session_id', $profile->session_id)
                ->where('shift', $profile->shift)
                ->with('members')
                ->get();
        }

        // My pending join requests
        $myRequests = GroupJoinRequest::where('user_id', $student->id)
            ->where('status', 'pending')
            ->with('group')
            ->get();

        // Defence schedule if any
        $defence = null;
        if ($myGroup) {
            $defence = DefenceSchedule::where('fyp_group_id', $myGroup->id)
                ->first();
        }

        return response()->json([
            'success'     => true,
            'student'     => [
                'id'       => $student->id,
                'name'     => $student->name,
                'email'    => $student->email,
                'semester' => $profile->current_semester ?? null,
                'shift'    => $profile->shift ?? null,
            ],
            'my_group'    => $myGroup,
            'open_groups' => $openGroups,
            'my_requests' => $myRequests,
            'defence'     => $defence,
        ]);
    }
}