<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MessageDeletion extends Model
{
    protected $fillable = ['message_id', 'user_id'];

    public function message()
    {
        return $this->belongsTo(GroupMessage::class, 'message_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
