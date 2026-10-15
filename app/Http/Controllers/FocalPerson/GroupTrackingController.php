<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use App\Models\FypGroup;

class GroupTrackingController extends Controller
{
    public function index()
    {
        $groups = FypGroup::with(['members.user','supervisor'])->get();
        return view('focal.group-tracking', compact('groups'));
    }

    public function show($groupId)
    {
        $group = FypGroup::with([
            'members.user',
            'supervisor',
            'defenceSchedule',
            
        ])->findOrFail($groupId);
        return view('focal.group-tracking-detail', compact('group'));
    }
}
