<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\WorkshopRating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientRatingController extends Controller
{
    public function index(): View
    {
        $clientUser = auth('client')->user();

        $hasRatingTable = Schema::hasTable('workshop_ratings');

        $completedBookings = Booking::with(['vehicle'])
            ->where('user_id', $clientUser->id)
            ->where('status', 'completed')
            ->latest('booking_date')
            ->get();

        $ratings = $hasRatingTable
            ? WorkshopRating::with(['booking.vehicle', 'user'])->where('rating', '>=', 4)->latest()->take(8)->get()
            : collect();

        $averageRating = $hasRatingTable ? (float) WorkshopRating::where('rating', '>=', 4)->avg('rating') : 0;
        $ratingCount = $hasRatingTable ? WorkshopRating::where('rating', '>=', 4)->count() : 0;

        return view('fe.rating.index', [
            'title' => 'Rating Bengkel',
            'clientUser' => $clientUser,
            'completedBookings' => $completedBookings,
            'ratings' => $ratings,
            'averageRating' => $averageRating,
            'ratingCount' => $ratingCount,
            'hasRatingTable' => $hasRatingTable,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('workshop_ratings')) {
            return back()->with('error', 'Tabel rating belum tersedia. Jalankan migrasi terlebih dulu.');
        }

        $clientUser = auth('client')->user();

        $validated = $request->validate([
            'booking_id' => [
                'nullable',
                Rule::exists('bookings', 'id')->where(fn ($query) => $query->where('user_id', $clientUser->id)->where('status', 'completed')),
            ],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:500'],
        ]);

        WorkshopRating::create([
            'user_id' => $clientUser->id,
            'booking_id' => $validated['booking_id'] ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Rating berhasil dikirim. Terima kasih atas masukannya.');
    }
}
