<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FypPhase;
use App\Models\StudentPhaseMark;
use App\Models\MarksAdjustment;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'phase_id' => 'required|exists:fyp_phases,id'
        ]);

        $phase = FypPhase::find($request->phase_id);

        $marks = StudentPhaseMark::with(['student.studentProfile.department', 'student.studentProfile.session'])
            ->where('phase_id', $phase->id)
            ->get()
            ->groupBy('student_id')
            ->map(function ($studentMarks) use ($phase) {
                $student  = $studentMarks->first()->student;
                $profile  = $student?->studentProfile;
                $total    = $studentMarks->sum('converted_marks');

                $adjustment = MarksAdjustment::where('phase_id', $phase->id)
                    ->where('student_id', $student->id)
                    ->first();

                return [
                    'student_id'       => $student->id,
                    'student_name'     => $student->name,
                    'student_roll'     => $profile?->student_id,
                    'department'       => $profile?->department?->name,
                    'session'          => $profile?->session?->name,
                    'shift'            => $profile?->shift,
                    'total_converted'  => $total,
                    'adjusted_marks'   => $adjustment?->adjusted_marks ?? $total,
                    'is_adjusted'      => $adjustment ? true : false,
                ];
            })->values();

        return response()->json([
            'success' => true,
            'phase'   => [
                'name'             => $phase->name,
                'total_marks'      => $phase->total_marks,
                'convert_to_marks' => $phase->convert_to_marks,
            ],
            'data' => $marks
        ]);
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'phase_id'       => 'required|exists:fyp_phases,id',
            'student_id'     => 'required|exists:users,id',
            'adjusted_marks' => 'required|numeric|min:0',
            'reason'         => 'nullable|string',
        ]);

        $totalConverted = StudentPhaseMark::where('phase_id', $request->phase_id)
            ->where('student_id', $request->student_id)
            ->sum('converted_marks');

        $adjustment = MarksAdjustment::updateOrCreate(
            [
                'phase_id'   => $request->phase_id,
                'student_id' => $request->student_id,
            ],
            [
                'total_converted_marks' => $totalConverted,
                'adjusted_marks'        => $request->adjusted_marks,
                'reason'                => $request->reason,
                'adjusted_by'           => auth()->id(),
                'adjusted_at'           => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Marks adjusted successfully!',
            'data'    => $adjustment
        ]);
    }
}