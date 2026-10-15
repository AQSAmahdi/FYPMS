<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FypGroup;
use App\Models\DefenceSchedule;
use App\Models\GroupMember;
use App\Models\GroupJoinRequest;
use App\Models\Proposal;
use App\Models\SystemSetting;
use App\Models\PhaseSchedule;
use App\Models\PhaseDocument;
use App\Models\User;
use App\Helpers\NotificationHelper;

class StudentController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    private function isAjax(Request $request){
        return $request->header('X-Requested-With') === 'XMLHttpRequest';
    }

    private function respond(Request $request, $successMsg, $redirectBack=true, $extra=[]){
        if($this->isAjax($request))
            return response()->json(array_merge(['success'=>$successMsg], $extra));
        return back()->with('success', $successMsg);
    }

    private function respondError(Request $request, $errorMsg){
        if($this->isAjax($request))
            return response()->json(['error'=>$errorMsg], 422);
        return back()->with('error', $errorMsg);
    }

    public function dashboard(){
        $student = auth()->user();
        $profile = $student->studentProfile;
        $myGroup = null;
        $membership = GroupMember::where('user_id',$student->id)->where('status','accepted')->first();
        if($membership){
            $myGroup = FypGroup::with(['members.user.studentProfile','proposal','supervisor'])->find($membership->fyp_group_id);
        }
        $openGroups = collect();
        $myJoinRequests = [];
        $pendingRequests = collect();
        $myPendingRequests = collect();
        if($profile){
            $openGroups = FypGroup::where('status','open')
                ->where('department_id',$profile->department_id)
                ->where('session_id',$profile->session_id)
                ->where('shift',$profile->shift)
                ->whereHas('creator.studentProfile',function($q) use($profile){
                    $q->where('current_semester',$profile->current_semester);
                })
                ->where('created_by','!=',$student->id)
                ->whereDoesntHave('members',function($q) use($student){
                    $q->where('user_id',$student->id)->where('status','accepted');
                })
                ->with(['members.user','creator'])->get();
            $myJoinRequests = GroupJoinRequest::where('user_id',$student->id)->where('status','pending')->pluck('fyp_group_id')->toArray();
            $myPendingRequests = GroupJoinRequest::where('user_id',$student->id)->where('status','pending')->with('group')->get();
        }
        if($myGroup && $myGroup->created_by==$student->id){
            $pendingRequests = GroupJoinRequest::where('fyp_group_id',$myGroup->id)->where('status','pending')->with('user.studentProfile')->get();
        }
        $myDefence = null;
        if($myGroup) {
            $myDefence = DefenceSchedule::where('fyp_group_id',$myGroup->id)->with(['panel'])->first();
        }

        // Deadline reminders - phases with upload_deadline within 3 days or already passed
        $upcomingDeadlines = collect();
        if($myGroup) {
            $upcomingDeadlines = PhaseSchedule::with('phase')
                ->where('group_id', $myGroup->id)
                ->whereNotNull('upload_deadline')
                ->get()
                ->filter(function($s) {
                    $deadline = \Carbon\Carbon::parse($s->upload_deadline)->endOfDay();
                    $daysLeft = now()->diffInDays($deadline, false);
                    return $daysLeft <= 3;
                });
        }

        return view('student.dashboard',compact('student','profile','myGroup','openGroups','myJoinRequests','pendingRequests','myPendingRequests','myDefence','upcomingDeadlines'));
    }

    public function createGroup(Request $request){
        $open = \App\Models\SystemSetting::where('key','group_formation_open')->value('value');
        if(!$open) return $this->respond($request,'Group formation is currently closed.',false);
        $request->validate([
            'project_title' => 'required|string|max:255',
        ]);
        $student = auth()->user();
        $profile = $student->studentProfile;
        if(!$profile) return $this->respondError($request,'Profile not found.');
        if(!SystemSetting::isGroupFormationOpen()) return $this->respondError($request,'Group formation period is closed.');
        if(GroupMember::where('user_id',$student->id)->where('status','accepted')->first())
            return $this->respondError($request,'You are already in a group.');
        if(GroupJoinRequest::where('user_id',$student->id)->where('status','pending')->first())
            return $this->respondError($request,'You have a pending join request. Cancel it first.');
        if(FypGroup::where('created_by',$student->id)->where('status','open')->first())
            return $this->respondError($request,'You already have an open group.');
        $groupCode = FypGroup::generateGroupCode($profile->department->code,$profile->session->name,$profile->shift);
        $group = FypGroup::create([
            'group_code'=>$groupCode,'project_title'=>$request->project_title,'department_id'=>$profile->department_id,
            'session_id'=>$profile->session_id,'shift'=>$profile->shift,
            'created_by'=>$student->id,'status'=>'open','max_members'=>4,
        ]);
        GroupMember::create(['fyp_group_id'=>$group->id,'user_id'=>$student->id,'role'=>'creator','status'=>'accepted']);
        return $this->respond($request,'Group '.$groupCode.' created! Others can now join.',true,['reload'=>true]);
    }

    public function requestJoin(Request $request, $groupId){
        $student = auth()->user();
        if(!SystemSetting::isGroupFormationOpen()) return $this->respondError($request,'Group formation period is closed.');
        if(GroupMember::where('user_id',$student->id)->where('status','accepted')->first())
            return $this->respondError($request,'You are already in a group.');
        if(GroupJoinRequest::where('user_id',$student->id)->where('status','pending')->first())
            return $this->respondError($request,'You already have a pending request. Cancel it first.');
        if(FypGroup::where('created_by',$student->id)->where('status','open')->first())
            return $this->respondError($request,'You have an open group. Lock or delete it first.');
        $group = FypGroup::findOrFail($groupId);
        if($group->members()->count() >= $group->max_members)
            return $this->respondError($request,'Group is full.');
        GroupJoinRequest::create(['fyp_group_id'=>$groupId,'user_id'=>$student->id,'status'=>'pending']);
        return $this->respond($request,'Join request sent! Wait for the creator to accept.',true,['reload'=>true]);
    }

    public function cancelRequest(Request $request, $requestId){
        $req = GroupJoinRequest::where('id',$requestId)->where('user_id',auth()->id())->where('status','pending')->firstOrFail();
        $req->delete();
        return $this->respond($request,'Join request cancelled.',true,['reload'=>true]);
    }

    public function handleRequest(Request $request, $requestId){
        $student = auth()->user();
        $joinReq = GroupJoinRequest::findOrFail($requestId);
        $group = FypGroup::find($joinReq->fyp_group_id);
        if($group->created_by != $student->id) abort(403);
        if($request->action=='accept'){
            if($group->members()->count() >= $group->max_members)
                return $this->respondError($request,'Group is full.');
            GroupMember::create(['fyp_group_id'=>$group->id,'user_id'=>$joinReq->user_id,'role'=>'member','status'=>'accepted']);
            $joinReq->update(['status'=>'accepted']);
            if($group->members()->count() >= $group->max_members)
                $group->update(['status'=>'locked']);
        } else {
            $joinReq->update(['status'=>'rejected']);
        }
        return $this->respond($request,'Done!',true,['reload'=>true]);
    }

    public function deleteGroup(Request $request, $groupId) {
        $student = auth()->user();
        $group = \App\Models\FypGroup::findOrFail($groupId);
        if($group->created_by != $student->id) return back()->with("error","Only creator can delete.");
        if($group->status != "open") return back()->with("error","Cannot delete this group.");
        \App\Models\GroupMember::where("fyp_group_id",$groupId)->delete();
        \App\Models\GroupJoinRequest::where("fyp_group_id",$groupId)->delete();
        $group->delete();
        return redirect("/student/dashboard")->with("success","Group deleted!");
    }

    public function leaveGroup(Request $request, $groupId) {
        $student = auth()->user();
        $membership = \App\Models\GroupMember::where("fyp_group_id",$groupId)->where("user_id",$student->id)->where("role","member")->first();
        if(!$membership) return back()->with("error","You cannot leave this group.");
        $membership->delete();
        \App\Models\GroupJoinRequest::where("fyp_group_id",$groupId)->where("user_id",$student->id)->delete();
        return redirect("/student/dashboard")->with("success","You have left the group.");
    }

    public function lockGroup(Request $request, $groupId){
        $group = FypGroup::findOrFail($groupId);
        if($group->created_by != auth()->id()) abort(403);
        if($group->members()->count() < 2) return $this->respondError($request,'Need at least 2 members to lock group.');
        $group->update(['status'=>'locked']);
        return $this->respond($request,'Group locked! You can now submit your proposal.',true,['reload'=>true]);
    }

    public function downloadProposalDocument(Proposal $proposal)
    {
        $user = auth()->user();
        $group = $proposal->group; // FypGroup
        if (!$group) abort(404);

        $isMember     = GroupMember::where('fyp_group_id', $group->id)->where('user_id', $user->id)->where('status','accepted')->exists();
        $isFocal      = $user->role === 'focal_person';
        $isSupervisor = $group->supervisor_id == $user->id;

        if (!$isMember && !$isFocal && !$isSupervisor) {
            abort(403, 'You are not authorized to view this document.');
        }

        if (!$proposal->file_path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($proposal->file_path)) {
            abort(404, 'Document not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->download($proposal->file_path);
    }

    public function submitProposal(Request $request){
        $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'technology_stack'=>'nullable|string',
            'document'=>'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);
        $student = auth()->user();
        $membership = GroupMember::where('user_id',$student->id)->where('status','accepted')->firstOrFail();
        $group = FypGroup::findOrFail($membership->fyp_group_id);
        if($group->status != 'locked') return $this->respondError($request,'Lock your group first.');
        if($group->proposal) return $this->respondError($request,'Proposal already submitted.');

        $filePath = null;
        if ($request->hasFile('document')) {
            $filePath = $request->file('document')->store('proposals', 'local');
        }

        Proposal::create([
            'fyp_group_id'=>$group->id,'title'=>$request->title,
            'description'=>$request->description,'technology_stack'=>$request->technology_stack,
            'file_path'=>$filePath,'status'=>'pending',
        ]);
        $group->update(['status'=>'proposal_submitted']);
        return $this->respond($request,'Proposal submitted successfully!',true,['reload'=>true]);
    }


    public function myGroup()
    {
        $student = auth()->user();
        $profile = $student->studentProfile;
        $myGroup = null;
        $membership = \App\Models\GroupMember::where('user_id',$student->id)->where('status','accepted')->first();
        if($membership){
            $myGroup = \App\Models\FypGroup::with(['members.user.studentProfile','supervisor','proposal'])->find($membership->fyp_group_id);
        }
        $openGroups = collect();
        $myJoinRequests = [];
        $pendingRequests = collect();
        $myPendingRequests = collect();
        if($profile){
            $openGroups = \App\Models\FypGroup::where('status','open')
                ->where('department_id',$profile->department_id)
                ->where('session_id',$profile->session_id)
                ->where('shift',$profile->shift)
                ->where('created_by','!=',$student->id)
                ->whereDoesntHave('members',function($q) use($student){
                    $q->where('user_id',$student->id)->where('status','accepted');
                })
                ->with(['members.user','creator'])->get();
            $myJoinRequests = \App\Models\GroupJoinRequest::where('user_id',$student->id)->where('status','pending')->pluck('fyp_group_id')->toArray();
            $myPendingRequests = \App\Models\GroupJoinRequest::where('user_id',$student->id)->where('status','pending')->with('group')->get();
        }
        if($myGroup && $myGroup->created_by==$student->id){
            $pendingRequests = \App\Models\GroupJoinRequest::where('fyp_group_id',$myGroup->id)->where('status','pending')->with('user.studentProfile')->get();
        }
        return view('student.my-group', compact('student','profile','myGroup','openGroups','myJoinRequests','pendingRequests','myPendingRequests'));
    }

    public function proposal()
    {
        $student = auth()->user();
        $membership = \App\Models\GroupMember::where('user_id',$student->id)->where('status','accepted')->first();
        $myGroup = $membership ? \App\Models\FypGroup::with(['proposal','supervisor'])->find($membership->fyp_group_id) : null;
        return view('student.proposal', compact('myGroup','student'));
    }

    public function phaseDocuments()
    {
        $student = auth()->user();
        $membership = GroupMember::where('user_id', $student->id)->where('status', 'accepted')->first();
        $myGroup = $membership ? FypGroup::with('supervisor')->find($membership->fyp_group_id) : null;

        $schedules = collect();
        $documents = collect();
        if ($myGroup) {
            $schedules = PhaseSchedule::with('phase')->where('group_id', $myGroup->id)->orderBy('schedule_date')->get();
            $documents = PhaseDocument::where('group_id', $myGroup->id)->get()->keyBy('phase_id');
        }

        return view('student.phase-documents', compact('myGroup', 'schedules', 'documents'));
    }

    public function assistantClear()
    {
        $student = auth()->user();
        \App\Models\ChatMessage::where('user_id', $student->id)->delete();
        return response()->json(['success' => true]);
    }

    public function assistant()
    {
        $student = auth()->user();
        $membership = \App\Models\GroupMember::where('user_id', $student->id)
            ->where('status', 'accepted')->first();
        $myGroup = $membership
            ? \App\Models\FypGroup::with(['supervisor', 'proposal', 'members.user'])
                ->find($membership->fyp_group_id)
            : null;

        $schedules = collect();
        $documents = collect();
        if ($myGroup) {
            $schedules = \App\Models\PhaseSchedule::with('phase')
                ->where('group_id', $myGroup->id)
                ->orderBy('schedule_date')->get();
            $documents = \App\Models\PhaseDocument::where('group_id', $myGroup->id)
                ->get()->keyBy('phase_id');
        }

        $chatHistory = \App\Models\ChatMessage::where('user_id', $student->id)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('student.assistant', compact('student', 'myGroup', 'schedules', 'documents', 'chatHistory'));
    }

    public function assistantChat(\Illuminate\Http\Request $request)
    {
        $student = auth()->user();
        $membership = \App\Models\GroupMember::where('user_id', $student->id)
            ->where('status', 'accepted')->first();
        $myGroup = $membership
            ? \App\Models\FypGroup::with(['supervisor', 'proposal', 'members.user'])
                ->find($membership->fyp_group_id)
            : null;

        $schedules = collect();
        $documents = collect();
        if ($myGroup) {
            $schedules = \App\Models\PhaseSchedule::with('phase')
                ->where('group_id', $myGroup->id)
                ->orderBy('schedule_date')->get();
            $documents = \App\Models\PhaseDocument::where('group_id', $myGroup->id)
                ->get()->keyBy('phase_id');
        }

        // Build context for Gemini
        $context = "You are an FYP (Final Year Project) assistant for BGNU university FYPMS system. ";
        $context .= "Answer only in the same language the student uses (Urdu/English). Be helpful and concise.\n\n";
        $context .= "Student Name: " . $student->name . "\n";

        if ($myGroup) {
            $context .= "Group Code: " . $myGroup->group_code . "\n";
            $context .= "Project Title: " . ($myGroup->project_title ?? $myGroup->proposal->title ?? 'Not set') . "\n";
            $context .= "Supervisor: " . ($myGroup->supervisor->name ?? 'Not assigned') . "\n";
            $context .= "Group Status: " . $myGroup->status . "\n";
            $context .= "Proposal Status: " . ($myGroup->proposal->status ?? 'Not submitted') . "\n";
            $context .= "Members: " . $myGroup->members->pluck('user.name')->join(', ') . "\n\n";

            $context .= "Phase Schedules:\n";
            foreach ($schedules as $s) {
                $docUploaded = isset($documents[$s->phase_id]) ? 'Yes' : 'No';
                $context .= "- " . ($s->phase->name ?? 'Phase') . ": scheduled " . $s->schedule_date .
                    ", result: " . $s->result .
                    ", document uploaded: " . $docUploaded . "\n";
            }
            $context .= "\nIMPORTANT SECURITY RULE: You must NEVER mention, reveal, calculate, or discuss any marks, scores, grades, percentages, or academic performance numbers. If asked about marks or scores, say that marks information is confidential and not available through this assistant.\n";
        } else {
            $context .= "The student is not in any group yet.\n";
        }

        $userMessage = $request->input('message');

        // Save user message
        \App\Models\ChatMessage::create([
            'user_id' => $student->id,
            'role'    => 'user',
            'message' => $userMessage,
        ]);
        $apiKey = env('GROQ_API_KEY');

        $payload = [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'system', 'content' => $context],
                ['role' => 'user', 'content' => $userMessage]
            ],
            'max_tokens' => 500,
            'temperature' => 0.7,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.groq.com/openai/v1/chat/completions");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $reply = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not process your request. Please try again.';

        // Save assistant reply
        \App\Models\ChatMessage::create([
            'user_id' => $student->id,
            'role'    => 'assistant',
            'message' => $reply,
        ]);

        return response()->json(['reply' => $reply]);
    }

    public function myProgress()
    {
        $student = auth()->user();
        $membership = \App\Models\GroupMember::where('user_id', $student->id)
            ->where('status', 'accepted')->first();
        $myGroup = $membership
            ? \App\Models\FypGroup::find($membership->fyp_group_id)
            : null;

        $schedules = collect();
        if ($myGroup) {
            $schedules = PhaseSchedule::with('phase')
                ->where('group_id', $myGroup->id)
                ->orderBy('phase_id')
                ->orderBy('attempt_number')
                ->get()
                ->groupBy('phase_id');
        }

        $proposal = $myGroup ? \App\Models\Proposal::where('fyp_group_id', $myGroup->id)->first() : null;
        return view('student.my-progress', compact('myGroup', 'schedules', 'proposal'));
    }

    public function uploadPhaseDocument(Request $request, PhaseSchedule $schedule)
    {
        $student = auth()->user();
        $membership = GroupMember::where('user_id', $student->id)->where('status', 'accepted')->first();
        if (!$membership || $membership->fyp_group_id != $schedule->group_id) {
            abort(403);
        }

        $phase = $schedule->phase;
        if (!$phase || !$phase->requires_upload) {
            return back()->withErrors(['document' => 'This phase does not require a document upload.']);
        }

        // Deadline check
        $deadline = $schedule->upload_deadline
            ? \Carbon\Carbon::parse($schedule->upload_deadline)->endOfDay()
            : \Carbon\Carbon::parse($schedule->schedule_date)->subDay()->endOfDay();

        $isLate = now()->gt($deadline);

        if ($isLate && !$schedule->late_upload_allowed) {
            return back()->withErrors(['document' => 'Upload deadline has passed (' . $deadline->format('d M Y') . '). Please request your Focal Person to grant special upload permission.']);
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,zip,rar|max:20480',
        ]);

        $file = $request->file('document');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = 'group_' . $schedule->group_id . '_phase_' . $phase->id . '_' . time() . '.' . $ext;
        $storedPath = $file->storeAs('phase-documents', $filename, 'local');

        PhaseDocument::updateOrCreate(
            ['phase_id' => $phase->id, 'group_id' => $schedule->group_id],
            [
                'phase_schedule_id' => $schedule->id,
                'uploaded_by'       => $student->id,
                'original_filename' => $file->getClientOriginalName(),
                'stored_path'       => $storedPath,
                'file_type'         => $ext,
                'file_size'         => $file->getSize(),
                'is_late'           => $isLate,
                'uploaded_at'       => now(),
            ]
        );

        $group = $schedule->group;

        $focalPersons = User::where('role', 'focal_person')->get();
        foreach ($focalPersons as $fp) {
            NotificationHelper::send(
                $fp->id,
                'Phase Document Uploaded',
                'Group "' . $group->group_code . '" uploaded a document for "' . $phase->name . '".',
                'info',
                '/focal/phase-documents'
            );
        }

        if ($group->supervisor_id) {
            NotificationHelper::send(
                $group->supervisor_id,
                'Phase Document Uploaded',
                'Group "' . $group->group_code . '" uploaded a document for "' . $phase->name . '".',
                'info',
                '/faculty/phase-documents'
            );
        }

        return back()->with('success', 'Document uploaded successfully!');
    }


    public function meetings()
    {
        $student = auth()->user();
        $membership = \App\Models\GroupMember::where('user_id', $student->id)
            ->where('status', 'accepted')->first();
        $myGroup = $membership
            ? \App\Models\FypGroup::find($membership->fyp_group_id)
            : null;

        $meetings = collect();
        if ($myGroup) {
            $meetings = \App\Models\MeetingSchedule::where('group_id', $myGroup->id)
                ->orderBy('meeting_date', 'desc')
                ->orderBy('meeting_time', 'desc')
                ->get();

            $myAttendance = \App\Models\Attendance::where('student_id', $student->id)
                ->whereIn('meeting_id', $meetings->pluck('id'))
                ->get()
                ->keyBy('meeting_id');

            $meetings = $meetings->map(function ($meeting) use ($myAttendance) {
                $att = $myAttendance->get($meeting->id);
                $meeting->my_status = $att->status ?? null;
                $meeting->my_remarks = $att->remarks ?? null;
                return $meeting;
            });
        }

        return view('student.meetings', compact('myGroup', 'meetings'));
    }

}