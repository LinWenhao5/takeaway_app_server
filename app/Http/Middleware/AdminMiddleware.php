<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has admin privileges
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
