<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Batch extends Model
{
    protected $fillable = ['name','semester_type','session_id','is_active'];
 
    public function session() { return $this->belongsTo(AcademicSession::class, 'session_id'); }
}