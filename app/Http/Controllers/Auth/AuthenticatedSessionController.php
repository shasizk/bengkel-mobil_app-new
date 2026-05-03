<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\BackendAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        protected BackendAuth $backendAuth
    ) {
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function createDefault(): View
    {
        return view('auth.client-login');
    }

    public function createClient(): View
    {
        return view('auth.client-login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = $request->authenticateBackend();

        if ($user->role === 'customer') {
            throw ValidationException::withMessages([
                'email' => 'Akun customer masuk dari halaman login client.',
            ]);
        }

        $guard = $this->backendAuth->guardForRole($user->role);
        Auth::guard($guard)->login($user, $request->boolean('remember'));
        $this->refreshSessionAfterLogin($request);

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard.index', ['ctx' => $guard]),
            'owner' => redirect()->route('admin.dashboard.index', ['ctx' => $guard]),
            'kasir' => redirect()->route('admin.transactions.index', ['ctx' => $guard]),
            'mekanik' => redirect()->route('admin.booking.index', ['ctx' => $guard]),
            default => redirect()->route('home'),
        };
    }

    public function storeClient(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('client');
        $this->refreshSessionAfterLogin($request);

        $user = Auth::guard('client')->user();

        if ($user->role !== 'customer') {
            $this->logoutAndInvalidate($request, 'client');

            throw ValidationException::withMessages([
                'email' => 'Akun admin, owner, mekanik, dan kasir masuk dari login admin.',
            ]);
        }

        return redirect()->route('home');
    }

    public function storeDefault(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('web');
        $this->refreshSessionAfterLogin($request);

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroyAdmin(Request $request): RedirectResponse
    {
        $guard = $this->backendAuth->resolveGuard($request) ?? 'admin';
        $role = Auth::guard($guard)->user()?->role;

        $this->logoutAndInvalidate($request, $guard);

        if ($role === 'customer') {
            return redirect()->route('home');
        }

        return redirect()->route('admin.login', array_filter([
            'ctx' => $guard,
        ]));
    }

    public function destroyClient(Request $request): RedirectResponse
    {
        $this->logoutAndInvalidate($request, 'client');

        return redirect()->route('home');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $guard = $this->resolveGuard($request);
        $role = Auth::guard($guard)->user()?->role;

        $this->logoutAndInvalidate($request, $guard);

        if ($guard === 'client' || $role === 'customer') {
            return redirect()->route('home');
        }

        if ($guard === 'web') {
            return redirect()->route('home');
        }

        return redirect()->route('admin.login', array_filter([
            'ctx' => $guard !== 'client' ? $guard : null,
        ]));
    }

    protected function logoutAndInvalidate(Request $request, string $guard): void
    {
        Auth::guard($guard)->logout();
        $request->session()->regenerateToken();
    }

    protected function refreshSessionAfterLogin(Request $request): void
    {
        // Rotate session ID after successful auth without invalidating CSRF tokens
        // in other open tabs/role login pages.
        $request->session()->migrate(true);
    }

    protected function resolveGuard(Request $request): string
    {
        if ($request->routeIs('admin.*') || $request->is('admin') || $request->is('admin/*')) {
            return $this->backendAuth->resolveGuard($request) ?? 'admin';
        }

        if ($request->routeIs('client.*') || $request->is('client/*')) {
            return 'client';
        }

        if (Auth::guard('web')->check()) {
            return 'web';
        }

        if (Auth::guard('client')->check() && empty($this->backendAuth->activeGuards())) {
            return 'client';
        }

        return $this->backendAuth->resolveGuard($request) ?? 'admin';
    }
}
