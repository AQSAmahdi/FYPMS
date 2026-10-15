<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChatRead extends Model
{
    protected $fillable = ['user_id', 'group_id', 'last_read_at'];

    protected $casts = [
        'last_read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'group_id');
    }
}
