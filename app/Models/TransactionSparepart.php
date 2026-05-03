<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionSparepart extends Model
{
    // Nama tabel di database
    protected $table = 'transaction_spareparts';

    // Kolom yang boleh diisi
    protected $fillable = [
        'transaction_id',
        'sparepart_id',
        'qty',
        'price',
        'subtotal',
    ];

    /**
     * Relasi balik ke Sparepart
     */
    public function sparepart(): BelongsTo
    {
        return $this->belongsTo(Sparepart::class);
    }

    /**
     * Relasi balik ke Transaction
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}