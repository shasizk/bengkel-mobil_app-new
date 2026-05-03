<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Display the OTP verification view.
     */
    public function create(): View
    {
        if (!session('registration_email')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', [
            'email' => session('registration_email'),
        ]);
    }

    /**
     * Handle incoming OTP verification request.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('registration_email');

        if (!$email) {
            return redirect()->route('register')->with('error', 'Session berakhir. Silakan daftar ulang.');
        }

        $user = User::where('email', $email)->firstOrFail();

        if (!OtpToken::verify($user, $request->otp)) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP salah atau sudah kadaluarsa.',
            ]);
        }

        // Mark email as verified
        $user->update(['email_verified_at' => now()]);

        // Redirect to login with success message
        $request->session()->forget('registration_email');

        return redirect()->route('client.login')->with('status', 'Email berhasil diverifikasi. Silakan login dengan akun Anda.');
    }

    /**
     * Resend OTP code.
     */
    public function resend(Request $request): RedirectResponse
    {
        $email = session('registration_email');

        if (!$email) {
            return redirect()->route('register')->with('error', 'Session berakhir. Silakan daftar ulang.');
        }

        $user = User::where('email', $email)->firstOrFail();

        if ($user->email_verified_at) {
            return redirect()->route('client.login')->with('info', 'Email Anda sudah diverifikasi. Silakan login.');
        }

        // Generate new OTP
        $otp = OtpToken::generate($user);

        // Send OTP email
        \Mail::to($user->email)->send(new \App\Mail\OtpMail($user, $otp));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
