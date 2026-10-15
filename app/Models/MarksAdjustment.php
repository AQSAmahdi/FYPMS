<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarksAdjustment extends Model
{
    protected $fillable = [
        'phase_id', 'student_id',
        'total_converted_marks', 'adjusted_marks',
        'reason', 'adjusted_by', 'adjusted_at'
    ];

    protected $casts = [
        'adjusted_at' => 'datetime'
    ];

    public function phase()
    {
        return $this->belongsTo(FypPhase::class, 'phase_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}