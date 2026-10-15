<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'fyp_group_id','title','description',
        'technology_stack','file_path','status','focal_remarks'
    ];

    public function group()
    {
        return $this->belongsTo(FypGroup::class, 'fyp_group_id');
    }
}
