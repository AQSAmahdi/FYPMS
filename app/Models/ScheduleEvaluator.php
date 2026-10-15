<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleEvaluator extends Model
{
    protected $fillable = ['phase_schedule_id', 'evaluator_id', 'submitted_at'];

    protected $casts = ['submitted_at' => 'datetime'];

    public function schedule()
    {
        return $this->belongsTo(PhaseSchedule::class, 'phase_schedule_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}