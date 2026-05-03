<?php

namespace App\Http\Middleware;

use App\Support\BackendAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function __construct(
        protected BackendAuth $backendAuth
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $guard = $request->routeIs('admin.*') || $request->is('admin') || $request->is('admin/*')
            ? ($this->backendAuth->resolveGuardForRoles($roles, $request) ?? 'admin')
            : 'client';
        $user = $guard === 'client'
            ? auth('client')->user()
            : $this->backendAuth->user($request);

        if (! $user) {
            return redirect()->route($guard === 'client' ? 'client.login' : 'admin.login', array_filter([
                'ctx' => $guard !== 'client' ? $guard : null,
            ]));
        }

        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        $redirectRoute = $user->role === 'customer'
            ? 'home'
            : match ($user->role) {
                'kasir' => 'admin.transactions.index',
                'mekanik' => 'admin.booking.index',
                'admin', 'owner' => 'admin.dashboard.index',
                default => 'home',
            };

        return redirect()
            ->route($redirectRoute, str_starts_with($redirectRoute, 'admin.') ? ['ctx' => $guard] : [])
            ->with('error', 'Akses ke halaman itu tidak sesuai dengan role akun Anda.');
    }
}
