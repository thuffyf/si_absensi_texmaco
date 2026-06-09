<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LeaveRequestController;
use App\Models\Student;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NfcDeviceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\NotificationController;
use App\Support\RecaptchaBypass;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Admin Dashboard Routes untuk Sistem Absensi NFC Texmaco
|
*/

// Login Routes
Route::get('/login', function (Request $request) {
    $recaptchaBypass = RecaptchaBypass::enabled($request);

    return view('auth.login', [
        'recaptchaSiteKey' => $recaptchaBypass ? null : config('services.recaptcha.site_key'),
        'recaptchaBypass' => $recaptchaBypass,
    ]);
})->name('login');

Route::post('/login', function (Request $request) {
    $recaptchaBypass = RecaptchaBypass::enabled($request);

    $request->validate([
        'username' => 'required|email',
        'password' => 'required|string',
        'g-recaptcha-response' => $recaptchaBypass ? 'nullable|string' : 'required|string',
    ]);

    if (! $recaptchaBypass) {
        $recaptchaSecret = config('services.recaptcha.secret_key');
        if (! $recaptchaSecret) {
            return back()
                ->withErrors(['captcha' => 'Konfigurasi captcha belum lengkap.'])
                ->withInput($request->only('username'));
        }

        $recaptcha = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (! $recaptcha->ok() || ! ($recaptcha->json('success') === true)) {
            return back()
                ->withErrors(['captcha' => 'Verifikasi captcha gagal.'])
                ->withInput($request->only('username'));
        }
    }

    $user = User::where('email', $request->username)->first();

    $passwordOk = false;
    if ($user) {
        $stored = (string) $user->password;
        $looksHashed = (bool) preg_match('/^\$2[aby]\$\d{2}\$.+/', $stored);

        if ($looksHashed) {
            $passwordOk = Hash::check($request->password, $stored);
        } else {
            $passwordOk = hash_equals($stored, (string) $request->password);
            if ($passwordOk) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
        }
    }

    // Hanya izinkan admin / tata_usaha
    $roleMatch = $user && in_array($user->role, ['tata_usaha', 'admin']);

    if ($user && $passwordOk && $roleMatch) {
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'login' => 'Email, password, atau akses ditolak.',
    ])->withInput($request->only('username'));
})->name('login.submit');

