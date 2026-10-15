<?php
namespace App\Http\Controllers\Faculty;
use App\Http\Controllers\Controller;
use App\Models\PhaseDocument;
use App\Models\FypGroup;
use App\Models\FypPhase;
use Illuminate\Support\Facades\Storage;

class PhaseDocumentController extends Controller
{
    public function index()
    {
        $supervisor = auth()->user();

        $groupIds = FypGroup::where('supervisor_id', $supervisor->id)->pluck('id');

        $documents = PhaseDocument::with(['phase', 'group', 'uploader'])
            ->whereIn('group_id', $groupIds)
            ->orderBy('updated_at', 'desc')
            ->get();

        $phases = FypPhase::where('requires_upload', true)->orderBy('name')->get();
        $groups = FypGroup::where('supervisor_id', $supervisor->id)->orderBy('group_code')->get();

        return view('faculty.phase-documents', compact('documents', 'phases', 'groups'));
    }

    public function download(PhaseDocument $document)
    {
        $supervisor = auth()->user();
        $group = FypGroup::find($document->group_id);

        if (!$group || $group->supervisor_id !== $supervisor->id) {
            abort(403, 'Access denied.');
        }

        if (!Storage::disk('local')->exists($document->stored_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('local')->download($document->stored_path, $document->original_filename);
    }
}
