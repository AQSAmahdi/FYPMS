<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attendance extends Model
{
    protected $table = 'fyp_attendances';
    protected $fillable = [
        'group_id', 'meeting_id', 'student_id', 'marked_by',
        'meeting_date', 'status', 'remarks'
    ];
    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'group_id');
    }
    public function meeting()
    {
        return $this->belongsTo(MeetingSchedule::class, 'meeting_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