// Dashboard Routes (Protected by auth middleware)
Route::middleware(['auth', 'role:tata_usaha,admin'])->group(function () {
    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Dashboard Utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Student Dashboard (separate from admin)
    Route::get('/student-dashboard', [App\Http\Controllers\StudentDashboardController::class, 'index'])
        ->name('student.dashboard')
        ->middleware('auth');

    // Monitoring NFC Real-Time
    Route::get('/monitoring/nfc', [MonitoringController::class, 'nfc'])->name('monitoring.nfc');
    Route::get('/monitoring/nfc-data', [MonitoringController::class, 'nfcWebData'])->name('monitoring.nfc-data');

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

    // Absensi (formerly Izin & Sakit)
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::put('/absensi/{attendance}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{attendance}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    // POST /absensi dihapus - Absensi otomatis terisi dari tap in alat NFC, notifikasi siswa sakit/izin, atau penolakan dari TU

    Route::get('/absensi/siswa', function (Request $request) {
        $student = Student::where('email', auth()->user()->email)->first();

        if (! $student) {
            return redirect()->route('dashboard')->with('error', 'Akun siswa belum terhubung dengan data siswa. Silakan hubungi admin.');
        }

        $today = Carbon::today();
        $now = Carbon::now('Asia/Jakarta');
        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('attendance_date', $today)
            ->first();

        // Generate day cards for the week
        $weekStart = Carbon::now()->locale('id')->startOfWeek(Carbon::MONDAY);
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dayCards = [];

        // Determine if this is Normatif or Produktif week
        // Starting from May 25, 2026 is Normatif week
        $normativeStart = Carbon::create(2026, 5, 25)->startOfWeek(Carbon::MONDAY);
        $weekNumber = $weekStart->diffInWeeks($normativeStart);
        $isNormative = $weekNumber % 2 === 0; // Even weeks are Normatif, odd weeks are Produktif

        for ($i = 0; $i < 5; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $att = Attendance::where('student_id', $student->id)
                ->whereDate('attendance_date', $date)
                ->first();

            // Check for existing leave request for this date
            $leaveRequest = \App\Models\LeaveRequest::where('student_id', $student->id)
                ->whereDate('request_date', $date)
                ->whereIn('status', ['pending_teacher', 'pending_admin', 'approved'])
                ->first();

            // Set room based on week type
            $room = $isNormative ? 'X TEI' : 'Lab TEI';

            $dayCards[] = [
                'name' => $days[$i],
                'date' => $date->format('d M Y'),
                'attendance' => $att,
                'leave_request' => $leaveRequest,
                'is_today' => $date->isSameDay($today),
                'is_past' => $date->lt($today),
                'room' => $room,
                'can_request' => !$att && !$leaveRequest,
            ];
        }

        return view('absensi.student', compact('student', 'attendance', 'dayCards', 'now'));
    })->name('absensi.student');

    Route::post('/absensi/siswa', function (Request $request) {
        $student = Student::where('email', auth()->user()->email)->first();

        if (! $student) {
            return redirect()->route('dashboard')->with('error', 'Akun siswa belum terhubung dengan data siswa. Silakan hubungi admin.');
        }

        $data = $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'leave_reason' => 'nullable|string|required_if:status,izin,sakit|max:500',
            'note' => 'nullable|string|max:255',
        ]);

        // If status is izin or sakit, create leave request instead of direct attendance
        if ($data['status'] === 'izin' || $data['status'] === 'sakit') {
            $leaveRequest = LeaveRequest::create([
                'student_id' => $student->id,
                'type' => $data['status'],
                'reason' => $data['leave_reason'],
                'status' => 'pending_teacher',
                'request_date' => Carbon::today()->toDateString(),
            ]);

            return back()->with('success', 'Permintaan ' . ucfirst($data['status']) . ' berhasil dikirim ke Guru untuk persetujuan.');
        }

        // For hadir or alpha, create attendance directly
        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'attendance_date' => Carbon::today()->toDateString(),
            ],
            [
                'status' => $data['status'],
                'attendance_time' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'note' => $data['note'] ?? null,
            ]
        );

        return back()->with('success', 'Absensi berhasil disimpan untuk hari ini.');
    })->name('absensi.student.store');

    // Notifikasi Guru
    Route::get('/notifications/guru-approvals', [NotificationController::class, 'teacherApprovals'])->name('notifications.guru-approvals');
    Route::get('/monitoring/guru', [NotificationController::class, 'teacherMonitoring'])->name('monitoring.guru');
    Route::patch('/notifications/guru-approvals/{leaveRequest}/approve', [NotificationController::class, 'teacherApprove'])->name('notifications.teacher-approve');
    Route::patch('/notifications/guru-approvals/{leaveRequest}/reject', [NotificationController::class, 'teacherReject'])->name('notifications.teacher-reject');

    // Notifikasi TU
    Route::get('/notifications/tu-approvals', [NotificationController::class, 'tuApprovals'])->name('notifications.tu-approvals');
    Route::patch('/notifications/tu-approvals/{leaveRequest}/approve', [NotificationController::class, 'tuApprove'])->name('notifications.tu-approve');
    Route::patch('/notifications/tu-approvals/{leaveRequest}/reject', [NotificationController::class, 'tuReject'])->name('notifications.tu-reject');

    // Jadwal Siswa
    Route::get('/jadwal-siswa', function () {
        $student = Student::where('email', auth()->user()->email)->first();

        if (! $student) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $schedules = \App\Models\Schedule::query()
            ->where('class_name', $student->class_name)
            ->with('teacher')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $schedulesByDay = $schedules->groupBy('day_of_week');
        $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $schedulesByDay = $schedulesByDay->sortKeysUsing(function ($a, $b) use ($dayOrder) {
            $posA = array_search($a, $dayOrder, true);
            $posB = array_search($b, $dayOrder, true);
            $posA = $posA === false ? 999 : $posA;
            $posB = $posB === false ? 999 : $posB;
            return $posA <=> $posB;
        });

        return view('schedules.student', compact('student', 'schedulesByDay'));
    })->name('schedules.student')->middleware('auth');

    // Laporan Absensi
    Route::get('/laporan/absensi', [ReportController::class, 'absensi'])->name('reports.absensi');
    Route::get('/laporan/absensi/download-csv', [ReportController::class, 'downloadCsv'])->name('reports.absensi.download-csv');
    Route::get('/laporan/absensi/download-pdf', [ReportController::class, 'downloadPdf'])->name('reports.absensi.download-pdf');

    // Monitoring Alat NFC
    // Route::get('/alat-nfc', [NfcDeviceController::class, 'index'])->name('devices.nfc-tools');
    // Route::post('/alat-nfc', [NfcDeviceController::class, 'store'])->name('devices.store');
    // Route::get('/alat-nfc/{device}/edit', [NfcDeviceController::class, 'edit'])->name('devices.edit');
    // Route::put('/alat-nfc/{device}', [NfcDeviceController::class, 'update'])->name('devices.update');
    // Route::delete('/alat-nfc/{device}', [NfcDeviceController::class, 'destroy'])->name('devices.destroy');

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

    Route::post('/profile/change-password', function (Request $request) {
        $request->validate([
            'password_current' => 'required|string',
            'password_new' => 'required|string|min:8|different:password_current',
            'password_confirmation' => 'required|string|same:password_new',
        ], [
            'password_current.required' => 'Password saat ini harus diisi.',
            'password_new.required' => 'Password baru harus diisi.',
            'password_new.min' => 'Password baru minimal 8 karakter.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
            'password_new.different' => 'Password baru harus berbeda dari password saat ini.',
            'password_confirmation.required' => 'Konfirmasi password harus diisi.',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->password_current, $user->password)) {
            return back()->withErrors(['password_current' => 'Password saat ini tidak sesuai.']);
        }

        // Update password
        $user->password = Hash::make($request->password_new);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    })->name('profile.change-password');
});
