<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DefencePanel extends Model {
    protected $fillable = ['name','description','created_by','status'];

    public function members() {
        return $this->hasMany(PanelMember::class);
    }
    public function facultys() {
        return $this->belongsToMany(User::class, 'panel_members', 'defence_panel_id', 'user_id');
    }
    public function schedules() {
        return $this->hasMany(DefenceSchedule::class);
    }
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
