<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized access. You need to have one of these roles: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}