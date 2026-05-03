<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $table = 'services'; // Nama tabel di DB manualmu
    protected $fillable = ['service_name', 'description', 'price', 'estimated_time'];

    public function bookingServices(): HasMany
    {
        return $this->hasMany(BookingService::class, 'service_id');
    }
}
