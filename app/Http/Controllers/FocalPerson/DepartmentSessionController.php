<?php
namespace App\Http\Controllers\FocalPerson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\AcademicSession;
use App\Models\FypGroup;
use App\Models\Batch;
use App\Models\SupervisorProfile;
use App\Models\StudentProfile;

class DepartmentSessionController extends Controller {

    public function index() {
        $departments = Department::orderBy('name')->get();
        $sessions = AcademicSession::orderBy('name')->get();
        return view('focal.departments-sessions', compact('departments', 'sessions'));
    }

    public function storeDepartment(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
        ]);
        Department::create([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => true,
        ]);
        return back()->with('success', 'Department added successfully.');
    }

    public function toggleDepartmentStatus($id) {
        $dept = Department::findOrFail($id);
        $dept->is_active = !$dept->is_active;
        $dept->save();
        return back()->with('success', 'Department status updated.');
    }

    public function deleteDepartment($id) {
        $dept = Department::findOrFail($id);

        $inUse = FypGroup::where('department_id', $id)->exists()
            || Batch::where('department_id', $id)->exists()
            || SupervisorProfile::where('department_id', $id)->exists()
            || StudentProfile::where('department_id', $id)->exists();

        if ($inUse) {
            return back()->with('error', 'Cannot delete "' . $dept->name . '" — it is already in use. You can deactivate it instead.');
        }

        $dept->delete();
        return back()->with('success', 'Department deleted.');
    }

    public function storeSession(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        AcademicSession::create([
            'name' => $request->name,
            'is_active' => true,
        ]);
        return back()->with('success', 'Session added successfully.');
    }

    public function toggleSessionStatus($id) {
        $session = AcademicSession::findOrFail($id);
        $session->is_active = !$session->is_active;
        $session->save();
        return back()->with('success', 'Session status updated.');
    }

    public function deleteSession($id) {
        $session = AcademicSession::findOrFail($id);

        $inUse = FypGroup::where('session_id', $id)->exists()
            || StudentProfile::where('session_id', $id)->exists();

        if ($inUse) {
            return back()->with('error', 'Cannot delete "' . $session->name . '" — it is already in use. You can deactivate it instead.');
        }

        $session->delete();
        return back()->with('success', 'Session deleted.');
    }
}
