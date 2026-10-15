<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FypGroup;
use App\Models\DefenceSchedule;

class FacultyApiController extends Controller
{
    // Supervisor dashboard - assigned groups
    public function dashboard(Request $request)
    {
        $supervisor = $request->user();

        $assignedGroups = FypGroup::where('supervisor_id', $supervisor->id)
            ->with(['members.user', 'proposal'])
            ->get();

        return response()->json([
            'success'         => true,
            'supervisor'      => [
                'id'    => $supervisor->id,
                'name'  => $supervisor->name,
                'email' => $supervisor->email,
            ],
            'assigned_groups' => $assignedGroups,
            'total_groups'    => $assignedGroups->count(),
        ]);
    }

    // Defence schedules assigned to this supervisor
    public function defence(Request $request)
    {
        $supervisor = $request->user();

        $schedules = DefenceSchedule::where('assigned_teacher_id', $supervisor->id)
            ->with(['group.members.user', 'panel'])
            ->get();

        return response()->json([
            'success'   => true,
            'schedules' => $schedules,
        ]);
    }
}