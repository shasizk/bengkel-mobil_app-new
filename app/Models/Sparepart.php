<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $table = 'spareparts'; // Memastikan Laravel pakai tabel yang sudah kamu buat

    protected $fillable = [
        'name',
        'brand',
        'stock',
        'price'
    ];
}