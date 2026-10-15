<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DefenceSchedule extends Model {
    protected $fillable = ['fyp_group_id','defence_panel_id','assigned_teacher_id','defence_date','defence_time','room','status','result','result_remarks','failure_reason','attempt_number','created_by'];

    public function group() {
        return $this->belongsTo(FypGroup::class, 'fyp_group_id');
    }
    public function panel() {
        return $this->belongsTo(DefencePanel::class, 'defence_panel_id');
    }
    public function faculty() {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }
    public function criteria() {
        return $this->hasMany(EvaluationCriteria::class);
    }
    public function evaluations() {
        return $this->hasMany(Evaluation::class);
    }
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getTotalMarksAttribute() {
        return $this->criteria->sum('max_marks');
    }
    public function getObtainedMarksAttribute() {
        return $this->evaluations->sum('marks_obtained');
    }
}
