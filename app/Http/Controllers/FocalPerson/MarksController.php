<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use App\Models\StudentPhaseMark;
use App\Models\FypPhase;
use App\Models\Department;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    public function index()
    {
        $query = StudentPhaseMark::with(['phase', 'student', 'evaluator']);

        if(request('phase_id'))      $query->where('phase_id', request('phase_id'));
        if(request('session_id'))    $query->whereHas('student.studentProfile', fn($q) => $q->where('session_id', request('session_id')));
        if(request('department_id')) $query->whereHas('student.studentProfile', fn($q) => $q->where('department_id', request('department_id')));
        if(request('status') === 'adjusted') $query->where('is_adjusted', true);

        $marks       = $query->get();
        $phases      = FypPhase::all();
        $departments = Department::all();
        $sessions    = AcademicSession::all();

        $stats = [
            'total'     => $marks->count(),
            'submitted' => $marks->whereNotNull('marks_obtained')->count(),
            'pending'   => $marks->whereNull('marks_obtained')->count(),
            'adjusted'  => $marks->where('is_adjusted', true)->count(),
        ];

        return view('focal.marks.index', compact('marks', 'phases', 'departments', 'sessions', 'stats'));
    }

    public function update(Request $request, StudentPhaseMark $mark)
    {
        $request->validate(['obtained_marks' => 'required|numeric|min:0']);

        $mark->load('phase');
        $totalMarks    = $mark->max_marks ?? ($mark->phase->total_marks ?? 0);
        $convertTo     = $mark->phase->convert_to_marks ?? 0;
        $marksObtained = min((float)$request->obtained_marks, (float)$totalMarks);
        $converted     = $totalMarks > 0
            ? round(($marksObtained * $convertTo) / $totalMarks, 2)
            : 0;

        $mark->update([
            'marks_obtained'    => $marksObtained,
            'converted_marks'   => $converted,
            'is_adjusted'       => true,
            'adjustment_reason' => $request->adjustment_reason,
        ]);
        return redirect()->route('focal.marks.index')->with('success', 'Marks adjusted!');
    }
}
