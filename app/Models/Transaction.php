<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id', 
        'mekanik_id', 
        'kasir_id', 
        'total_service', 
        'total_sparepart', 
        'grand_total'
    ];

    /**
     * Relasi ke Booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relasi ke Mekanik (User)
     */
    public function mekanik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    /**
     * Relasi ke Kasir (User)
     */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    /**
     * Relasi ke Detail Sparepart yang dibeli
     * (Hanya satu fungsi details saja)
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionSparepart::class, 'transaction_id');
    }

    /**
     * Relasi ke Payment
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function sparepart() {
    return $this->belongsTo(Sparepart::class);
    }
}