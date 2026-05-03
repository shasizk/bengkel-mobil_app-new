<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicAttendance extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_date',
        'status',
        'notes',
        'face_photo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
