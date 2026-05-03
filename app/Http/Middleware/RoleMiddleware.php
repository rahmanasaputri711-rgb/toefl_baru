<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
   public function handle($request, Closure $next, $role)
{
    if (!auth()->check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    if (auth()->user()->role != $role) {
        return response()->json([
            'message' => 'Akses ditolak'
        ], 403);
    }

    return $next($request);
}
}