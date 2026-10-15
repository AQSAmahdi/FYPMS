<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhaseSchedule extends Model
{
    protected $fillable = [
        'phase_id', 'group_id', 'panel_id',
        'schedule_date', 'schedule_time', 'room', 'status',
        'attempt_number', 'result', 'result_remarks', 'average_marks',
        'upload_deadline', 'late_upload_allowed', 'late_upload_allowed_by', 'late_upload_allowed_at'
    ];

    public function phase()
    {
        return $this->belongsTo(FypPhase::class, 'phase_id');
    }

    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'group_id');
    }

    public function panel()
    {
        return $this->belongsTo(DefencePanel::class, 'panel_id');
    }

    public function evaluators()
    {
        return $this->hasMany(ScheduleEvaluator::class, 'phase_schedule_id');
    }

    public function marks()
    {
        return $this->hasMany(StudentPhaseMark::class, 'phase_schedule_id');
    }
}