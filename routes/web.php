<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FocalPersonController;
use App\Http\Controllers\StudentController;

    
    
Route::get('/logout-web', function() {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

Route::get('/', function () { return redirect('/login'); });

Route::get('/forgot-password', [App\Http\Controllers\PasswordResetRequestController::class, 'showForm'])->name('password.request.form');
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetRequestController::class, 'submit'])->name('password.request.submit');

Auth::routes();

// Focal Person Routes
Route::middleware(['auth', 'role:focal_person'])->prefix('focal')->group(function () {
    Route::get('/dashboard', [FocalPersonController::class, 'dashboard'])->name('focal.dashboard');
    Route::get('/password-reset-requests', [FocalPersonController::class, 'passwordResetRequests'])->name('focal.password-resets');
    Route::post('/password-reset-requests/{id}/resolve', [FocalPersonController::class, 'resolvePasswordReset'])->name('focal.password-resets.resolve');
    Route::delete('/password-reset-requests/{id}', [FocalPersonController::class, 'deletePasswordReset'])->name('focal.password-resets.delete');
    Route::get('/attendance-summary', [FocalPersonController::class, 'attendanceSummary'])->name('focal.attendance.summary');
    Route::post('/attendance/update', [FocalPersonController::class, 'updateAttendance'])->name('focal.attendance.update');
    Route::get('/students/register', [FocalPersonController::class, 'registerStudent'])->name('focal.students.register');
    Route::post('/students/store', [FocalPersonController::class, 'storeStudent'])->name('focal.students.store');
    Route::get('/students', [FocalPersonController::class, 'allStudents'])->name('focal.students');
    Route::get('/students/{id}', [FocalPersonController::class, 'showStudent'])->name('focal.students.show');
    Route::get('/students/{id}/edit', [FocalPersonController::class, 'editStudent'])->name('focal.students.edit');
    Route::post('/students/{id}/update', [FocalPersonController::class, 'updateStudent'])->name('focal.students.update');
    Route::post('/students/{id}/delete', [FocalPersonController::class, 'deleteStudent'])->name('focal.students.delete');
    Route::get('/faculty-members/register', [FocalPersonController::class, 'registerSupervisor'])->name('focal.faculty-members.register');
    Route::post('/faculty-members/store', [FocalPersonController::class, 'storeSupervisor'])->name('focal.faculty-members.store');
    Route::get('/faculty-members', [FocalPersonController::class, 'allSupervisors'])->name('focal.faculty-members');
    Route::get('/groups', [FocalPersonController::class, 'groups'])->name('focal.groups');
    Route::post('/groups/{groupId}/delete',[FocalPersonController::class, 'deleteGroup'])->name('focal.groups.delete');
    Route::post('/groups/{groupId}/assign-faculty', [FocalPersonController::class, 'assignSupervisor'])->name('focal.groups.assign');
    Route::post('/proposals/{proposalId}/review', [FocalPersonController::class, 'reviewProposal'])->name('focal.proposals.review');
    Route::get('/settings', [FocalPersonController::class, 'settings'])->name('focal.settings');
    Route::post('/settings', [FocalPersonController::class, 'updateSettings'])->name('focal.settings.update');

    // Late Upload Privilege
    Route::post('/phase-schedules/{id}/allow-late-upload', [App\Http\Controllers\FocalPerson\PhaseScheduleController::class, 'allowLateUpload'])->name('focal.phase-schedules.allow-late-upload');

    // Late Upload Privilege
    Route::post('/phase-schedules/{id}/allow-late-upload', [App\Http\Controllers\FocalPerson\PhaseScheduleController::class, 'allowLateUpload'])->name('focal.phase-schedules.allow-late-upload');

    // Department & Session Management
    Route::get('/departments-sessions', [App\Http\Controllers\FocalPerson\DepartmentSessionController::class, 'index'])->name('focal.departments-sessions');
    Route::post('/departments-sessions/department/store', [App\Http\Controllers\FocalPerson\DepartmentSessionController::class, 'storeDepartment'])->name('focal.departments-sessions.department.store');
    Route::post('/departments-sessions/department/{id}/toggle', [App\Http\Controllers\FocalPerson\DepartmentSessionController::class, 'toggleDepartmentStatus'])->name('focal.departments-sessions.department.toggle');
    Route::post('/departments-sessions/department/{id}/delete', [App\Http\Controllers\FocalPerson\DepartmentSessionController::class, 'deleteDepartment'])->name('focal.departments-sessions.department.delete');
    Route::post('/departments-sessions/session/store', [App\Http\Controllers\FocalPerson\DepartmentSessionController::class, 'storeSession'])->name('focal.departments-sessions.session.store');
    Route::post('/departments-sessions/session/{id}/toggle', [App\Http\Controllers\FocalPerson\DepartmentSessionController::class, 'toggleSessionStatus'])->name('focal.departments-sessions.session.toggle');
    Route::post('/departments-sessions/session/{id}/delete', [App\Http\Controllers\FocalPerson\DepartmentSessionController::class, 'deleteSession'])->name('focal.departments-sessions.session.delete');

    // Defence Panel Routes
    Route::get('/defence/panels', [App\Http\Controllers\FocalPerson\DefencePanelController::class, 'index'])->name('focal.defence.panels');
    Route::post('/defence/panels', [App\Http\Controllers\FocalPerson\DefencePanelController::class, 'store'])->name('focal.defence.panels.store');
    Route::post('/defence/panels/{id}/delete', [App\Http\Controllers\FocalPerson\DefencePanelController::class, 'destroy'])->name('focal.defence.panels.delete');
    Route::post('/defence/panels/{id}/toggle', [App\Http\Controllers\FocalPerson\DefencePanelController::class, 'toggleStatus'])->name('focal.defence.panels.toggle');

    // Defence Schedule Routes
    Route::get('/defence/schedules', [App\Http\Controllers\FocalPerson\DefenceScheduleController::class, 'index'])->name('focal.defence.schedules');
    Route::post('/defence/schedules', [App\Http\Controllers\FocalPerson\DefenceScheduleController::class, 'store'])->name('focal.defence.schedules.store');
    Route::post('/defence/schedules/{id}/delete', [App\Http\Controllers\FocalPerson\DefenceScheduleController::class, 'destroy'])->name('focal.defence.schedules.delete');
    Route::post('/defence/schedules/{id}/complete', [App\Http\Controllers\FocalPerson\DefenceScheduleController::class, 'markCompleted'])->name('focal.defence.schedules.complete');
    Route::get('/defence/results', [App\Http\Controllers\FocalPerson\DefenceScheduleController::class, 'results'])->name('focal.defence.results');
    Route::post('/defence/results/{id}/update', [App\Http\Controllers\FocalPerson\DefenceScheduleController::class, 'updateResult'])->name('focal.defence.results.update');
    Route::post('/defence/results/{id}/reattempt', [App\Http\Controllers\FocalPerson\DefenceScheduleController::class, 'allowReAttempt'])->name('focal.defence.results.reattempt');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/notifications', function() { return view('student.notifications'); })->name('student.notifications');
    Route::get('/my-group', [StudentController::class, 'myGroup'])->name('student.my-group');
    Route::get('/proposal', [StudentController::class, 'proposal'])->name('student.proposal');
    Route::get('/phase-documents', [StudentController::class, 'phaseDocuments'])->name('student.phase-documents');
    Route::get('/my-progress', [StudentController::class, 'myProgress'])->name('student.my-progress');
    Route::get('/meetings', [StudentController::class, 'meetings'])->name('student.meetings');
    Route::get('/assistant', [StudentController::class, 'assistant'])->name('student.assistant');
    Route::post('/assistant', [StudentController::class, 'assistantChat'])->name('student.assistant.chat');
    Route::post('/assistant/clear', [StudentController::class, 'assistantClear'])->name('student.assistant.clear');
    Route::post('/phase-documents/{schedule}', [StudentController::class, 'uploadPhaseDocument'])->name('student.phase-documents.upload');
    Route::post('/group/create', [StudentController::class, 'createGroup'])->name('student.group.create');
    Route::post('/group/join/{groupId}', [StudentController::class, 'requestJoin'])->name('student.group.join');
    Route::post('/group/request/{requestId}', [StudentController::class, 'handleRequest'])->name('student.group.request');
    Route::post('/group/request/cancel/{requestId}', [StudentController::class, 'cancelRequest'])->name('student.request.cancel');
    Route::post('/group/delete/{groupId}',[StudentController::class,'deleteGroup'])->name('student.group.delete');
    Route::post('/group/leave/{groupId}',[StudentController::class,'leaveGroup'])->name('student.group.leave');
    Route::post('/group/lock/{groupId}', [StudentController::class, 'lockGroup'])->name('student.group.lock');
    Route::post('/group/proposal', [StudentController::class, 'submitProposal'])->name('student.proposal.submit');
    Route::get('/proposal/document/{proposal}', [StudentController::class, 'downloadProposalDocument'])->name('student.proposal.document');
});

