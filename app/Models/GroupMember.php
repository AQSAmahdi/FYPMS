<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $fillable = ['fyp_group_id','user_id','role','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'fyp_group_id');
    }
}
