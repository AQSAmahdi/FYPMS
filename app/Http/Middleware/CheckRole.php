<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        $userRole = $user->role === 'supervisor' ? 'faculty' : $user->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access. This page is not available for your role.');
        }

        return $next($request);
    }
}
