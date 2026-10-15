<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model {
    protected $fillable = ['defence_schedule_id','evaluated_by','criteria_id','marks_obtained'];

    public function schedule() {
        return $this->belongsTo(DefenceSchedule::class, 'defence_schedule_id');
    }
    public function evaluator() {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
    public function criteria() {
        return $this->belongsTo(EvaluationCriteria::class, 'criteria_id');
    }
}
