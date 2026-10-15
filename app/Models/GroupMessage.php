<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $table = 'fyp_group_messages';

    protected $fillable = [
        'group_id', 'sender_id', 'message', 'deleted_for_everyone'
    ];

    protected $casts = [
        'deleted_for_everyone' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'group_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
