<?php

namespace App\Providers;

use App\Support\BackendAuth;
use App\Models\Booking;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');

        $this->app->singleton(BackendAuth::class, fn () => new BackendAuth());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $backendAuth = app(BackendAuth::class);
            $backendUser = $backendAuth->user(request());
            $bookingNotifications = collect();
            $pendingBookingCount = 0;

            if ($backendUser && Schema::hasTable('bookings') && in_array($backendUser->role, ['admin', 'mekanik', 'kasir', 'owner'], true)) {
                $bookingNotifications = Booking::with(['user', 'vehicle'])
                    ->latest()
                    ->take(5)
                    ->get();
                $pendingBookingCount = Booking::where('status', 'pending')->count();
            }

            $view->with('backendAuthUser', $backendUser);
            $view->with('backendGuard', $backendAuth->resolveGuard(request()));
            $view->with('backendActiveGuards', $backendAuth->activeGuards());
            $view->with('backendBookingNotifications', $bookingNotifications);
            $view->with('backendPendingBookingCount', $pendingBookingCount);
        });
    }
}
