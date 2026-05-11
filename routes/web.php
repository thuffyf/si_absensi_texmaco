<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Admin Dashboard Routes untuk Sistem Absensi NFC Texmaco
|
*/

// Login Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required|email',
        'password' => 'required|string',
        'status' => 'required|in:siswa,tata_usaha,guru',
    ]);

    $user = User::where('email', $request->username)->first();

    $passwordOk = false;
    if ($user) {
        $stored = (string) $user->password;
        $looksHashed = (bool) preg_match('/^\$2[aby]\$\d{2}\$.+/', $stored);

        if ($looksHashed) {
            $passwordOk = Hash::check($request->password, $stored);
        } else {
            // Mode percobaan: jika password di DB masih plaintext, izinkan sekali,
            // lalu upgrade ke hash agar berikutnya aman.
            $passwordOk = hash_equals($stored, (string) $request->password);
            if ($passwordOk) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
        }
    }

    if ($user && $passwordOk && $request->status === 'tata_usaha') {
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'login' => 'Email, password, atau status tidak valid untuk akses Tata Usaha.',
    ])->withInput($request->only('username','status'));
})->name('login.submit');

// Dashboard Routes (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard Utama
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');
    
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    });

    // Monitoring NFC Real-Time
    Route::get('/monitoring/nfc', function () {
        return view('monitoring.nfc');
    })->name('monitoring.nfc');

    // Data Siswa
    Route::get('/siswa', function () {
        return view('students.index');
    })->name('students.index');

    // Data Guru
    Route::get('/guru', function () {
        return view('teachers.index');
    })->name('teachers.index');

    // Jadwal Kelas
    Route::get('/jadwal', function () {
        return view('schedules.index');
    })->name('schedules.index');

    // Request Izin & Sakit
    Route::get('/request-izin-sakit', function () {
        return view('requests.izin-sakit');
    })->name('requests.izin-sakit');

    // Notifikasi: persetujuan laporan guru (izin / alpha)
    Route::get('/notifikasi/persetujuan-guru', function () {
        return view('notifications.guru-persetujuan');
    })->name('notifications.guru-approvals');

    // Laporan Absensi
    Route::get('/laporan/absensi', function () {
        return view('reports.absensi');
    })->name('reports.absensi');

    // Monitoring Alat NFC
    Route::get('/alat-nfc', function () {
        return view('devices.nfc-tools');
    })->name('devices.nfc-tools');

    // Pengaturan Sistem
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
});

