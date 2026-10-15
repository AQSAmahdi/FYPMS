<?php
namespace App\Http\Middleware;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($user->role === 'focal_person') return redirect('/focal/dashboard');
                if ($user->role === 'faculty' || $user->role === 'supervisor') return redirect('/faculty/dashboard');
                if ($user->role === 'student') return redirect('/student/dashboard');
                return redirect('/focal/dashboard');
            }
        }
        return $next($request);
    }
}
