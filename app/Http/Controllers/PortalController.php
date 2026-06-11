<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function home(): RedirectResponse
    {
        return redirect()->route($this->portalRouteForRole(Auth::user()->role));
    }

    public function studentDashboard(): View
    {
        $student = $this->currentStudent();
        $period = $this->studentPeriod();
        $summary = $this->studentSummary($student, $period['from'], $period['until']);
        $todayName = $this->dayName(Carbon::now('Asia/Jakarta'));

        $latestRecords = Attendance::query()
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$period['from']->toDateString(), $period['until']->toDateString()])
            ->orderByDesc('attendance_date')
            ->orderByDesc('attendance_time')
            ->limit(4)
            ->get();

        $latestRequests = LeaveRequest::query()
            ->where('student_id', $student->id)
            ->orderByDesc('requested_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $todaySchedules = Schedule::query()
            ->where('class_name', $student->class_name)
            ->where('day_of_week', $todayName)
            ->with('teacher')
            ->orderBy('start_time')
            ->get();

        return view('portal.student.dashboard', [
            'student' => $student,
            'summary' => $summary,
            'latestRecords' => $latestRecords,
            'latestRequests' => $latestRequests,
            'todaySchedules' => $todaySchedules,
            'todayName' => $todayName,
            'periodLabel' => $period['label'],
        ]);
    }

    public function studentHistory(): View
    {
        $student = $this->currentStudent();
        $period = $this->studentPeriod();

        $records = Attendance::query()
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$period['from']->toDateString(), $period['until']->toDateString()])
            ->orderByDesc('attendance_date')
            ->orderByDesc('attendance_time')
            ->get();

        return view('portal.student.history', [
            'student' => $student,
            'records' => $records,
            'periodLabel' => $period['label'],
        ]);
    }

    public function studentLeave(): View
    {
        $student = $this->currentStudent();

        $requests = LeaveRequest::query()
            ->where('student_id', $student->id)
            ->orderByDesc('requested_at')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        return view('portal.student.leave', [
            'student' => $student,
            'requests' => $requests,
            'today' => Carbon::today('Asia/Jakarta')->toDateString(),
        ]);
    }

    public function storeStudentLeave(Request $request): RedirectResponse
    {
        $student = $this->currentStudent();

        $data = $request->validate([
            'type' => 'required|in:izin,sakit',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'type.required' => 'Jenis pengajuan wajib dipilih.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'reason.required' => 'Alasan wajib diisi.',
        ]);

        $startDate = Carbon::parse($data['start_date'])->toDateString();
        $endDate = filled($data['end_date'] ?? null)
            ? Carbon::parse($data['end_date'])->toDateString()
            : $startDate;

        $existingAttendance = Attendance::query()
            ->where('student_id', $student->id)
            ->whereDate('attendance_date', $startDate)
            ->first();

        if ($existingAttendance) {
            return back()->withErrors([
                'leave' => 'Absensi untuk tanggal ini sudah ada. Pengajuan tidak bisa dibuat.',
            ])->withInput();
        }

        $existingRequest = LeaveRequest::query()
            ->where('student_id', $student->id)
            ->whereDate('start_date', $startDate)
            ->whereIn('status', ['pending_teacher', 'pending_admin', 'approved'])
            ->first();

        if ($existingRequest) {
            return back()->withErrors([
                'leave' => 'Pengajuan untuk tanggal ini masih aktif atau sudah disetujui.',
            ])->withInput();
        }

        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('leave-requests', 'public');
        }

        LeaveRequest::create([
            'student_id' => $student->id,
            'type' => $data['type'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'request_date' => $startDate,
            'reason' => $data['reason'],
            'status' => 'pending_admin',
            'requested_at' => Carbon::now('Asia/Jakarta'),
            'photo' => $photoPath,
        ]);

        return redirect()
            ->route('portal.student.leave')
            ->with('success', 'Pengajuan berhasil dikirim ke TU.');
    }

    public function studentProfile(): View
    {
        return view('portal.student.profile', [
            'student' => $this->currentStudent(),
        ]);
    }

    public function updateStudentPhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'photo.required' => 'Pilih foto terlebih dahulu.',
            'photo.image'    => 'File harus berupa gambar.',
            'photo.mimes'    => 'Format foto harus JPG, PNG, atau WEBP.',
            'photo.max'      => 'Ukuran foto maksimal 3 MB.',
        ]);

        $student = $this->currentStudent();
        $disk    = $this->storageDisk();

        // Hapus foto lama kalau ada
        if ($student->photo_path) {
            Storage::disk($disk)->delete($student->photo_path);
        }

        $path = $request->file('photo')->store('profile-photos/students', $disk);
        $student->update(['photo_path' => $path]);
        Auth::user()?->forceFill(['photo' => $path])->save();

        return redirect()->route('portal.student.profile')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function deleteStudentPhoto()
    {
        $student = $this->currentStudent();
        $this->removeProfilePhoto($student->photo_path);

        $student->update(['photo_path' => null]);
        Auth::user()?->forceFill(['photo' => null])->save();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('portal.student.profile')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    public function updateStudentPassword(Request $request): RedirectResponse
    {
        $student = $this->currentStudent();

        // Siswa login dengan tanggal lahir, bukan password User — kita update User password
        $request->validate([
            'new_password'              => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string',
        ], [
            'new_password.required'  => 'Password baru wajib diisi.',
            'new_password.min'       => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();
        $user->forceFill(['password' => Hash::make($request->new_password)])->save();

        return redirect()->route('portal.student.profile')
            ->with('success', 'Password berhasil diperbarui.');
    }

    public function teacherProfile(): View
    {
        return view('portal.teacher.profile', [
            'teacher' => $this->currentTeacher(),
        ]);
    }

    public function updateTeacherPhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'photo.required' => 'Pilih foto terlebih dahulu.',
            'photo.image'    => 'File harus berupa gambar.',
            'photo.mimes'    => 'Format foto harus JPG, PNG, atau WEBP.',
            'photo.max'      => 'Ukuran foto maksimal 3 MB.',
        ]);

        $teacher = $this->currentTeacher();
        $disk    = $this->storageDisk();

        if ($teacher->photo_path) {
            Storage::disk($disk)->delete($teacher->photo_path);
        }

        $path = $request->file('photo')->store('profile-photos/teachers', $disk);
        $teacher->update(['photo_path' => $path]);
        Auth::user()?->forceFill(['photo' => $path])->save();

        return redirect()->route('portal.teacher.profile')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function deleteTeacherPhoto()
    {
        $teacher = $this->currentTeacher();
        $this->removeProfilePhoto($teacher->photo_path);

        $teacher->update(['photo_path' => null]);
        Auth::user()?->forceFill(['photo' => null])->save();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('portal.teacher.profile')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    public function updateTeacherPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'new_password'              => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string',
        ], [
            'new_password.required'  => 'Password baru wajib diisi.',
            'new_password.min'       => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();
        $user->forceFill(['password' => Hash::make($request->new_password)])->save();

        return redirect()->route('portal.teacher.profile')
            ->with('success', 'Password berhasil diperbarui.');
    }

    public function studentSchedule(): View
    {
        $student = $this->currentStudent();

        $schedules = Schedule::query()
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

        return view('portal.student.schedule', [
            'student' => $student,
            'schedulesByDay' => $schedulesByDay,
            'todayName' => $this->dayName(Carbon::now('Asia/Jakarta')),
        ]);
    }

    public function teacherAttendance(Request $request): View
    {
        $teacher = $this->currentTeacher();
        $date = Carbon::parse($request->query('date') ?: Carbon::now('Asia/Jakarta')->toDateString());
        $selectedClass = (string) $request->query('class_name', '');
        $selectedScheduleId = (string) $request->query('schedule_id', '');
        $selectedView = (string) $request->query('view', 'hadir');

        if (!in_array($selectedView, ['hadir', 'tidak_hadir', 'belum_absen'], true)) {
            $selectedView = 'hadir';
        }

        $dailySchedules = $teacher->schedules()
            ->where('day_of_week', $this->dayName($date))
            ->orderBy('start_time')
            ->get();

        $teacherClasses = $dailySchedules->pluck('class_name')->unique()->values();

        $scopedSchedules = $dailySchedules;
        if ($selectedScheduleId !== '') {
            $scopedSchedules = $scopedSchedules
                ->where('id', (int) $selectedScheduleId)
                ->values();
        }

        if ($selectedClass !== '') {
            $scopedSchedules = $scopedSchedules
                ->where('class_name', $selectedClass)
                ->values();
        }

        $scopedClasses = $scopedSchedules->pluck('class_name')->unique()->values();

        $records = collect();
        if ($scopedClasses->isNotEmpty()) {
            $records = Attendance::query()
                ->with('student')
                ->whereDate('attendance_date', $date->toDateString())
                ->whereHas('student', function ($query) use ($scopedClasses) {
                    $query->whereIn('class_name', $scopedClasses);
                })
                ->orderBy('attendance_time')
                ->get()
                ->map(function (Attendance $attendance) {
                    return [
                        'student_name' => $attendance->student?->name ?? '-',
                        'nis' => $attendance->student?->nis ?? '-',
                        'classroom' => $attendance->student?->class_name ?? '-',
                        'status' => $attendance->status === 'alpha' ? 'alpa' : $attendance->status,
                        'time' => $attendance->attendance_time,
                        'note' => $attendance->note,
                    ];
                })
                ->values();
        }

        $recordedNis = $records->pluck('nis')->filter()->values();
        $notRecorded = collect();

        if ($scopedClasses->isNotEmpty()) {
            $notRecorded = Student::query()
                ->whereIn('class_name', $scopedClasses)
                ->when($recordedNis->isNotEmpty(), fn ($query) => $query->whereNotIn('nis', $recordedNis))
                ->orderBy('class_name')
                ->orderBy('name')
                ->get()
                ->map(fn (Student $student) => [
                    'student_name' => $student->name,
                    'nis' => $student->nis,
                    'classroom' => $student->class_name,
                    'status' => 'belum_absen',
                    'time' => null,
                    'note' => null,
                ])
                ->values();
        }

        $present = $records->where('status', 'hadir')->values();
        $absences = $records->whereIn('status', ['izin', 'sakit', 'alpa'])->values();

        return view('portal.teacher.attendance', [
            'teacher' => $teacher,
            'date' => $date->toDateString(),
            'dayName' => $this->dayName($date),
            'selectedClass' => $selectedClass,
            'selectedScheduleId' => $selectedScheduleId,
            'selectedView' => $selectedView,
            'classes' => $teacherClasses,
            'schedules' => $dailySchedules,
            'summary' => [
                'total_students' => $records->count() + $notRecorded->count(),
                'hadir' => $present->count(),
                'izin' => $records->where('status', 'izin')->count(),
                'sakit' => $records->where('status', 'sakit')->count(),
                'alpa' => $records->where('status', 'alpa')->count(),
                'belum_absen' => $notRecorded->count(),
            ],
            'presentItems' => $present,
            'absenceItems' => $absences,
            'notRecordedItems' => $notRecorded,
        ]);
    }

    public function updateTeacherAttendance(Request $request): RedirectResponse
    {
        $teacher = $this->currentTeacher();

        $data = $request->validate([
            'nis' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'note' => 'nullable|string|max:255',
        ]);

        $student = Student::query()->where('nis', $data['nis'])->first();
        if (! $student) {
            return back()->withErrors([
                'attendance' => 'Siswa tidak ditemukan.',
            ]);
        }

        $date = Carbon::parse($data['date']);
        $allowedClassrooms = $teacher->schedules()
            ->where('day_of_week', $this->dayName($date))
            ->pluck('class_name')
            ->unique()
            ->all();

        if (! in_array($student->class_name, $allowedClassrooms, true)) {
            abort(403, 'Akses ke data absensi siswa ini ditolak.');
        }

        Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'attendance_date' => $date->toDateString(),
            ],
            [
                'status' => $data['status'],
                'attendance_time' => $data['status'] === 'hadir'
                    ? Carbon::now('Asia/Jakarta')->format('H:i:s')
                    : '00:00:00',
                'note' => $data['note'] ?: null,
            ]
        );

        return redirect()
            ->route('portal.teacher.attendance', $request->only(['date', 'class_name', 'schedule_id', 'view']))
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    private function currentStudent(): Student
    {
        $student = Student::query()->where('email', Auth::user()->email)->first();
        abort_if(! $student, 404, 'Data siswa tidak ditemukan.');

        return $student;
    }

    private function currentTeacher(): Teacher
    {
        $teacher = Teacher::query()->where('email', Auth::user()->email)->first();
        abort_if(! $teacher, 404, 'Data guru tidak ditemukan.');

        return $teacher;
    }

    private function studentPeriod(): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $from = $now->copy()->startOfMonth();
        $until = $now->copy()->endOfMonth();

        return [
            'from' => $from,
            'until' => $until,
            'label' => $from->toDateString() . ' sampai ' . $until->toDateString(),
        ];
    }

    private function studentSummary(Student $student, Carbon $from, Carbon $until): array
    {
        $records = Attendance::query()
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$from->toDateString(), $until->toDateString()])
            ->get();

        $counts = $records->countBy('status');
        $hadir = $counts->get('hadir', 0);
        $izin = $counts->get('izin', 0);
        $sakit = $counts->get('sakit', 0);
        $alpa = $counts->get('alpa', 0) + $counts->get('alpha', 0);

        return [
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpa' => $alpa,
            'total' => $hadir + $izin + $sakit + $alpa,
        ];
    }

    private function dayName(Carbon $date): string
    {
        return match ((int) $date->dayOfWeekIso) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            default => 'Minggu',
        };
    }

    private function portalRouteForRole(?string $role): string
    {
        return match ($role) {
            'siswa' => 'portal.student.dashboard',
            'guru' => 'portal.teacher.attendance',
            default => 'dashboard',
        };
    }

    /**
     * Pilih disk storage yang tepat.
     * Jika STORAGE_PUBLIC_PATH diset di .env, pakai disk 'public_web'
     * (yang menyimpan langsung ke path tersebut — cocok untuk cPanel shared hosting).
     * Jika tidak, pakai disk 'public' standar Laravel.
     */
    private function storageDisk(): string
    {
        return env('STORAGE_PUBLIC_PATH') ? 'public_web' : 'public';
    }

    private function removeProfilePhoto(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk($this->storageDisk())->delete($path);
    }
}
