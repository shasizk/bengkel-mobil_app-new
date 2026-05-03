<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpToken extends Model
{
    protected $table = 'otp_tokens';
    protected $fillable = ['user_id', 'code', 'expires_at', 'used_at'];
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generate(User $user): string
    {
        // Delete old OTP tokens for this user
        static::where('user_id', $user->id)->delete();

        // Generate 6-digit OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create OTP token that expires in 15 minutes
        static::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        return $code;
    }

    public static function verify(User $user, string $code): bool
    {
        $otp = static::where('user_id', $user->id)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->where('used_at', null)
            ->first();

        if (!$otp) {
            return false;
        }

        $otp->update(['used_at' => now()]);
        return true;
    }

    public static function isValid(User $user): bool
    {
        return static::where('user_id', $user->id)
            ->where('used_at', '!=', null)
            ->exists();
    }
}
