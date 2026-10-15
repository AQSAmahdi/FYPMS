<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class StudentProfile extends Model
{
    protected $fillable = [
        'user_id','student_id','reg_no','contact',
        'department_id','session_id','batch_id',
        'current_semester','shift','status'
    ];
 
    public function user()       { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function session()    { return $this->belongsTo(AcademicSession::class, 'session_id'); }
    public function batch()      { return $this->belongsTo(Batch::class); }
}