// Supervisor Routes
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\FacultyController::class, 'dashboard'])->name('faculty.dashboard');
    Route::get('/notifications', function() { return view('faculty.notifications'); })->name('faculty.notifications');
    Route::get('/attendance', [App\Http\Controllers\FacultyController::class, 'attendance'])->name('faculty.attendance');
    Route::get('/meetings', [App\Http\Controllers\FacultyController::class, 'meetings'])->name('faculty.meetings');
    Route::post('/attendance', [App\Http\Controllers\FacultyController::class, 'markAttendance'])->name('faculty.attendance.store');
    Route::get('/my-panels', [App\Http\Controllers\FacultyController::class, 'myPanels'])->name('faculty.my-panels');
    Route::get('/defence', [App\Http\Controllers\Faculty\DefenceController::class, 'index'])->name('faculty.defence.index');
    Route::get('/defence/{scheduleId}/evaluate', [App\Http\Controllers\Faculty\DefenceController::class, 'evaluate'])->name('faculty.defence.evaluate');
    Route::post('/defence/{scheduleId}/submit', [App\Http\Controllers\Faculty\DefenceController::class, 'submitEvaluation'])->name('faculty.defence.submit');
});
// Faculty Routes
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->group(function () {
    Route::get('/phase-evaluation', [App\Http\Controllers\Faculty\PhaseEvaluationController::class, 'index'])->name('faculty.phase-evaluation');
    Route::post('/phase-evaluation', [App\Http\Controllers\Faculty\PhaseEvaluationController::class, 'store'])->name('faculty.phase-evaluation.store');
    Route::get('/phase-documents', [App\Http\Controllers\Faculty\PhaseDocumentController::class, 'index'])->name('faculty.phase-documents.index');
    Route::get('/phase-documents/{document}/download', [App\Http\Controllers\Faculty\PhaseDocumentController::class, 'download'])->name('faculty.phase-documents.download');
    Route::get('/phase-documents', [App\Http\Controllers\Faculty\PhaseDocumentController::class, 'index'])->name('faculty.phase-documents.index');
    Route::get('/phase-documents/{document}/download', [App\Http\Controllers\Faculty\PhaseDocumentController::class, 'download'])->name('faculty.phase-documents.download');
});

