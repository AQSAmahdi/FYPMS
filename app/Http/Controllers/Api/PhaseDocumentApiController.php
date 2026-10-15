<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhaseSchedule;
use App\Models\GroupMember;
use App\Models\PhaseDocument;
use App\Models\User;
use App\Helpers\NotificationHelper;

class PhaseDocumentApiController extends Controller
{
    public function upload(Request $request, $scheduleId)
    {
        $student = $request->user();

        if ($student->role !== 'student') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Only students can upload documents.'], 403);
        }

        $schedule = PhaseSchedule::with('phase')->find($scheduleId);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.'], 404);
        }

        $membership = GroupMember::where('user_id', $student->id)->where('status', 'accepted')->first();
        if (!$membership || $membership->fyp_group_id != $schedule->group_id) {
            return response()->json(['success' => false, 'message' => 'You are not a member of this group.'], 403);
        }

        $phase = $schedule->phase;
        if (!$phase || !$phase->requires_upload) {
            return response()->json(['success' => false, 'message' => 'This phase does not require a document upload.'], 422);
        }

        // Deadline check
        $deadline = $schedule->upload_deadline
            ? \Carbon\Carbon::parse($schedule->upload_deadline)->endOfDay()
            : \Carbon\Carbon::parse($schedule->schedule_date)->subDay()->endOfDay();
        $isLate = now()->gt($deadline);

        if ($isLate && !$schedule->late_upload_allowed) {
            return response()->json([
                'success' => false,
                'message' => 'Upload deadline has passed (' . $deadline->format('d M Y') . '). Please request your Focal Person to grant special upload permission.'
            ], 422);
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

        // Notify focal persons
        $group = $schedule->group;
        $focalPersons = User::where('role', 'focal_person')->get();
        foreach ($focalPersons as $fp) {
            NotificationHelper::send(
                $fp->id,
                'Phase Document Uploaded',
                'Group "' . ($group->group_code ?? '') . '" uploaded a document for "' . $phase->name . '".',
                'info',
                '/focal/phase-documents'
            );
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Document uploaded successfully.' . ($isLate ? ' (Late upload)' : ''),
            'file_name' => $file->getClientOriginalName(),
            'is_late'   => $isLate,
        ], 201);
    }

    public function myDocuments(Request $request)
    {
        $student = $request->user();

        if ($student->role !== 'student') {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $membership = GroupMember::where('user_id', $student->id)->where('status', 'accepted')->first();
        if (!$membership) {
            return response()->json(['success' => true, 'documents' => []]);
        }

        $schedules = PhaseSchedule::with(['phase', 'document'])
            ->where('group_id', $membership->fyp_group_id)
            ->orderBy('schedule_date')
            ->get()
            ->map(function ($s) {
                $doc = PhaseDocument::where('phase_id', $s->phase_id)
                    ->where('group_id', $s->group_id)->first();
                return [
                    'schedule_id'     => $s->id,
                    'phase_name'      => $s->phase->name ?? '',
                    'schedule_date'   => $s->schedule_date,
                    'upload_deadline' => $s->upload_deadline,
                    'document_uploaded' => $doc ? true : false,
                    'original_filename' => $doc->original_filename ?? null,
                    'uploaded_at'       => $doc->uploaded_at ?? null,
                    'is_late'           => $doc->is_late ?? false,
                ];
            });

        return response()->json(['success' => true, 'documents' => $schedules]);
    }
}
