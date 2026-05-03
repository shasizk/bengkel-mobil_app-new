<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Add session timeout info to response headers for client-side handling
        $sessionTimeout = config('session.lifetime') * 60 * 1000; // Convert to milliseconds
        $warningTime = ($sessionTimeout - (10 * 60 * 1000)); // Warn 10 minutes before expiry

        $response = $next($request);
        
        if ($request->user()) {
            $response->header('X-Session-Timeout', $sessionTimeout);
            $response->header('X-Session-Warning-Time', $warningTime);
        }

        return $response;
    }
}
