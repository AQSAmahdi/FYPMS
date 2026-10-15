<?php
namespace App\Http\Controllers\Faculty;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefenceSchedule;
use App\Models\EvaluationCriteria;
use App\Models\Evaluation;

class DefenceController extends Controller {

    public function index() {
        $schedules = DefenceSchedule::with([
            'group.members.user',
            'group.department',
            'panel',
            'criteria',
            'evaluations'
        ])
        ->where('assigned_teacher_id', auth()->id())
        ->get();
        return view('faculty.defence.index', compact('schedules'));
    }

    public function evaluate($scheduleId) {
        $schedule = DefenceSchedule::with([
            'group.members.user',
            'group.department',
            'criteria.evaluations',
            'panel'
        ])
        ->where('assigned_teacher_id', auth()->id())
        ->findOrFail($scheduleId);

        $existingEvals = Evaluation::where('defence_schedule_id', $scheduleId)
                            ->where('evaluated_by', auth()->id())
                            ->pluck('marks_obtained','criteria_id');

        return view('faculty.defence.evaluate', compact('schedule','existingEvals'));
    }

    public function submitEvaluation(Request $request, $scheduleId) {
        $schedule = DefenceSchedule::where('assigned_teacher_id', auth()->id())
                        ->findOrFail($scheduleId);

        $request->validate([
            'marks'   => 'required|array',
            'marks.*' => 'required|integer|min:0',
        ]);

        foreach($request->marks as $criteriaId => $marks) {
            $criteria = EvaluationCriteria::where('defence_schedule_id', $scheduleId)
                            ->findOrFail($criteriaId);
            if($marks > $criteria->max_marks) {
                return back()->withErrors(['marks.'.$criteriaId => 'Marks cannot exceed '.$criteria->max_marks]);
            }
            Evaluation::updateOrCreate(
                [
                    'defence_schedule_id' => $scheduleId,
                    'evaluated_by'        => auth()->id(),
                    'criteria_id'         => $criteriaId,
                ],
                ['marks_obtained' => $marks]
            );
        }

        $schedule->status = 'completed';
        $schedule->save();

        return redirect()->route('faculty.defence.index')
                ->with('success','Evaluation submitted successfully!');
    }
}
