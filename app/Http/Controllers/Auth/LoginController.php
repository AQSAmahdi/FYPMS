<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/focal/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
{
    if ($user->role === 'focal_person') {
        return redirect('/focal/dashboard');
    } elseif ($user->role === 'faculty' || $user->role === 'supervisor') {
        return redirect('/faculty/dashboard');
    } elseif ($user->role === 'student') {
        return redirect('/student/dashboard');
    }
}
}
