<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'role'     => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email or password is incorrect',
            ], 401);
        }

        $user = Auth::user();

        // Map faculty/supervisor to a single tab value
        $actualRole   = $user->role === 'supervisor' ? 'faculty' : $user->role;
        $selectedRole = $request->role;

        if ($actualRole !== $selectedRole) {
            Auth::logout();
            $labels = [
                'focal_person' => 'Focal Person',
                'faculty'      => 'Faculty',
                'student'      => 'Student',
            ];
            return response()->json([
                'success' => false,
                'message' => 'This account is registered as ' . ($labels[$actualRole] ?? $actualRole) . '. Please select the correct role.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'role'    => $user->role,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    public function registerStudent(Request $request)
    {
        if ($request->user()->role !== 'focal_person') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Only Focal Person can register students.'], 403);
        }

        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
            'student_id'       => 'required|string|max:50',
            'department_id'    => 'required|exists:departments,id',
            'session_id'       => 'required|exists:academic_sessions,id',
            'semester'         => 'required|integer|min:1|max:8',
            'shift'            => 'required|in:Morning,Evening',
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'student',
        ]);

        \App\Models\StudentProfile::create([
            'user_id'          => $user->id,
            'student_id'       => $request->student_id,
            'department_id'    => $request->department_id,
            'session_id'       => $request->session_id,
            'current_semester' => $request->semester,
            'shift'            => $request->shift,
            'batch_id'         => $request->session_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student registered successfully.',
            'user'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role],
        ], 201);
    }

    public function registerFaculty(Request $request)
    {
        if ($request->user()->role !== 'focal_person') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Only Focal Person can register faculty.'], 403);
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6',
            'department_id' => 'required|exists:departments,id',
            'designation'   => 'required|string|max:255',
            'employee_id'   => 'required|string|unique:supervisor_profiles,employee_id',
            'contact'       => 'nullable|string|max:255',
            'max_groups'    => 'required|integer|min:1|max:6',
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'faculty',
        ]);

        \App\Models\SupervisorProfile::create([
            'user_id'       => $user->id,
            'department_id' => $request->department_id,
            'designation'   => $request->designation,
            'employee_id'   => $request->employee_id,
            'contact'       => $request->contact,
            'max_groups'    => $request->max_groups,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Faculty registered successfully.',
            'user'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role],
        ], 201);
    }
}
