<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model {
    protected $fillable = ['defence_schedule_id','criteria_name','max_marks','order'];

    public function schedule() {
        return $this->belongsTo(DefenceSchedule::class, 'defence_schedule_id');
    }
    public function evaluations() {
        return $this->hasMany(Evaluation::class, 'criteria_id');
    }
}
