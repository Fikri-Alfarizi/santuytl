<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // SEMENTARA: izinkan semua user (role apapun) akses fitur admin
        if (!Auth::check()) {
            abort(403, 'Access denied.');
        }
        return $next($request);
    }
}
