<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupJoinRequest extends Model
{
    protected $fillable = ['fyp_group_id','user_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'fyp_group_id');
    }
}
