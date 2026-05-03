<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\User;
use App\Models\WorkshopRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientUser = auth('client')->user();
        $services = Schema::hasTable('services')
            ? Service::orderBy('service_name')->get()
            : collect();
        $vehicles = $clientUser?->role === 'customer'
            ? $clientUser->vehicles()->orderBy('license_plate')->get()
            : collect();
        $recentBookings = $clientUser?->role === 'customer'
            ? Booking::with(['vehicle', 'services.service', 'transaction.payment'])
                ->where('user_id', $clientUser->id)
                ->latest()
                ->take(3)
                ->get()
            : collect();

        $averageRating = Schema::hasTable('workshop_ratings')
            ? (float) WorkshopRating::avg('rating')
            : 0;
        $ratingCount = Schema::hasTable('workshop_ratings')
            ? WorkshopRating::count()
            : 0;
        $latestRatings = Schema::hasTable('workshop_ratings')
            ? WorkshopRating::with('user')->latest()->take(3)->get()
            : collect();

        return view('fe.home.index', [
            'title' => 'Home',
            'services' => $services,
            'clientUser' => $clientUser,
            'vehicles' => $vehicles,
            'recentBookings' => $recentBookings,
            'averageRating' => $averageRating,
            'ratingCount' => $ratingCount,
            'latestRatings' => $latestRatings,
        ]);
    }

    public function booking()
    {
        $clientUser = auth('client')->user();
        $services = Schema::hasTable('services')
            ? Service::orderBy('service_name')->get()
            : collect();
        $vehicles = $clientUser?->role === 'customer'
            ? $clientUser->vehicles()->orderBy('license_plate')->get()
            : collect();

        return view('fe.booking.index', [
            'title' => 'Booking',
            'services' => $services,
            'clientUser' => $clientUser,
            'vehicles' => $vehicles,
        ]);
    }

    public function about()
    {
        $serviceCount = Schema::hasTable('services') ? Service::count() : 0;
        $bookingCount = Schema::hasTable('bookings') ? Booking::count() : 0;
        $completedCount = Schema::hasTable('bookings') ? Booking::where('status', 'completed')->count() : 0;
        $mechanicCount = Schema::hasTable('users') ? User::where('role', 'mekanik')->count() : 0;

        $progressBase = max(1, $bookingCount);
        $aboutStats = [
            'service_count' => $serviceCount,
            'booking_count' => $bookingCount,
            'completed_count' => $completedCount,
            'mechanic_count' => $mechanicCount,
            'completion_rate' => min(100, (int) round(($completedCount / $progressBase) * 100)),
        ];

        return view('fe.about.index', [
            'title' => 'About',
            'clientUser' => auth('client')->user(),
            'aboutStats' => $aboutStats,
            'workshopAddress' => 'Jl. Raya Servis Mobil No. 24, Kota Anda',
            'workshopPhone' => '0812-0000-0000',
            'workshopEmail' => 'service@bengkelmobil.test',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
