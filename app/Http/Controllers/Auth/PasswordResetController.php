<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Menampilkan form request link reset password
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Mengirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Rate limiting: maksimal 3 request per jam per email
        $key = 'password-reset:' . $request->ip() . ':' . strtolower($request->email);
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$minutes} menit.",
            ]);
        }

        // Cek apakah email ini adalah admin atau tata usaha
        $user = User::where('email', strtolower($request->email))->first();
        
        if ($user && in_array($user->role, ['admin', 'tata_usaha'])) {
            // Admin/TU tidak boleh reset password via email (email fiktif)
            // Tampilkan pesan umum agar tidak leak info
            RateLimiter::hit($key, 3600); // 1 jam
            
            // Redirect ke halaman instruksi kontak admin
            return redirect()->route('password.contact-admin')
                ->with('info', 'Untuk reset password akun administrator, silakan hubungi administrator sistem.');
        }

        // Increment rate limiter
        RateLimiter::hit($key, 3600); // 1 jam

        // Kirim reset link (hanya untuk guru dan siswa)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Selalu tampilkan pesan sukses untuk mencegah email enumeration
        // Tidak peduli apakah email ada atau tidak
        return back()->with('status', 'Jika email terdaftar dalam sistem, link reset password telah dikirim. Silakan cek inbox (termasuk folder spam).');
    }

    /**
     * Menampilkan halaman instruksi kontak admin
     */
    public function showContactAdminForm()
    {
        return view('auth.passwords.contact-admin');
    }

    /**
     * Menampilkan form reset password dengan token
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    /**
     * Reset password dengan token
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // minimal 1 huruf kecil
                'regex:/[A-Z]/',      // minimal 1 huruf besar
                'regex:/[0-9]/',      // minimal 1 angka
            ],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        // Cek apakah email ini adalah admin atau tata usaha
        $user = User::where('email', strtolower($request->email))->first();
        
        if ($user && in_array($user->role, ['admin', 'tata_usaha'])) {
            return back()->withErrors([
                'email' => 'Reset password untuk akun administrator harus dilakukan melalui administrator sistem.'
            ]);
        }

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update password dengan hash
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Fire event password reset
                event(new PasswordReset($user));
            }
        );

        // Jika berhasil, redirect ke login dengan pesan sukses
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
        }

        // Jika gagal, tampilkan error
        return back()->withErrors(['email' => __($status)]);
    }
}
