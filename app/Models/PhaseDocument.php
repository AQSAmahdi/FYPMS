<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PhaseDocument extends Model
{
    protected $fillable = [
        'phase_id', 'group_id', 'phase_schedule_id', 'uploaded_by',
        'original_filename', 'stored_path', 'file_type', 'file_size',
        'is_late', 'uploaded_at',
    ];
    public function phase()
    {
        return $this->belongsTo(FypPhase::class, 'phase_id');
    }
    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'group_id');
    }
    public function schedule()
    {
        return $this->belongsTo(PhaseSchedule::class, 'phase_schedule_id');
    }
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
