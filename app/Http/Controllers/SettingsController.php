<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Homeroom;
use App\Models\LeaveRequest;
use App\Models\NfcDevice;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentDevice;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();

        return view('settings.index', [
            'settings' => $settings->data ?? [],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'school_name' => 'nullable|string|max:150',
            'npsn' => 'nullable|string|max:50',
            'school_address' => 'nullable|string|max:255',
            'school_email' => 'nullable|email|max:255',
            'school_phone' => 'nullable|string|max:50',
            'entry_time' => 'nullable|date_format:H:i',
            'exit_time' => 'nullable|date_format:H:i',
            'late_tolerance' => 'nullable|integer|min:0|max:240',
            'alpha_threshold' => 'nullable|integer|min:0|max:365',
            'timezone' => 'nullable|string|max:60',
            'language' => 'nullable|string|max:40',
            'theme' => 'nullable|in:dark,light',
            'font_size' => 'nullable|in:kecil,normal,besar',
            'admin_name' => 'nullable|string|max:150',
            'admin_phone' => 'nullable|string|max:50',
            'admin_emails' => 'nullable|array|max:5',
            'admin_emails.*' => 'nullable|email|max:255',
        ]);

        $settings = $this->getSettings();

        $normalized = [
            'school_name' => $data['school_name'] ?? null,
            'npsn' => $data['npsn'] ?? null,
            'school_address' => $data['school_address'] ?? null,
            'school_email' => $data['school_email'] ?? null,
            'school_phone' => $data['school_phone'] ?? null,
            'entry_time' => $data['entry_time'] ?? null,
            'exit_time' => $data['exit_time'] ?? null,
            'late_tolerance' => $data['late_tolerance'] ?? null,
            'alpha_threshold' => $data['alpha_threshold'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            'language' => $data['language'] ?? null,
            'theme' => $data['theme'] ?? null,
            'font_size' => $data['font_size'] ?? null,
            'notify_realtime' => $request->boolean('notify_realtime'),
            'notify_device_offline' => $request->boolean('notify_device_offline'),
            'notify_email' => $request->boolean('notify_email'),
            'notify_leave_pending' => $request->boolean('notify_leave_pending'),
            'ui_animations' => $request->boolean('ui_animations'),
            'auto_refresh' => $request->boolean('auto_refresh'),
            'admin_name' => $data['admin_name'] ?? null,
            'admin_phone' => $data['admin_phone'] ?? null,
            'admin_emails' => $this->normalizeEmails($data['admin_emails'] ?? []),
        ];

        $settings->data = array_merge($settings->data ?? [], array_filter($normalized, function ($value) {
            return $value !== null;
        }));
        $settings->save();

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function resetDefaults()
    {
        $settings = $this->getSettings();
        $settings->data = $this->defaults();
        $settings->save();

        return back()->with('success', 'Pengaturan direset ke default.');
    }

    public function export()
    {
        $payload = [
            'generated_at' => now()->toDateTimeString(),
            'students' => Student::all(),
            'teachers' => Teacher::all(),
            'schedules' => Schedule::all(),
            'attendances' => Attendance::all(),
            'leave_requests' => LeaveRequest::all(),
            'nfc_devices' => NfcDevice::all(),
            'student_devices' => StudentDevice::all(),
            'homerooms' => Homeroom::all(),
            'settings' => $this->getSettings()->data ?? [],
        ];

        $filename = 'sitexa-export-' . now()->format('Ymd_His') . '.json';

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename);
    }

    public function cleanup()
    {
        $cutoff = Carbon::now()->subYear()->toDateString();

        $attendanceDeleted = Attendance::where('attendance_date', '<', $cutoff)->delete();
        $leaveDeleted = LeaveRequest::where('start_date', '<', $cutoff)->delete();

        return back()->with('success', "Data lama dihapus. Absensi: {$attendanceDeleted}, Izin/Sakit: {$leaveDeleted}.");
    }

    public function resetData(Request $request)
    {
        if ($request->input('confirm_reset') !== 'RESET') {
            return back()->withErrors(['confirm_reset' => 'Ketik RESET untuk konfirmasi.']);
        }

        Schema::disableForeignKeyConstraints();

        DB::table('attendances')->truncate();
        DB::table('leave_requests')->truncate();
        DB::table('student_devices')->truncate();
        DB::table('schedules')->truncate();
        DB::table('nfc_devices')->truncate();
        DB::table('homerooms')->truncate();
        DB::table('students')->truncate();
        DB::table('teachers')->truncate();

        Schema::enableForeignKeyConstraints();

        return back()->with('success', 'Semua data utama berhasil direset.');
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'student_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $path = $request->file('student_file')->getRealPath();
        if (!$path) {
            return back()->withErrors(['student_file' => 'File tidak valid.']);
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->withErrors(['student_file' => 'Tidak bisa membaca file.']);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['student_file' => 'Header CSV tidak ditemukan.']);
        }

        $map = $this->mapHeaders($header);
        $settings = $this->getSettings();
        $schoolEmail = (string) ($settings->data['school_email'] ?? '');
        $domain = 'texmaco.sch.id';
        if ($schoolEmail !== '' && str_contains($schoolEmail, '@')) {
            $domain = substr($schoolEmail, strpos($schoolEmail, '@') + 1);
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $nis = $this->csvValue($row, $map, 'nis');
            $name = $this->csvValue($row, $map, 'name');

            if ($nis === '' || $name === '') {
                $skipped++;
                continue;
            }

            $email = strtolower($this->csvValue($row, $map, 'email'));
            if ($email === '') {
                $email = strtolower($nis . '@' . $domain);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            $duplicateEmail = Student::where('email', $email)
                ->where('nis', '!=', $nis)
                ->exists();
            if ($duplicateEmail) {
                $skipped++;
                continue;
            }

            $payload = [
                'nis' => $nis,
                'name' => $name,
                'class_name' => $this->csvValue($row, $map, 'class_name') ?: 'X',
                'major' => $this->csvValue($row, $map, 'major') ?: 'Teknik Elektronika Industri',
                'email' => $email,
                'username' => $this->csvValue($row, $map, 'username') ?: null,
                'phone' => $this->csvValue($row, $map, 'phone') ?: null,
                'uid_kartu' => $this->csvValue($row, $map, 'uid_kartu') ?: null,
                'status' => $this->enumCsvValue($row, $map, 'status', ['aktif', 'tidak_aktif', 'lulus'], 'aktif'),
                'nfc_type' => $this->enumCsvValue($row, $map, 'nfc_type', ['kartu', 'handphone', 'belum_terdaftar'], 'belum_terdaftar'),
            ];

            $dob = $this->csvValue($row, $map, 'date_of_birth');
            if ($dob !== '') {
                try {
                    $payload['date_of_birth'] = Carbon::parse($dob)->toDateString();
                } catch (\Throwable $e) {
                    $payload['date_of_birth'] = null;
                }
            }

            $password = $this->csvValue($row, $map, 'password');
            if ($password !== '') {
                $payload['password'] = Hash::make($password);
            }

            $payload = array_filter($payload, function ($value) {
                return $value !== null && $value !== '';
            });

            $student = Student::updateOrCreate(['nis' => $nis], $payload);
            if ($student->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        fclose($handle);

        return back()->with('success', "Import siswa selesai. Baru: {$created}, Update: {$updated}, Lewati: {$skipped}.");
    }

    private function getSettings(): Setting
    {
        $defaults = $this->defaults();
        $settings = Setting::first();

        if (!$settings) {
            return Setting::create(['data' => $defaults]);
        }

        $data = array_merge($defaults, $settings->data ?? []);
        $settings->data = $data;

        if ($settings->isDirty('data')) {
            $settings->save();
        }

        return $settings;
    }

    private function defaults(): array
    {
        return [
            'school_name' => 'Texmaco School',
            'npsn' => '20504001',
            'school_address' => 'Jl. Pendidikan No. 123, Kota',
            'school_email' => 'info@texmaco.sch.id',
            'school_phone' => '+62-274-123456',
            'entry_time' => '07:00',
            'exit_time' => '14:30',
            'late_tolerance' => 15,
            'alpha_threshold' => 3,
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
            'notify_realtime' => true,
            'notify_device_offline' => true,
            'notify_email' => false,
            'notify_leave_pending' => true,
            'theme' => 'dark',
            'font_size' => 'normal',
            'ui_animations' => true,
            'auto_refresh' => true,
            'admin_name' => 'Admin Tata Usaha',
            'admin_emails' => [
                'admin@texmaco.sch.id',
                'admin2@texmaco.sch.id',
                'admin3@texmaco.sch.id',
                'admin4@texmaco.sch.id',
                'admin5@texmaco.sch.id',
            ],
            'admin_phone' => '+62-812-3456789',
        ];
    }

    private function normalizeEmails(array $emails): array
    {
        return collect($emails)
            ->filter(fn ($email) => is_string($email) && $email !== '')
            ->values()
            ->all();
    }

    private function mapHeaders(array $header): array
    {
        $map = [];
        foreach ($header as $index => $column) {
            $key = strtolower(trim((string) $column));
            $map[$key] = $index;
        }

        return $map;
    }

    private function csvValue(array $row, array $map, string $key): string
    {
        if (!array_key_exists($key, $map)) {
            return '';
        }

        $index = $map[$key];
        return isset($row[$index]) ? trim((string) $row[$index]) : '';
    }

    private function enumCsvValue(array $row, array $map, string $key, array $allowed, string $default): string
    {
        $value = $this->csvValue($row, $map, $key);

        return in_array($value, $allowed, true) ? $value : $default;
    }
}
