<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkshopRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'rating',
        'comment',
        'admin_reply',
        'responded_by',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'responded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}
