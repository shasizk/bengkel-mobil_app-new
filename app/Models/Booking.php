<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    // Pastikan fillable sesuai dengan kolom yang ada di database kamu
    protected $fillable = [
        'user_id',
        'vehicle_id', // Tadi di foto skema kamu kolomnya 'vehicle_id', bukan 'vehicle_type'
        'mekanik_id',
        'booking_date',
        'booking_time', // Tambahkan ini karena ada di database kamu
        'status',
        'complaint',
        'payment_preference',
    ];

    /**
     * Relasi ke User.
     * Gunakan nama fungsi tunggal (user) karena satu booking dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        // Pastikan memanggil 'User::class' (U kapital), bukan 'users' (nama tabel).
        // Laravel akan otomatis mencari kolom 'user_id' di tabel bookings.
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Vehicle.
     * Gunakan nama fungsi tunggal (vehicle) karena satu booking dimiliki oleh satu kendaraan.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(BookingService::class, 'booking_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'booking_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(WorkshopRating::class, 'booking_id');
    }
}
