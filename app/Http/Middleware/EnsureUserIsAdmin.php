<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the logged-in user has a role named 'admin'
        if (!auth()->user()->roles()->where('name', 'admin')->exists()) {
            // abort(403) returns an HTTP 403 Forbidden response
            // This stops the request and shows Laravel's error page
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