// Focal Person - Reports
Route::middleware(['auth', 'role:focal_person'])->prefix('focal')->group(function () {
    Route::get('/reports/students', function() { return view('focal.reports.students'); })->name('focal.reports.students');
    Route::get('/reports/marks',    function() { return view('focal.reports.marks'); })->name('focal.reports.marks');
});

// Focal Person - Phase Management Routes
Route::middleware(['auth', 'role:focal_person'])->prefix('focal')->group(function () {
    // Phases
    Route::get('/phases', [App\Http\Controllers\FocalPerson\PhaseController::class, 'index'])->name('focal.phases.index');
    Route::post('/phases', [App\Http\Controllers\FocalPerson\PhaseController::class, 'store'])->name('focal.phases.store');
    Route::put('/phases/{phase}', [App\Http\Controllers\FocalPerson\PhaseController::class, 'update'])->name('focal.phases.update');
    Route::delete('/phases/{phase}', [App\Http\Controllers\FocalPerson\PhaseController::class, 'destroy'])->name('focal.phases.destroy');

    // Phase Schedules
    Route::get('/phase-schedules', [App\Http\Controllers\FocalPerson\PhaseScheduleController::class, 'index'])->name('focal.phase-schedules.index');
    Route::post('/phase-schedules', [App\Http\Controllers\FocalPerson\PhaseScheduleController::class, 'store'])->name('focal.phase-schedules.store');
    Route::put('/phase-schedules/{schedule}', [App\Http\Controllers\FocalPerson\PhaseScheduleController::class, 'update'])->name('focal.phase-schedules.update');
    Route::delete('/phase-schedules/{schedule}', [App\Http\Controllers\FocalPerson\PhaseScheduleController::class, 'destroy'])->name('focal.phase-schedules.destroy');
    Route::get('/phase-results', [App\Http\Controllers\FocalPerson\PhaseResultController::class, 'index'])->name('focal.phase-results.index');
    Route::post('/phase-results/{schedule}/assign-supervisor', [App\Http\Controllers\FocalPerson\PhaseResultController::class, 'assignSupervisor'])->name('focal.phase-results.assign-supervisor');
    Route::post('/phase-results/{schedule}/retry', [App\Http\Controllers\FocalPerson\PhaseResultController::class, 'retry'])->name('focal.phase-results.retry');

    // Marks
    Route::get('/marks', [App\Http\Controllers\FocalPerson\MarksController::class, 'index'])->name('focal.marks.index');
    Route::get('/analytics', function() { return view('focal.analytics'); })->name('focal.analytics');
    Route::put('/marks/{mark}', [App\Http\Controllers\FocalPerson\MarksController::class, 'update'])->name('focal.marks.update');
    Route::get('/phase-documents', [App\Http\Controllers\FocalPerson\PhaseDocumentController::class, 'index'])->name('focal.phase-documents.index');
    Route::get('/phase-documents/{document}/download', [App\Http\Controllers\FocalPerson\PhaseDocumentController::class, 'download'])->name('focal.phase-documents.download');
    Route::get('/group-tracking', [App\Http\Controllers\FocalPerson\GroupTrackingController::class, 'index'])->name('focal.group.tracking');
    Route::get('/group-tracking/{groupId}', [App\Http\Controllers\FocalPerson\GroupTrackingController::class, 'show'])->name('focal.group.tracking.show');
});


// Group Chat routes (shared between student & faculty)
Route::middleware(['auth'])->prefix('chat')->group(function () {
    Route::get('/{groupId}', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/{groupId}/messages', [App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('/{groupId}/send', [App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
    Route::post('/{groupId}/clear-for-me', [App\Http\Controllers\ChatController::class, 'clearForMe'])->name('chat.clear-me');
    Route::post('/{groupId}/clear-for-everyone', [App\Http\Controllers\ChatController::class, 'clearForEveryone'])->name('chat.clear-everyone');
    Route::get('/unread/badge', [App\Http\Controllers\ChatController::class, 'unreadBadge'])->name('chat.unread-badge');
    Route::get('/unread/by-group', [App\Http\Controllers\ChatController::class, 'unreadByGroup'])->name('chat.unread-by-group');
    Route::post('/message/{messageId}/delete-for-me', [App\Http\Controllers\ChatController::class, 'deleteMessageForMe'])->name('chat.message.delete-me');
    Route::post('/message/{messageId}/delete-for-everyone', [App\Http\Controllers\ChatController::class, 'deleteMessageForEveryone'])->name('chat.message.delete-everyone');
});
