<?php
namespace App\Http\Controllers\FocalPerson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefencePanel;
use App\Models\PanelMember;
use App\Models\User;

class DefencePanelController extends Controller {

    public function index() {
        $panels = DefencePanel::with('facultys')->get();
        $facultys = User::where('role','faculty')->orWhere('role','supervisor')->get();
        return view('focal.defence.panels', compact('panels','facultys'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'faculty_ids' => 'required|array|min:1',
            'faculty_ids.*' => 'exists:users,id',
        ]);
        $panel = DefencePanel::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => auth()->id(),
            'status' => 'active',
        ]);
        foreach($request->faculty_ids as $tid) {
            PanelMember::create(['defence_panel_id'=>$panel->id,'user_id'=>$tid]);
        }
        return back()->with('success','Panel "'.$panel->name.'" created successfully!');
    }

    public function destroy($id) {
        $panel = DefencePanel::findOrFail($id);
        $panel->delete();
        return back()->with('success','Panel deleted.');
    }

    public function toggleStatus($id) {
        $panel = DefencePanel::findOrFail($id);
        $panel->status = $panel->status === 'active' ? 'inactive' : 'active';
        $panel->save();
        return back()->with('success','Panel status updated.');
    }
}
