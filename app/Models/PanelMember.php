<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PanelMember extends Model {
    protected $fillable = ['defence_panel_id','user_id'];

    public function panel() {
        return $this->belongsTo(DefencePanel::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
