<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    // Nama tabel di database
    protected $table = 'payments';

    // Kolom yang boleh diisi
    protected $fillable = [
        'transaction_id',
        'payment_code',
        'amount_paid',
        'payment_method',
        'payment_status',
        'snap_token',
        'payment_url',
        'qr_string',
        'gateway_status',
        'gateway_response',
        'paid_at',
    ];

    /**
     * Relasi balik ke Transaction
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
