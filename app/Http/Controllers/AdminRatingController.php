<?php

namespace App\Http\Controllers;

use App\Models\WorkshopRating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminRatingController extends Controller
{
    public function __construct()
    {
        view()->share('title', 'Rating');
    }

    public function index()
    {
        $hasRatingTable = Schema::hasTable('workshop_ratings');

        $ratings = $hasRatingTable
            ? WorkshopRating::with(['user', 'booking.vehicle', 'responder'])->latest()->paginate(15)
            : collect();

        $averageRating = $hasRatingTable ? (float) WorkshopRating::avg('rating') : 0;
        $ratingCount = $hasRatingTable ? WorkshopRating::count() : 0;

        return view('be.rating.index', [
            'ratings' => $ratings,
            'averageRating' => $averageRating,
            'ratingCount' => $ratingCount,
            'hasRatingTable' => $hasRatingTable,
            'backendRole' => backend_user()?->role,
        ]);
    }

    public function reply(Request $request, WorkshopRating $rating): RedirectResponse
    {
        abort_unless(backend_user()?->role === 'admin', 403);

        if (! Schema::hasTable('workshop_ratings')) {
            return back()->with('error', 'Tabel rating belum tersedia. Jalankan migrasi dulu.');
        }

        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:1000'],
        ]);

        $rating->update([
            'admin_reply' => $validated['admin_reply'],
            'responded_by' => backend_user()?->id,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Balasan rating berhasil disimpan.');
    }
}
