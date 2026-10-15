<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\SupervisorProfile;
use App\Models\Department;
use App\Models\AcademicSession;

class FocalPersonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function dashboard()
    {
        $totalStudents    = User::where('role', 'student')->count();
        $totalSupervisors = User::where('role', 'faculty')->count();
        return view('focal.dashboard', compact('totalStudents', 'totalSupervisors'));
    }

    public function registerStudent()
    {
        $departments = Department::where('is_active', 1)->get();
        $sessions    = AcademicSession::where('is_active', 1)->get();
        return view('focal.register_student', compact('departments', 'sessions'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'student_id'       => 'required|string|unique:student_profiles,student_id',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:6',
            'department_id'    => 'required|exists:departments,id',
            'session_id'       => 'required|exists:academic_sessions,id',
            'shift'            => 'required|in:Morning,Evening',
            'current_semester' => 'required|integer|min:1|max:8',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
        ]);

        StudentProfile::create([
            'user_id'          => $user->id,
            'student_id'       => $request->student_id,
            'reg_no'           => $request->reg_no,
            'contact'          => $request->contact,
            'department_id'    => $request->department_id,
            'session_id'       => $request->session_id,
            'batch_id'         => 1,
            'shift'            => $request->shift,
            'current_semester' => $request->current_semester,
        ]);

        return back()->with('success', 'Student registered! Email: '.$request->email.' | Password: '.$request->password);
    }

    public function registerSupervisor()
    {
        $departments = Department::where('is_active', 1)->get();
        return view('focal.register_supervisor', compact('departments'));
    }

    public function storeSupervisor(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'employee_id'   => 'required|string|unique:supervisor_profiles,employee_id',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'department_id' => 'required|exists:departments,id',
            'max_groups'    => 'required|integer|min:1|max:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'faculty',
        ]);

        SupervisorProfile::create([
            'user_id'       => $user->id,
            'employee_id'   => $request->employee_id,
            'contact'       => $request->contact,
            'designation'   => $request->designation,
            'department_id' => $request->department_id,
            'max_groups'    => $request->max_groups,
        ]);

        return back()->with('success', 'Faculty registered! Email: '.$request->email.' | Password: '.$request->password);
    }

    public function allStudents(Request $request)
    {
        $departments = Department::where('is_active', 1)->get();
        $sessions    = AcademicSession::where('is_active', 1)->get();

        $query = User::where('role', 'student')
            ->with(['studentProfile.department', 'studentProfile.session']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhereHas('studentProfile', function($q2) use ($search) {
                      $q2->where('student_id', 'like', "%$search%");
                  });
            });
        }

        if ($request->dept) {
            $query->whereHas('studentProfile', function($q) use ($request) {
                $q->where('department_id', $request->dept);
            });
        }

        if ($request->session) {
            $query->whereHas('studentProfile', function($q) use ($request) {
                $q->where('session_id', $request->session);
            });
        }

        if ($request->shift) {
            $query->whereHas('studentProfile', function($q) use ($request) {
                $q->where('shift', $request->shift);
            });
        }

        $students = $query->get()->filter(function($s) {
            return $s->studentProfile !== null;
        });

        $grouped = $students->groupBy([
            function($s) { return optional(optional($s->studentProfile)->department)->name ?? 'Unknown'; },
            function($s) { return optional(optional($s->studentProfile)->session)->name ?? 'Unknown'; },
            function($s) { return $s->studentProfile->shift ?? 'Unknown'; },
        ]);

        $totalCount   = $students->count();
        $morningCount = $students->filter(function($s) { return optional($s->studentProfile)->shift == 'Morning'; })->count();
        $eveningCount = $students->filter(function($s) { return optional($s->studentProfile)->shift == 'Evening'; })->count();

        return view('focal.students', compact('grouped', 'departments', 'sessions', 'totalCount', 'morningCount', 'eveningCount'));
    }

    public function showStudent($id)
    {
        $student = User::where('role', 'student')
            ->with(['studentProfile.department', 'studentProfile.session'])
            ->findOrFail($id);
        return view('focal.student_detail', compact('student'));
    }


    public function editStudent($id)
    {
        $student = \App\Models\User::where('role','student')
            ->with(['studentProfile.department','studentProfile.session'])
            ->findOrFail($id);
        $departments = \App\Models\Department::all();
        $sessions = \App\Models\AcademicSession::all();
        return view('focal.student_edit', compact('student','departments','sessions'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = \App\Models\User::where('role','student')->findOrFail($id);
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'department_id'    => 'required',
            'session_id'       => 'required',
            'shift'            => 'required',
            'current_semester' => 'required|integer|min:1|max:8',
        ]);
        $student->update(['name'=>$request->name,'email'=>$request->email]);
        if($student->studentProfile){
            $student->studentProfile->update([
                'department_id'    => $request->department_id,
                'session_id'       => $request->session_id,
                'shift'            => $request->shift,
                'current_semester' => $request->current_semester,
            ]);
        }
        if($request->header('X-Requested-With')==='XMLHttpRequest')
            return response()->json(['success'=>'Student updated successfully!']);
        return redirect('/focal/students/'.$id)->with('success','Student updated successfully!');
    }

    public function deleteStudent(Request $request, $id)
    {
        $student = \App\Models\User::where('role','student')->findOrFail($id);
        $student->studentProfile()->delete();
        $student->delete();
        if($request->header('X-Requested-With')==='XMLHttpRequest')
            return response()->json(['success'=>'Student deleted successfully!']);
        return redirect('/focal/students')->with('success','Student deleted.');
    }
    public function allSupervisors()
    {
        $supervisors = User::where('role', 'faculty')->orWhere('role', 'supervisor')->with('supervisorProfile.department')->latest()->get();
        return view('focal.supervisors', compact('supervisors'));
    }

    public function groups()
    {
        $groups = \App\Models\FypGroup::with([
            'members.user.studentProfile',
            'proposal',
            'supervisor',
            'department',
            'session'
        ])->orderBy('created_at','desc')->get();

        $supervisors = \App\Models\User::where('role','faculty')
            ->orWhere('role','supervisor')
            ->with('supervisorProfile')->get();

        return view('focal.groups', compact('groups','supervisors'));
    }

    public function assignSupervisor(Request $request,$groupId)
    {
        $facultyId = $request->faculty_id ?? $request->supervisor_id;

        $group = \App\Models\FypGroup::findOrFail($groupId);
        $isReassignment = !empty($group->supervisor_id) && $group->supervisor_id != $facultyId;
        $group->update(['supervisor_id'=>$facultyId,'status'=>'supervisor_assigned']);

        $group->load('members.user');
        foreach ($group->members as $member) {
            \App\Helpers\NotificationHelper::send(
                $member->user_id,
                'Supervisor Assigned',
                'A supervisor has been assigned to your group "' . $group->project_title . '".',
                'success',
                '/student/dashboard'
            );
        }

        \App\Helpers\NotificationHelper::send(
            $facultyId,
            $isReassignment ? 'Supervisor Reassignment' : 'New Group Assigned',
            'You have been assigned as supervisor for group "' . $group->project_title . '" (' . $group->group_code . ').',
            'info',
            '/faculty/dashboard'
        );

        if($request->header('X-Requested-With')==='XMLHttpRequest')
            return response()->json(['success'=>'Supervisor assigned successfully!']);
        return back()->with('success','Supervisor assigned successfully!');
    }

    public function reviewProposal(Request $request, $proposalId)
    {
        $proposal = \App\Models\Proposal::findOrFail($proposalId);
        $status = $request->input('action') === 'approve' ? 'approved' : 'rejected';
        $proposal->update(['status'=>$status,'focal_remarks'=>$request->remarks]);
        if($request->header('X-Requested-With')==='XMLHttpRequest')
            return response()->json(['success'=>'Proposal '.$request->status.'!']);
        return back()->with('success','Proposal reviewed!');
    }



    public function deleteGroup($groupId) {
        $group = \App\Models\FypGroup::findOrFail($groupId);
        \App\Models\GroupMember::where("fyp_group_id",$groupId)->delete();
        \App\Models\GroupJoinRequest::where("fyp_group_id",$groupId)->delete();
        \App\Models\Proposal::where("fyp_group_id",$groupId)->delete();
        $group->delete();
        return back()->with("success","Group deleted!");
    }

    public function allowRetry($groupId) {
        $group = \App\Models\FypGroup::findOrFail($groupId);
        $group->update(['status' => 'supervisor_assigned']);
        \App\Models\Proposal::where('fyp_group_id', $groupId)->update(['status' => 'pending']);
        return back()->with('success', 'Group can now resubmit proposal.');
    }

    public function settings() {
        $settings = \App\Models\SystemSetting::pluck('value','key');
        return view('focal.settings', compact('settings'));
    }
    public function updateSettings(Request $request) {
        $fields = [
            'group_formation_open',
        ];
        foreach ($fields as $key) {
            if ($request->has($key)) {
                \App\Models\SystemSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }
        return redirect()->route('focal.settings')->with('success', 'Settings updated successfully!');
    }


    public function attendanceSummary(Request $request)
    {
        $query = \App\Models\FypGroup::with([
            'members.user.studentProfile',
            'department',
            'session',
        ])->whereHas('members');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('session_id')) {
            $query->where('session_id', $request->session_id);
        }
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('group_code', 'like', "%{$search}%")
                  ->orWhereHas('members.user', function ($qq) use ($search) {
                      $qq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $groups = $query->orderBy('group_code')->paginate(8)->withQueryString();

        $groupIds = $groups->pluck('id');

        $meetingsByGroup = \App\Models\MeetingSchedule::whereIn('group_id', $groupIds)
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->get()
            ->groupBy('group_id');

        $studentIds = $groups->flatMap(fn($g) => $g->members->pluck('user_id'));

        $attendanceByStudent = \App\Models\Attendance::whereIn('group_id', $groupIds)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->groupBy('student_id');

        $groups->through(function ($group) use ($meetingsByGroup, $attendanceByStudent) {
            $meetings = $meetingsByGroup->get($group->id, collect());
            $group->totalMeetings = $meetings->count();

            $group->studentsData = $group->members->map(function ($member) use ($meetings, $attendanceByStudent) {
                $studentAtt = $attendanceByStudent->get($member->user_id, collect())->keyBy('meeting_id');
                $present = $studentAtt->where('status', 'present')->count();
                $absent = $studentAtt->where('status', 'absent')->count();
                $total = $meetings->count();
                $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

                $meetingRows = $meetings->map(function ($m) use ($studentAtt) {
                    $att = $studentAtt->get($m->id);
                    return (object) [
                        'meeting_id' => $m->id,
                        'date'       => $m->meeting_date,
                        'status'     => $att->status ?? null,
                        'remarks'    => $att->remarks ?? null,
                    ];
                });

                return (object) [
                    'user'        => $member->user,
                    'present'     => $present,
                    'absent'      => $absent,
                    'total'       => $total,
                    'percentage'  => $percentage,
                    'meetingRows' => $meetingRows,
                ];
            });

            return $group;
        });

        $departments = \App\Models\Department::where('is_active', 1)->orderBy('name')->get();
        $sessions = \App\Models\AcademicSession::where('is_active', 1)->orderBy('name')->get();

        return view('focal.attendance-summary', compact('groups', 'departments', 'sessions'));
    }

    public function updateAttendance(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required|exists:meeting_schedules,id',
            'student_id' => 'required|exists:users,id',
            'group_id'   => 'required|exists:fyp_groups,id',
            'status'     => 'required|in:present,absent',
            'remarks'    => 'nullable|string|max:255',
        ]);

        $meeting = \App\Models\MeetingSchedule::findOrFail($request->meeting_id);

        $attendance = \App\Models\Attendance::updateOrCreate(
            ['meeting_id' => $request->meeting_id, 'student_id' => $request->student_id],
            [
                'group_id'     => $request->group_id,
                'marked_by'    => auth()->id(),
                'meeting_date' => $meeting->meeting_date,
                'status'       => $request->status,
                'remarks'      => $request->remarks,
            ]
        );

        return response()->json(['success' => true, 'status' => $attendance->status]);
    }

    // Password reset requests - list pending + resolved
    public function passwordResetRequests()
    {
        $requests = \App\Models\PasswordResetRequest::with(['user', 'resolvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $totalCount = \App\Models\PasswordResetRequest::count();
        $pendingCount = \App\Models\PasswordResetRequest::where('status', 'pending')->count();
        $resolvedCount = \App\Models\PasswordResetRequest::where('status', 'resolved')->count();
        return view('focal.password-reset-requests', compact('requests', 'totalCount', 'pendingCount', 'resolvedCount'));
    }

    public function deletePasswordReset($id)
    {
        $resetRequest = \App\Models\PasswordResetRequest::findOrFail($id);
        $resetRequest->delete();
        return back()->with('success', 'Request deleted.');
    }

    public function resolvePasswordReset(\Illuminate\Http\Request $request, $id)
    {
        $resetRequest = \App\Models\PasswordResetRequest::findOrFail($id);
        if ($resetRequest->status === 'resolved') {
            return back()->with('error', 'This request has already been resolved.');
        }

        $request->validate([
            'custom_password' => 'nullable|string|min:6|max:30',
        ]);

        $customPassword = trim((string) $request->input('custom_password'));
        $newPassword = $customPassword !== '' ? $customPassword : \Illuminate\Support\Str::random(8);

        $user = $resetRequest->user;
        $user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
        $user->save();
        $resetRequest->update([
            'status'              => 'resolved',
            'new_password_plain'  => $newPassword,
            'resolved_by'         => auth()->id(),
            'resolved_at'         => now(),
        ]);
        return back()->with('success', 'New password set for '.$user->name.': '.$newPassword.' — please share this with the user securely.');
    }
}
