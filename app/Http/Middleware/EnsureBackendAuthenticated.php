<?php

namespace App\Http\Middleware;

use App\Support\BackendAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBackendAuthenticated
{
    public function __construct(
        protected BackendAuth $backendAuth
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $guard = $this->backendAuth->resolveGuard($request);

        if (! $guard || ! auth($guard)->check()) {
            return redirect()->route('admin.login', array_filter([
                'ctx' => $guard,
            ]));
        }

        $request->attributes->set('backend_guard', $guard);
        $request->setUserResolver(fn () => auth($guard)->user());

        return $next($request);
    }
}
