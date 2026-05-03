<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackendAuth
{
    public const BACKEND_GUARDS = ['admin', 'owner', 'kasir', 'mekanik'];

    public function guardForRole(?string $role): ?string
    {
        return in_array($role, self::BACKEND_GUARDS, true) ? $role : null;
    }

    public function resolveGuard(?Request $request = null): ?string
    {
        $request ??= request();

        $resolved = $request?->attributes->get('backend_guard');

        if (is_string($resolved) && in_array($resolved, self::BACKEND_GUARDS, true)) {
            return $resolved;
        }

        $requested = $request?->query('ctx') ?? $request?->input('ctx');

        if (in_array($requested, self::BACKEND_GUARDS, true)) {
            return $requested;
        }

        foreach (self::BACKEND_GUARDS as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }

    public function resolveGuardForRoles(array $roles, ?Request $request = null): ?string
    {
        $request ??= request();

        $resolved = $request?->attributes->get('backend_guard');

        if (is_string($resolved) && in_array($resolved, self::BACKEND_GUARDS, true)) {
            $user = Auth::guard($resolved)->user();

            if ($user && in_array($user->role, $roles, true)) {
                return $resolved;
            }
        }

        $requested = $request?->query('ctx') ?? $request?->input('ctx');

        if (in_array($requested, self::BACKEND_GUARDS, true)) {
            $user = Auth::guard($requested)->user();

            if ($user && in_array($user->role, $roles, true)) {
                return $requested;
            }
        }

        foreach (self::BACKEND_GUARDS as $guard) {
            $user = Auth::guard($guard)->user();

            if ($user && in_array($user->role, $roles, true)) {
                return $guard;
            }
        }

        return $this->resolveGuard($request);
    }

    public function user(?Request $request = null): ?User
    {
        $guard = $this->resolveGuard($request);

        return $guard ? Auth::guard($guard)->user() : null;
    }

    public function isAuthenticated(?Request $request = null): bool
    {
        $guard = $this->resolveGuard($request);

        return $guard ? Auth::guard($guard)->check() : false;
    }

    public function activeGuards(): array
    {
        return collect(self::BACKEND_GUARDS)
            ->filter(fn (string $guard) => Auth::guard($guard)->check())
            ->values()
            ->all();
    }

    public function logout(string $guard): void
    {
        Auth::guard($guard)->logout();
    }
}
