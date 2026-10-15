<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FypGroup;
use App\Models\Proposal;

class FocalPersonApiController extends Controller
{
    // Dashboard stats
    public function dashboard(Request $request)
    {
        $totalStudents    = User::where('role', 'student')->count();
        $totalSupervisors = User::where('role', 'faculty')->count();
        $totalGroups      = FypGroup::count();
        $pendingProposals = Proposal::where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'stats'   => [
                'total_students'    => $totalStudents,
                'total_faculty_members' => $totalSupervisors,
                'total_groups'      => $totalGroups,
                'pending_proposals' => $pendingProposals,
            ],
        ]);
    }

    // All students - with filters, only students who have a profile
    public function students(Request $request)
    {
        $query = User::where('role', 'student')
            ->whereHas('studentProfile')
            ->with('studentProfile');

        if ($request->has('semester')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('current_semester', $request->semester);
            });
        }

        if ($request->has('shift')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });
        }

        if ($request->has('department_id')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->has('session_id')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('session_id', $request->session_id);
            });
        }

        $students = $query->with([
            'studentProfile.department',
            'studentProfile.session',
            'groupMembership.group.supervisor'
        ])->get()->map(function ($user) {
            return [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'student_id'    => $user->studentProfile->student_id ?? null,
                'reg_no'        => $user->studentProfile->reg_no ?? null,
                'semester'      => $user->studentProfile->current_semester ?? null,
                'shift'         => $user->studentProfile->shift ?? null,
                'department'    => $user->studentProfile->department->name ?? null,
                'department_id' => $user->studentProfile->department_id ?? null,
                'session'       => $user->studentProfile->session->name ?? null,
                'session_id'    => $user->studentProfile->session_id ?? null,
                'group_code'    => $user->groupMembership->group->group_code ?? null,
                'supervisor'    => $user->groupMembership->group->supervisor->name ?? null,
                'status'        => !$user->groupMembership || !$user->groupMembership->group
                                        ? 'No Group'
                                        : ($user->groupMembership->group->status === 'approved'
                                            ? 'Completed'
                                            : 'In Progress'),
            ];
        });

        return response()->json([
            'success'  => true,
            'total'    => $students->count(),
            'students' => $students,
        ]);
    }

    // All supervisors list
    public function facultyMembers(Request $request)
{
    $supervisors = User::where('role', 'faculty')
        ->with(['supervisorProfile.department'])
        ->get()
        ->map(function ($user) {
            return [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'employee_id' => $user->supervisorProfile->employee_id ?? null,
                'designation' => $user->supervisorProfile->designation ?? null,
                'department'  => $user->supervisorProfile->department->code ?? null,
                'max_groups'  => $user->supervisorProfile->max_groups ?? null,
            ];
        });

    return response()->json([
        'success'     => true,
        'faculty_members' => $supervisors,
    ]);
}

// All groups list
public function groups(Request $request)
{
    $groups = FypGroup::with([
        'members.user',
        'supervisor',
        'proposal'
    ])->get()->map(function ($group) {
        return [
            'id'          => $group->id,
            'group_code'  => $group->group_code,
            'project_title' => $group->project_title ?? 'Not submitted',
            'status'      => $group->status,
            'shift'       => $group->shift,
            'max_members' => $group->max_members,
            'supervisor'  => $group->supervisor ? $group->supervisor->name : 'Not assigned',
            'members_count' => $group->members->where('status', 'accepted')->count(),
            'members'     => $group->members->where('status', 'accepted')->map(function ($m) {
                return [
                    'id'   => $m->user->id ?? null,
                    'name' => $m->user->name ?? '-',
                ];
            })->values(),
            'proposal_status' => $group->proposal ? $group->proposal->status : 'No proposal',
        ];
    });

    return response()->json([
        'success' => true,
        'total'   => $groups->count(),
        'groups'  => $groups,
    ]);
}

// Defence panels list
public function panels(Request $request)
{
    $panels = \App\Models\DefencePanel::with('facultys')->get()
        ->map(function($panel) {
            return [
                'id'       => $panel->id,
                'name'     => $panel->name,
                'status'   => $panel->status,
                'facultys' => $panel->facultys->map(fn($t) => [
                    'id'   => $t->id,
                    'name' => $t->name,
                ]),
            ];
        });

    return response()->json(['success' => true, 'panels' => $panels]);
}

