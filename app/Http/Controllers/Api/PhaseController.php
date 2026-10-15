<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FypPhase;
use App\Models\Session;
use App\Models\Department;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    public function index()
    {
        $phases = FypPhase::with(['session', 'department'])
            ->orderBy('order')
            ->get()
            ->map(function ($phase) {
                return [
                    'id'               => $phase->id,
                    'name'             => $phase->name,
                    'description'      => $phase->description,
                    'total_marks'      => $phase->total_marks,
                    'convert_to_marks' => $phase->convert_to_marks,
                    'session'          => $phase->session?->name,
                    'department'       => $phase->department?->name,
                    'shift'            => $phase->shift,
                    'is_active'        => $phase->is_active,
                    'order'            => $phase->order,
                ];
            });

        return response()->json(['success' => true, 'data' => $phases]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'total_marks'      => 'required|integer|min:1',
            'convert_to_marks' => 'required|integer|min:1',
            'order'            => 'required|integer|min:1',
        ]);

        $phase = FypPhase::create([
            'name'             => $request->name,
            'description'      => $request->description,
            'total_marks'      => $request->total_marks,
            'convert_to_marks' => $request->convert_to_marks,
            'session_id'       => $request->session_id,
            'department_id'    => $request->department_id,
            'shift'            => $request->shift,
            'order'            => $request->order,
            'is_active'        => true,
            'created_by'       => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Phase created successfully!',
            'data'    => $phase
        ], 201);
    }

    public function update(Request $request, FypPhase $phase)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'total_marks'      => 'required|integer|min:1',
            'convert_to_marks' => 'required|integer|min:1',
            'order'            => 'required|integer|min:1',
        ]);

        $phase->update([
            'name'             => $request->name,
            'description'      => $request->description,
            'total_marks'      => $request->total_marks,
            'convert_to_marks' => $request->convert_to_marks,
            'session_id'       => $request->session_id,
            'department_id'    => $request->department_id,
            'shift'            => $request->shift,
            'order'            => $request->order,
            'is_active'        => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Phase updated!',
            'data'    => $phase
        ]);
    }

    public function destroy(FypPhase $phase)
    {
        $phase->delete();
        return response()->json(['success' => true, 'message' => 'Phase deleted!']);
    }
}