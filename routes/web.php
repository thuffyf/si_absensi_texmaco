<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NfcDeviceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Dashboard Utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Monitoring NFC Real-Time
    Route::get('/monitoring/nfc', [MonitoringController::class, 'nfc'])->name('monitoring.nfc');

    // Data Siswa
    Route::get('/siswa', [StudentController::class, 'index'])->name('students.index');
    Route::post('/siswa', [StudentController::class, 'store'])->name('students.store');
    Route::get('/siswa/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/siswa/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/siswa/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Data Guru
    Route::get('/guru', [TeacherController::class, 'index'])->name('teachers.index');
    Route::post('/guru', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/guru/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/guru/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/guru/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    // Jadwal Kelas
    Route::get('/jadwal/hadir/{slug}', [ScheduleController::class, 'presence'])
        ->name('schedules.presence')
        ->where('slug', 'x-tei|xi-tei|xii-tei');
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/jadwal', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/jadwal/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/jadwal/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/jadwal/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Request Izin & Sakit
    Route::get('/request-izin-sakit', [LeaveRequestController::class, 'index'])->name('requests.izin-sakit');
    Route::post('/request-izin-sakit', [LeaveRequestController::class, 'store'])->name('requests.store');
    Route::patch('/request-izin-sakit/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('requests.approve');
    Route::patch('/request-izin-sakit/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('requests.reject');

    // Notifikasi Guru
    Route::get('/notifications/guru-approvals', [NotificationController::class, 'teacherApprovals'])->name('notifications.guru-approvals');
    Route::patch('/notifications/guru-approvals/{leaveRequest}/approve', [NotificationController::class, 'approve'])->name('notifications.approve');
    Route::patch('/notifications/guru-approvals/{leaveRequest}/reject', [NotificationController::class, 'reject'])->name('notifications.reject');

    // Laporan Absensi
    Route::get('/laporan/absensi', [ReportController::class, 'absensi'])->name('reports.absensi');

    // Monitoring Alat NFC
    Route::get('/alat-nfc', [NfcDeviceController::class, 'index'])->name('devices.nfc-tools');
    Route::post('/alat-nfc', [NfcDeviceController::class, 'store'])->name('devices.store');
    Route::get('/alat-nfc/{device}/edit', [NfcDeviceController::class, 'edit'])->name('devices.edit');
    Route::put('/alat-nfc/{device}', [NfcDeviceController::class, 'update'])->name('devices.update');
    Route::delete('/alat-nfc/{device}', [NfcDeviceController::class, 'destroy'])->name('devices.destroy');

    // Pengaturan Sistem
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset-defaults', [SettingsController::class, 'resetDefaults'])
        ->name('settings.reset-defaults');
    Route::post('/settings/export', [SettingsController::class, 'export'])->name('settings.export');
    Route::post('/settings/cleanup', [SettingsController::class, 'cleanup'])->name('settings.cleanup');
    Route::post('/settings/reset-data', [SettingsController::class, 'resetData'])->name('settings.reset-data');
    Route::post('/settings/import-students', [SettingsController::class, 'importStudents'])
        ->name('settings.import-students');

    // Profile
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');

    Route::post('/profile', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if (!empty($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('profile-photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui.');
    })->name('profile.update');

    Route::post('/profile/delete-photo', function () {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        if (!empty($user->photo)) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        return response()->json(['success' => true]);
    })->name('profile.delete-photo');
});

