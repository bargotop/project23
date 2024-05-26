<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.index');
        }

        return $next($request);
    }
}
