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
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the user's role
            $userRole = Auth::user()->role->name;

            // Check if the user's role matches the required role
            if ($userRole === $role) {
                return $next($request);
            }
        }

        // Redirect the user to an unauthorized page or return a 403 Forbidden response
        return redirect('/');
    }
}
