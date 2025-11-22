<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscordRoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasDiscordRole')) {
            abort(403, 'Unauthorized');
        }
        foreach ($roles as $role) {
            if ($user->hasDiscordRole($role)) {
                return $next($request);
            }
        }
        abort(403, 'Akses halaman ini dibatasi oleh role Discord.');
    }
}
