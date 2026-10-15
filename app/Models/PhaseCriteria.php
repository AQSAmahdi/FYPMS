<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhaseCriteria extends Model
{
    protected $table = 'phase_criteria';
    protected $fillable = ['phase_id', 'name', 'max_marks', 'order'];

    public function phase()
    {
        return $this->belongsTo(FypPhase::class, 'phase_id');
    }
}
