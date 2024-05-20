<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        if ($user->role && $user->role->name === $role) {
            return $next($request);
        }

        return abort(403, 'Unauthorized action.');
    }
}

