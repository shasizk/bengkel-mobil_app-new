<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'vehicle', 'services.service', 'transaction.payment', 'mechanic'])->latest()->get();
        $mechanics = User::where('role', 'mekanik')->orderBy('name')->get();
        $title = 'Booking';

        return view('booking.index', compact('bookings', 'title', 'mechanics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|digits:4|integer|min:1900|max:' . now()->year,
            'license_plate' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'payment_preference' => 'nullable|in:cash,transfer,qris',
            'complaint' => 'required|string',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
        ]);

        if (empty($validated['vehicle_id'])) {
            validator($request->all(), [
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|digits:4|integer|min:1900|max:' . now()->year,
                'license_plate' => 'required|string|max:50',
                'color' => 'required|string|max:50',
            ])->validate();
        }

        DB::transaction(function () use ($validated) {
            $user = Auth::guard('client')->check() && Auth::guard('client')->user()->role === 'customer'
                ? Auth::guard('client')->user()
                : User::firstOrNew(['email' => $validated['email']]);

            if ($user->exists && $user->role !== 'customer') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'Email ini sudah dipakai akun internal. Gunakan email customer yang berbeda.',
                ]);
            }

            if (! $user->exists) {
                $user->fill([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] ?? null,
                    'role' => 'customer',
                ]);
                $user->password = Str::random(16);
            } else {
                $user->fill([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] ?? null,
                ]);
            }

            $user->save();

            if (! empty($validated['vehicle_id'])) {
                $vehicle = Vehicle::where('user_id', $user->id)->findOrFail($validated['vehicle_id']);
            } else {
                $vehicle = Vehicle::firstOrNew([
                    'license_plate' => strtoupper($validated['license_plate']),
                ]);
                $vehicle->fill([
                    'user_id' => $user->id,
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'year' => $validated['year'],
                    'license_plate' => strtoupper($validated['license_plate']),
                    'color' => $validated['color'],
                ]);
                $vehicle->save();
            }

            $hasScheduleConflict = Booking::whereDate('booking_date', $validated['booking_date'])
                ->where('booking_time', $validated['booking_time'])
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->exists();

            if ($hasScheduleConflict) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'booking_time' => 'Jadwal booking bentrok dengan booking lain. Pilih tanggal atau jam yang berbeda.',
                ]);
            }

            $booking = Booking::create([
                'user_id' => $user->id,
                'vehicle_id' => $vehicle->id,
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'],
                'status' => 'pending',
                'complaint' => $validated['complaint'],
                'payment_preference' => $validated['payment_preference'] ?? 'cash',
            ]);

            $services = Service::whereIn('id', $validated['service_ids'])->get();

            foreach ($services as $service) {
                BookingService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id,
                    'price' => $service->price,
                ]);
            }
        });

        if (Auth::guard('client')->check()) {
            return redirect()
                ->route('client.history')
                ->with('success', 'Booking berhasil dikirim. Riwayat booking Anda sudah diperbarui.')
                ->with('booking_submitted', true);
        }

        return redirect()
            ->route('home')
            ->with('success', 'Booking berhasil dikirim dan sudah masuk ke daftar admin.');
    }

    /**
     * Update status booking (Pending, Confirmed, dll).
     */
    public function updateStatus($id, $status)
    {
        $authUser = backend_user();
        $role = $authUser?->role;

        if (! in_array($role, ['admin', 'mekanik'], true)) {
            return redirect()
                ->to(backend_route('admin.booking.index'))
                ->with('error', 'Anda tidak memiliki akses untuk mengubah status booking.');
        }

        $booking = Booking::with(['user', 'vehicle', 'services.service'])->findOrFail($id);

        if ($role === 'admin') {
            if (! in_array($status, ['confirmed', 'cancelled'], true)) {
                return redirect()
                    ->to(backend_route('admin.booking.index'))
                    ->with('error', 'Admin hanya bisa konfirmasi atau membatalkan booking.');
            }

            if ($booking->status !== 'pending') {
                return redirect()
                    ->to(backend_route('admin.booking.index'))
                    ->with('error', 'Hanya booking dengan status pending yang bisa dikonfirmasi atau dibatalkan.');
            }
        }

        if ($role === 'mekanik') {
            if (! in_array($status, ['in_progress', 'completed'], true)) {
                return redirect()
                    ->to(backend_route('admin.booking.index'))
                    ->with('error', 'Mekanik hanya bisa mulai kerja atau konfirmasi selesai.');
            }

            if ($status === 'in_progress') {
                if ($booking->status !== 'confirmed') {
                    return redirect()
                        ->to(backend_route('admin.booking.index'))
                        ->with('error', 'Booking harus berstatus confirmed sebelum mulai dikerjakan.');
                }

                if ($booking->mekanik_id && (int) $booking->mekanik_id !== (int) $authUser->id) {
                    return redirect()
                        ->to(backend_route('admin.booking.index'))
                        ->with('error', 'Booking ini sudah ditangani mekanik lain.');
                }

                $booking->mekanik_id = $authUser->id;
            }

            if ($status === 'completed') {
                if ($booking->status !== 'in_progress') {
                    return redirect()
                        ->to(backend_route('admin.booking.index'))
                        ->with('error', 'Booking harus berstatus in progress sebelum diselesaikan.');
                }

                if ((int) $booking->mekanik_id !== (int) $authUser->id) {
                    return redirect()
                        ->to(backend_route('admin.booking.index'))
                        ->with('error', 'Hanya mekanik yang menangani yang bisa menyelesaikan booking ini.');
                }
            }
        }

        $booking->status = $status;
        $booking->save();

        $emailFailureMessage = null;

        if ($status === 'confirmed' && $booking->user?->email) {
            try {
                Mail::to($booking->user->email)->send(new BookingConfirmedMail($booking));
            } catch (\Throwable $exception) {
                Log::warning('Failed to send booking confirmation email.', [
                    'booking_id' => $booking->id,
                    'email' => $booking->user->email,
                    'message' => $exception->getMessage(),
                ]);

                $emailFailureMessage = 'Status booking berhasil diubah, tetapi email konfirmasi gagal dikirim ke '.$booking->user->email.'.';
            }
        } elseif ($status === 'confirmed') {
            $emailFailureMessage = 'Status booking berhasil diubah, tetapi email customer belum tersedia.';
        }

        if ($emailFailureMessage) {
            return redirect()->back()->with('error', $emailFailureMessage);
        }

        $successMessage = 'Status booking berhasil diperbarui ke '.$status;

        if ($status === 'confirmed' && $booking->user?->email) {
            $successMessage .= '. Email konfirmasi telah dikirim ke '.$booking->user->email.'.';
        }

        if ($status === 'completed') {
            $successMessage .= '. Booking siap diproses pembayaran oleh kasir/admin (cash, transfer, atau qris).';
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Menghapus data booking.
     */
    public function destroy(string $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return redirect()->back()->with('success', 'Data booking berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Fungsi create, store, edit, update bisa kamu isi nanti 
    // jika ingin menambahkan fitur tambah/edit booking secara manual oleh admin.

    public function show ($id) {
        $title = 'Booking';
        $booking = Booking::with(['user', 'vehicle.user', 'services.service', 'mechanic'])->findOrFail($id);
        return view('booking.show', compact('booking', 'title'));
    }
}