public function storePanel(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'faculty_ids' => 'required|array|min:1',
        'faculty_ids.*' => 'exists:users,id',
    ]);

    $panel = \App\Models\DefencePanel::create([
        'name' => $request->name,
        'description' => $request->description,
        'created_by' => auth()->id(),
        'status' => 'active',
    ]);

    foreach ($request->faculty_ids as $tid) {
        \App\Models\PanelMember::create([
            'defence_panel_id' => $panel->id,
            'user_id' => $tid,
        ]);
    }

    $panel->load('facultys');

    return response()->json([
        'success' => true,
        'message' => 'Panel "'.$panel->name.'" created successfully!',
        'panel' => [
            'id'       => $panel->id,
            'name'     => $panel->name,
            'status'   => $panel->status,
            'facultys' => $panel->facultys->map(fn($t) => [
                'id'   => $t->id,
                'name' => $t->name,
            ]),
        ],
    ]);
}

// Defence schedules list
public function schedules(Request $request)
{
    $schedules = \App\Models\DefenceSchedule::with([
        'group', 'faculty', 'criteria'
    ])->get()->map(function($sch) {
        return [
            'id'           => $sch->id,
            'group_code'   => $sch->group->group_code ?? '-',
            'project_title'=> $sch->group->project_title ?? '-',
            'faculty'      => $sch->faculty->name ?? '-',
            'defence_date' => $sch->defence_date,
            'defence_time' => $sch->defence_time,
            'room'         => $sch->room,
            'status'       => $sch->status,
            'criteria'     => $sch->criteria->map(fn($c) => [
                'name'      => $c->criteria_name,
                'max_marks' => $c->max_marks,
            ]),
        ];
    });

    return response()->json(['success' => true, 'schedules' => $schedules]);
}

