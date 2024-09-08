<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has the specified role
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // If the user is not authenticated or does not have the role, return unauthorized response
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
