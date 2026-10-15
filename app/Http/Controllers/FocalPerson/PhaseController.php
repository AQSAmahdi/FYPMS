<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use App\Models\FypPhase;
use App\Models\PhaseCriteria;
use App\Models\DefencePanel;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    public function index()
    {
        $phases  = FypPhase::with('panel', 'criteria')->orderBy('created_at', 'desc')->get();
        $panels  = DefencePanel::all();
        return view('focal.phases.index', compact('phases', 'panels'));
    }

    /**
     * Validate and save criteria rows for a phase.
     * If criteria_name[] is present, their max_marks must sum to total_marks.
     */
    private function saveCriteria(Request $request, FypPhase $phase)
    {
        $names = $request->input('criteria_name', []);
        $marks = $request->input('criteria_marks', []);

        // Drop empty rows
        $rows = [];
        foreach ($names as $i => $name) {
            $name = trim($name ?? '');
            $mark = $marks[$i] ?? null;
            if ($name === '' || $mark === null || $mark === '') continue;
            $rows[] = ['name' => $name, 'max_marks' => (int) $mark];
        }

        // Always clear old criteria first (handles edit + clearing on update)
        PhaseCriteria::where('phase_id', $phase->id)->delete();

        if (empty($rows)) {
            return; // No criteria breakdown — phase uses single "Overall" marking
        }

        $sum = array_sum(array_column($rows, 'max_marks'));
        if ($sum != $phase->total_marks) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'criteria_name' => "Criteria marks must add up to the phase's Total Marks ({$phase->total_marks}). Currently they add up to {$sum}.",
            ]);
        }

        foreach ($rows as $order => $row) {
            PhaseCriteria::create([
                'phase_id'  => $phase->id,
                'name'      => $row['name'],
                'max_marks' => $row['max_marks'],
                'order'     => $order + 1,
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'total_marks'      => 'required|numeric|min:1',
            'convert_to_marks' => 'required|numeric|min:1',
        ]);
        $phase = FypPhase::create(array_merge(
            $request->only('name', 'total_marks', 'convert_to_marks', 'description', 'upload_instructions', 'panel_id'),
            [
                'created_by'      => auth()->id(),
                'requires_upload' => $request->boolean('requires_upload'),
            ]
        ));

        $this->saveCriteria($request, $phase);

        return redirect()->route('focal.phases.index')->with('success', 'Phase created successfully!');
    }

    public function update(Request $request, FypPhase $phase)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'total_marks'      => 'required|numeric|min:1',
            'convert_to_marks' => 'required|numeric|min:1',
        ]);
        $phase->update(array_merge(
            $request->only('name', 'total_marks', 'convert_to_marks', 'description', 'upload_instructions', 'panel_id'),
            ['requires_upload' => $request->boolean('requires_upload')]
        ));

        $this->saveCriteria($request, $phase);

        return redirect()->route('focal.phases.index')->with('success', 'Phase updated successfully!');
    }

    public function destroy(FypPhase $phase)
    {
        $phase->delete();
        return redirect()->route('focal.phases.index')->with('success', 'Phase deleted!');
    }
}
