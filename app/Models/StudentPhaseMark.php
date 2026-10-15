<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPhaseMark extends Model
{
    protected $fillable = [
        'phase_id', 'phase_schedule_id', 'student_id',
        'evaluator_id', 'criteria_name', 'max_marks',
        'marks_obtained', 'converted_marks'
    ];

    public function phase()
    {
        return $this->belongsTo(FypPhase::class, 'phase_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function schedule()
    {
        return $this->belongsTo(PhaseSchedule::class, 'phase_schedule_id');
    }
}