<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoutePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $currentRouteName = $request->route()->getName();

        // Admin bypasses route protections automatically
        if ($user->role === 'admin') {
            return $next($request);
        }

        // If the current route has a name, check if user's role has permission
        if ($currentRouteName && !$user->hasPermissionToRoute($currentRouteName)) {
            abort(403, 'Your role does not have authorization to view this page.');
        }

        return $next($request);
    }
}
