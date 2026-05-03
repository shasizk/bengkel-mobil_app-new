<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(string $guard = 'web'): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');

        try {
            if (Auth::guard($guard)->attempt($credentials, $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());

                return;
            }
        } catch (\RuntimeException $e) {
            // Fallback untuk akun lama yang password di DB belum di-hash bcrypt.
        }

        $user = User::where('email', $this->string('email'))->first();

        if ($user && hash_equals((string) $user->password, (string) $this->string('password'))) {
            $user->forceFill([
                'password' => Hash::make($this->string('password')),
            ])->save();

            Auth::guard($guard)->login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());

            return;
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Attempt to authenticate a backend user and return the matched account.
     *
     * @throws ValidationException
     */
    public function authenticateBackend(): User
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->string('email'))->first();

        if (! $user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $plainPassword = (string) $this->string('password');

        try {
            if (! Hash::check($plainPassword, (string) $user->password)) {
                if (! hash_equals((string) $user->password, $plainPassword)) {
                    RateLimiter::hit($this->throttleKey());

                    throw ValidationException::withMessages([
                        'email' => trans('auth.failed'),
                    ]);
                }

                $user->forceFill([
                    'password' => Hash::make($plainPassword),
                ])->save();
            }
        } catch (\RuntimeException $e) {
            if (! hash_equals((string) $user->password, $plainPassword)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }

            $user->forceFill([
                'password' => Hash::make($plainPassword),
            ])->save();
        }

        RateLimiter::clear($this->throttleKey());

        return $user;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email')).'|'.$this->ip().'|'.$this->path()
        );
    }
}
