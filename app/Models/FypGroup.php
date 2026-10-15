<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FypGroup extends Model
{
    protected $fillable = [
        'group_code','project_title','project_description',
        'department_id','session_id','shift','created_by',
        'supervisor_id','status','max_members'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class)->where('status', 'accepted');
    }

    public function allMembers()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function joinRequests()
    {
        return $this->hasMany(GroupJoinRequest::class)->where('status', 'pending');
    }

    public function proposal()
    {
        return $this->hasOne(Proposal::class);
    }
    public function defenceSchedule()
    {
        return $this->hasOne(\App\Models\DefenceSchedule::class, 'fyp_group_id');
    }
    public function documents()
    {
        return $this->hasMany(PhaseDocument::class, 'group_id');
    }

    public static function generateGroupCode($deptCode, $sessionName, $shift)
    {
        // BSCSF22MG01
        $session = substr(str_replace('-', '', explode('-', $sessionName)[0]), 2); // 22
        $shiftCode = $shift == 'Morning' ? 'M' : 'E';
        $prefix = 'BS'.$deptCode.'F'.$session.$shiftCode.'G';

        $last = self::where('group_code', 'like', $prefix.'%')->count();
        $number = str_pad($last + 1, 2, '0', STR_PAD_LEFT);

        return $prefix.$number;
    }
}