// Defence results
public function results(Request $request)
{
    $schedules = \App\Models\DefenceSchedule::where('status', 'completed')
        ->with(['group.members.user', 'faculty', 'criteria', 'evaluations'])
        ->get()->map(function($sch) {
            $totalMax      = $sch->criteria->sum('max_marks');
            $totalObtained = $sch->evaluations->sum('marks_obtained');
            $percentage    = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100) : 0;

            return [
                'id'             => $sch->id,
                'group_id'       => $sch->group->id ?? null,
                'supervisor_id'  => $sch->group->supervisor_id ?? null,
                'group_code'     => $sch->group->group_code ?? '-',
                'project_title'  => $sch->group->project_title ?? '-',
                'group_status'   => $sch->group->status ?? '-',
                'result'         => $sch->result ?? 'pending',
                'result_remarks' => $sch->result_remarks ?? '',
                'attempt_number'  => $sch->attempt_number ?? 1,
                'failure_reason'  => $sch->failure_reason ?? '',
                'faculty'        => $sch->faculty->name ?? '-',
                'defence_date'   => $sch->defence_date,
                'defence_time'   => $sch->defence_time,
                'room'           => $sch->room,
                'total_max'      => $totalMax,
                'total_obtained' => $totalObtained,
                'percentage'     => $percentage,
                'members'        => $sch->group->members->map(fn($m) => [
                    'name' => $m->user->name ?? '-',
                ]),
                'criteria'       => $sch->criteria->map(fn($c) => [
                    'name'      => $c->criteria_name,
                    'max_marks' => $c->max_marks,
                    'obtained'  => $sch->evaluations->where('criteria_id', $c->id)->sum('marks_obtained'),
                ]),
            ];
        });

    return response()->json(['success' => true, 'results' => $schedules]);
}

    public function phasesList(Request $request)
    {
        $phases = \App\Models\FypPhase::orderBy('name')->get(['id', 'name']);
        return response()->json(['phases' => $phases]);
    }

    public function marksReport(Request $request)
    {
        $query = \App\Models\StudentPhaseMark::with([
            'student',
            'phase',
            'evaluator',
            'schedule.phase'
        ]);

        if ($request->session_id) {
            $query->whereHas('student.studentProfile', fn($q) => $q->where('session_id', $request->session_id));
        }
        if ($request->department_id) {
            $query->whereHas('student.studentProfile', fn($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->phase_id) {
            $query->where('phase_id', $request->phase_id);
        }

        $records = $query->get()->map(function($e) {
            $student = $e->student;
            $profile = $student ? $student->studentProfile : null;
            $session = $profile ? \App\Models\AcademicSession::find($profile->session_id) : null;
            $dept    = $profile ? \App\Models\Department::find($profile->department_id) : null;
            return [
                'student_id'      => $student->student_id ?? ($student->id ?? '—'),
                'student_name'    => $student->name ?? '—',
                'session'         => $session->name ?? '—',
                'department'      => $dept->name ?? '—',
                'phase'           => $e->phase->name ?? '—',
                'marks_obtained'  => $e->marks_obtained,
                'total_marks'     => $e->max_marks,
                'converted_marks' => $e->converted_marks,
                'evaluator'       => $e->evaluator->name ?? '—',
                'is_adjusted'     => false,
            ];
        });

        return response()->json(['marks' => $records]);
    }


    public function sessions(Request $request)
    {
        $sessions = \App\Models\AcademicSession::orderBy('name')->get(['id', 'name']);
        return response()->json(['sessions' => $sessions]);
    }

    public function departments(Request $request)
    {
        $departments = \App\Models\Department::orderBy('name')->get(['id', 'name']);
        return response()->json(['departments' => $departments]);
    }


    public function groupTracking(Request $request)
    {
        $groups = \App\Models\FypGroup::with(["members.user","supervisor"])->get()->map(function($g) {
            return [
                "id"            => $g->id,
                "group_code"    => $g->group_code,
                "project_title" => $g->project_title ?? "No title",
                "supervisor"    => $g->supervisor->name ?? "Not assigned",
                "status"        => $g->status,
                "members_count" => $g->members->count(),
            ];
        });
        return response()->json(["groups" => $groups]);
    }

    public function groupTrackingDetail(Request $request, $id)
    {
        $group = \App\Models\FypGroup::with(["members.user","supervisor"])->findOrFail($id);

        $defences = \App\Models\DefenceSchedule::where("fyp_group_id", $id)
            ->with(["faculty","criteria","evaluations"])
            ->orderBy("attempt_number")
            ->get()->map(function($d) {
                $totalMax      = $d->criteria->sum("max_marks");
                $totalObtained = $d->evaluations->sum("marks_obtained");
                $percentage    = $totalMax > 0 ? round(($totalObtained/$totalMax)*100) : 0;
                return [
                    "attempt"        => $d->attempt_number ?? 1,
                    "date"           => $d->defence_date,
                    "status"         => $d->status,
                    "result"         => $totalMax > 0 ? ($percentage >= 50 ? "pass" : "fail") : "pending",
                    "percentage"     => $percentage,
                    "total_obtained" => $totalObtained,
                    "total_max"      => $totalMax,
                    "evaluator"      => $d->faculty->name ?? "-",
                    "failure_reason" => $d->failure_reason ?? "",
                ];
            });

        $phaseMarks = \App\Models\StudentPhaseMark::whereHas("student", function($q) use($group) {
            $q->whereIn("id", $group->members->pluck("user_id"));
        })->with(["phase","evaluator","student"])->get()
          ->groupBy("phase_id")->map(function($marks) {
            $first = $marks->first();
            return [
                "phase" => $first->phase->name ?? "-",
                "marks" => $marks->map(fn($m) => [
                    "student"   => $m->student->name ?? "-",
                    "obtained"  => $m->marks_obtained,
                    "max"       => $m->max_marks,
                    "evaluator" => $m->evaluator->name ?? "-",
                ])->values(),
            ];
        })->values();

        return response()->json([
            "group" => [
                "id"            => $group->id,
                "group_code"    => $group->group_code,
                "project_title" => $group->project_title ?? "-",
                "supervisor"    => $group->supervisor->name ?? "Not assigned",
                "status"        => $group->status === "supervisor_assigned" ? "Assigned" : ucfirst($group->status),
                "members"       => $group->members->map(fn($m) => ["name" => $m->user->name ?? "-"])->values(),
            ],
            "defences"    => $defences,
            "phase_marks" => $phaseMarks,
        ]);
    }


    public function groupsForBulk(Request $request)
    {
        $query = \App\Models\FypGroup::with(['members.user']);
        if ($request->session_id) {
            $query->whereHas('members.user', fn($q) => $q->where('session_id', $request->session_id));
        }
        if ($request->department_id) {
            $query->whereHas('members.user', fn($q) => $q->where('department_id', $request->department_id));
        }
        // Exclude groups already assigned to this phase
        if ($request->phase_id) {
            $assigned = \App\Models\PhaseSchedule::where('phase_id', $request->phase_id)->pluck('group_id');
            $query->whereNotIn('id', $assigned);
        }
        $groups = $query->get()->map(fn($g) => [
            'id'            => $g->id,
            'group_code'    => $g->group_code,
            'project_title' => $g->project_title ?? 'No Title',
        ]);
        return response()->json(['groups' => $groups]);
    }

    public function bulkAssignPhase(Request $request)
    {
        $request->validate([
            'phase_id'      => 'required|exists:fyp_phases,id',
            'group_ids'     => 'required|array|min:1',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required',
        ]);
        $count = 0;
        foreach ($request->group_ids as $groupId) {
            \App\Models\PhaseSchedule::create([
                'phase_id'      => $request->phase_id,
                'group_id'      => $groupId,
                'schedule_date' => $request->schedule_date,
                'schedule_time' => $request->schedule_time,
                'room'          => $request->room ?? '',
                'status'        => 'scheduled',
            ]);
            $count++;
        }
        return response()->json(['success' => true, 'count' => $count]);
    }


    public function analytics(Request $request)
    {
        // Pass/Fail breakdown across all completed defences
        $defences = \App\Models\DefenceSchedule::with(['criteria','evaluations'])->get();
        $passCount = 0; $failCount = 0; $pendingCount = 0;
        foreach ($defences as $d) {
            $totalMax = $d->criteria->sum('max_marks');
            $totalObtained = $d->evaluations->sum('marks_obtained');
            if ($totalMax == 0) { $pendingCount++; continue; }
            $pct = round(($totalObtained / $totalMax) * 100);
            if ($pct >= 50) $passCount++; else $failCount++;
        }

        // Group status breakdown
        $groupStatusRaw = \App\Models\FypGroup::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')->get();
        $groupStatus = $groupStatusRaw->map(fn($g) => [
            'status' => $g->status === 'supervisor_assigned' ? 'Assigned' : ucfirst(str_replace('_',' ',$g->status)),
            'count' => $g->count
        ]);

        // Average marks per phase
        $phaseAverages = \App\Models\FypPhase::all()->map(function($phase) {
            $marks = \App\Models\StudentPhaseMark::where('phase_id', $phase->id)->get();
            $avgPct = $marks->count() > 0
                ? round($marks->avg(fn($m) => $m->max_marks > 0 ? ($m->marks_obtained / $m->max_marks) * 100 : 0), 1)
                : 0;
            return ['phase' => $phase->name, 'average_percentage' => $avgPct, 'evaluations_count' => $marks->count()];
        });

        // Session-wise group counts
        $sessionCounts = \App\Models\AcademicSession::all()->map(function($s) {
            return ['session' => $s->name, 'groups' => \App\Models\FypGroup::where('session_id', $s->id)->count()];
        });

        // Re-attempt stats
        $reattempts = \App\Models\DefenceSchedule::where('attempt_number', '>', 1)->count();

        return response()->json([
            'pass_fail' => ['pass' => $passCount, 'fail' => $failCount, 'pending' => $pendingCount],
            'group_status' => $groupStatus,
            'phase_averages' => $phaseAverages,
            'session_counts' => $sessionCounts,
            'reattempts' => $reattempts,
            'total_groups' => \App\Models\FypGroup::count(),
            'total_defences' => $defences->count(),
        ]);
    }

}