<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MeetingSchedule extends Model
{
    protected $table = 'meeting_schedules';
    protected $fillable = [
        'group_id', 'scheduled_by', 'meeting_date', 'meeting_time', 'agenda', 'status'
    ];
    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'group_id');
    }
    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'meeting_id');
    }
}
