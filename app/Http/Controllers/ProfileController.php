<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        if ($request->routeIs('client.*')) {
            return redirect()->route('client.profile');
        }

        return view('profile.edit', [
            'user' => backend_user(),
            'title' => 'User',
        ]);
    }

    public function clientProfile(Request $request): View
    {
        $user = Auth::guard('client')->user()->load(['vehicles']);
        [$bookings, $stats] = $this->buildClientBookingData($user->id, $user->vehicles->count());

        return view('fe.profile.index', [
            'user' => $user,
            'bookings' => $bookings,
            'stats' => $stats,
            'title' => 'Client Profile',
        ]);
    }

    public function clientHistory(Request $request): View
    {
        $user = Auth::guard('client')->user()->load(['vehicles']);
        [$bookings, $stats] = $this->buildClientBookingData($user->id, $user->vehicles->count());

        return view('fe.profile.history', [
            'user' => $user,
            'bookings' => $bookings,
            'stats' => $stats,
            'title' => 'Riwayat Booking',
        ]);
    }

    protected function buildClientBookingData(int $userId, int $vehicleCount): array
    {
        $bookings = Booking::with([
            'vehicle',
            'services.service',
            'transaction.payment',
        ])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $stats = [
            'total_bookings' => $bookings->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'active_bookings' => $bookings->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count(),
            'total_vehicles' => $vehicleCount,
            'paid_bookings' => $bookings->filter(fn ($booking) => $booking->transaction?->payment?->payment_status === 'paid')->count(),
        ];

        return [$bookings, $stats];
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $guard = $this->resolveGuard($request);
        $user = Auth::guard($guard)->user();
        $validated = $request->validated();

        unset($validated['profile_photo']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->profile_photo_path = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->save();

        $redirect = $guard === 'client'
            ? Redirect::route('client.profile')
            : Redirect::to(backend_route('admin.profile.edit'));

        return $redirect->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $guard = $this->resolveGuard($request);

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:'.$guard],
        ]);

        $user = Auth::guard($guard)->user();

        Auth::guard($guard)->logout();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    protected function resolveGuard(Request $request): string
    {
        return $request->routeIs('client.*') ? 'client' : (backend_guard() ?? 'admin');
    }
}
