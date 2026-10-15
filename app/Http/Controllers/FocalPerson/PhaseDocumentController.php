<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use App\Models\PhaseDocument;
use App\Models\PhaseSchedule;
use App\Models\FypPhase;
use App\Models\FypGroup;
use Illuminate\Support\Facades\Storage;

class PhaseDocumentController extends Controller
{
    public function index()
    {
        $documents = PhaseDocument::with(['phase', 'group', 'uploader'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Schedules where upload required, deadline passed, doc not yet uploaded
        $allUploadedKeys = PhaseDocument::selectRaw('CONCAT(phase_id,"_",group_id) as pkey')->pluck('pkey')->toArray();

        $pendingSchedules = PhaseSchedule::with(['phase', 'group'])
            ->whereHas('phase', fn($q) => $q->where('requires_upload', true))
            ->get()
            ->filter(function($s) use ($allUploadedKeys) {
                $key = $s->phase_id . '_' . $s->group_id;
                $alreadyUploaded = in_array($key, $allUploadedKeys);
                if ($alreadyUploaded) return false;

                $deadline = $s->upload_deadline
                    ? \Carbon\Carbon::parse($s->upload_deadline)->endOfDay()
                    : \Carbon\Carbon::parse($s->schedule_date)->endOfDay();

                return now()->gt($deadline); // only show if deadline passed
            });

        $phases = FypPhase::where('requires_upload', true)->orderBy('name')->get();
        $groups = FypGroup::orderBy('group_code')->get();
        return view('focal.phase-documents.index', compact('documents', 'phases', 'groups', 'pendingSchedules'));
    }

    public function download(PhaseDocument $document)
    {
        if (!Storage::disk('local')->exists($document->stored_path)) {
            abort(404, 'File not found.');
        }
        return Storage::disk('local')->download($document->stored_path, $document->original_filename);
    }
}
