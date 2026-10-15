<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FypPhase extends Model
{
    protected $fillable = [
        'name', 'description', 'total_marks', 'convert_to_marks',
        'session_id', 'department_id', 'shift', 'is_active', 'order', 'created_by',
        'requires_upload', 'upload_instructions', 'panel_id'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(PhaseSchedule::class, 'phase_id');
    }
    public function documents()
    {
        return $this->hasMany(PhaseDocument::class, 'phase_id');
    }
    public function panel()
    {
        return $this->belongsTo(\App\Models\DefencePanel::class, 'panel_id');
    }

    public function criteria()
    {
        return $this->hasMany(PhaseCriteria::class, 'phase_id')->orderBy('order');
    }

    // Conversion formula
    public function convertMarks($obtained)
    {
        if ($this->total_marks == 0) return 0;
        return round(($obtained * $this->convert_to_marks) / $this->total_marks, 2);
    }
}