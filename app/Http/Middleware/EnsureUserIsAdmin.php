<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user || !$user->admin) {
            abort(403, 'Csak adminisztrátor érheti el ezt az oldalt.');
        }

        return $next($request);
    }
}
