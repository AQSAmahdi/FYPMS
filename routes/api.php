<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FocalPersonApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\FacultyApiController;

Route::post('/login', [AuthController::class, 'login']);

// Registration APIs (requires focal person token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/focal/register/student', [App\Http\Controllers\Api\AuthController::class, 'registerStudent']);
    Route::post('/focal/register/faculty', [App\Http\Controllers\Api\AuthController::class, 'registerFaculty']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Focal Person routes
    Route::prefix('focal')->group(function () {
        Route::get('/dashboard',   [FocalPersonApiController::class, 'dashboard']);
        Route::get('/students',    [FocalPersonApiController::class, 'students']);
        Route::get('/sessions',    [FocalPersonApiController::class, 'sessions']);
        Route::get('/departments', [FocalPersonApiController::class, 'departments']);
        Route::get('/phases-list', [FocalPersonApiController::class, 'phasesList']);
        Route::get('/groups-for-bulk',    [FocalPersonApiController::class, 'groupsForBulk']);
        Route::post('/bulk-assign-phase', [FocalPersonApiController::class, 'bulkAssignPhase']);
        Route::get('/analytics', [FocalPersonApiController::class, 'analytics']);
        Route::get('/notifications', [App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::get('/notifications/latest', [App\Http\Controllers\Api\NotificationController::class, 'latest']);
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markRead']);
        Route::post('/notifications/{id}/clear', [App\Http\Controllers\Api\NotificationController::class, 'clearOne']);
        Route::post('/notifications/read-all', [App\Http\Controllers\Api\NotificationController::class, 'markAllRead']);
        Route::post('/notifications/clear-read', [App\Http\Controllers\Api\NotificationController::class, 'clearRead']);
        Route::get('/group-tracking',      [FocalPersonApiController::class, 'groupTracking']);
        Route::get('/group-tracking/{id}', [FocalPersonApiController::class, 'groupTrackingDetail']);
        Route::get('/marks-report',[FocalPersonApiController::class, 'marksReport']);
        Route::get('/faculty-members', [FocalPersonApiController::class, 'facultyMembers']);
        Route::get('/groups',      [FocalPersonApiController::class, 'groups']);
        Route::get('/panels',      [FocalPersonApiController::class, 'panels']);
        Route::post('/panels',     [FocalPersonApiController::class, 'storePanel']);
        Route::get('/schedules',   [FocalPersonApiController::class, 'schedules']);
        Route::get('/results',     [FocalPersonApiController::class, 'results']);

        Route::get('/phases',    [App\Http\Controllers\Api\PhaseController::class, 'index']);
        Route::post('/phases',   [App\Http\Controllers\Api\PhaseController::class, 'store']);
        Route::put('/phases/{phase}',    [App\Http\Controllers\Api\PhaseController::class, 'update']);
        Route::delete('/phases/{phase}', [App\Http\Controllers\Api\PhaseController::class, 'destroy']);

        Route::get('/phase-schedules',    [App\Http\Controllers\Api\PhaseScheduleController::class, 'index']);
        Route::post('/phase-schedules',    [App\Http\Controllers\Api\PhaseScheduleController::class, 'store']);
        Route::delete('/phase-schedules/{schedule}',[App\Http\Controllers\Api\PhaseScheduleController::class, 'destroy']);

        Route::get('/marks',         [App\Http\Controllers\Api\MarksController::class, 'index']);
        Route::post('/marks/adjust', [App\Http\Controllers\Api\MarksController::class, 'adjust']);
    });

    // Student routes
    Route::prefix('student')->group(function () {
        Route::post('/phase-documents/{scheduleId}/upload', [App\Http\Controllers\Api\PhaseDocumentApiController::class, 'upload']);
        Route::get('/phase-documents', [App\Http\Controllers\Api\PhaseDocumentApiController::class, 'myDocuments']);
        Route::get('/dashboard', [StudentApiController::class, 'dashboard']);
        Route::get('/notifications', [App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::get('/notifications/latest', [App\Http\Controllers\Api\NotificationController::class, 'latest']);
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markRead']);
        Route::post('/notifications/{id}/clear', [App\Http\Controllers\Api\NotificationController::class, 'clearOne']);
        Route::post('/notifications/read-all', [App\Http\Controllers\Api\NotificationController::class, 'markAllRead']);
        Route::post('/notifications/clear-read', [App\Http\Controllers\Api\NotificationController::class, 'clearRead']);
        Route::get('/attendance', [App\Http\Controllers\Api\AttendanceController::class, 'myAttendance']);
        Route::get('/meetings', [App\Http\Controllers\Api\MeetingController::class, 'studentMeetings']);
    });

    // Supervisor routes
    Route::prefix('faculty')->group(function () {
        Route::get('/dashboard', [FacultyApiController::class, 'dashboard']);
        Route::get('/defence',   [FacultyApiController::class, 'defence']);
        Route::get('/notifications', [App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::get('/notifications/latest', [App\Http\Controllers\Api\NotificationController::class, 'latest']);
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markRead']);
        Route::post('/notifications/{id}/clear', [App\Http\Controllers\Api\NotificationController::class, 'clearOne']);
        Route::post('/notifications/clear-read', [App\Http\Controllers\Api\NotificationController::class, 'clearRead']);
        Route::post('/notifications/read-all', [App\Http\Controllers\Api\NotificationController::class, 'markAllRead']);
    });

    // Faculty routes
    Route::prefix('faculty')->group(function () {
        Route::get('/phase-schedules',    [App\Http\Controllers\Api\PhaseEvaluationController::class, 'mySchedules']);
        Route::get('/phase-evaluation/{schedule}', [App\Http\Controllers\Api\PhaseEvaluationController::class, 'show']);
        Route::post('/phase-evaluation/{schedule}',[App\Http\Controllers\Api\PhaseEvaluationController::class, 'store']);

        Route::get('/attendance/groups',      [App\Http\Controllers\Api\AttendanceController::class, 'myGroups']);
        Route::post('/attendance',            [App\Http\Controllers\Api\AttendanceController::class, 'store']);
        Route::get('/attendance/{groupId}',   [App\Http\Controllers\Api\AttendanceController::class, 'groupHistory']);
        Route::post('/meetings', [App\Http\Controllers\Api\MeetingController::class, 'store']);
        Route::get('/meetings/{groupId}', [App\Http\Controllers\Api\MeetingController::class, 'facultyMeetings']);
        Route::get('/meetings/{meetingId}/attendance', [App\Http\Controllers\Api\MeetingController::class, 'meetingAttendance']);
        Route::put('/meetings/{id}', [App\Http\Controllers\Api\MeetingController::class, 'update']);
        Route::delete('/meetings/{id}', [App\Http\Controllers\Api\MeetingController::class, 'destroy']);
    });

});
// This line intentionally left blank
