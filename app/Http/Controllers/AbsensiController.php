<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
<<<<<<< HEAD
    private string $externalApiUrl;

    public function __construct()
    {
        $this->externalApiUrl = (string) config('services.absensi.external_api_url');
=======
    private $externalApiUrl;

    public function __construct()
    {
        $this->externalApiUrl = env('EXTERNAL_ATTENDANCE_API_URL', 'http://localhost/absensi_api/absen.php');
>>>>>>> 8b68358 (nfc)
    }

    public function index(Request $request)
    {
        $query = Attendance::query()->with('student');

        // Default filter for today's attendance only
        if (!$request->filled('date')) {
            $query->whereDate('attendance_date', Carbon::today());
        } elseif ($request->date === 'all') {
            // Show all records if explicitly requested
        } else {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('class')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_name', $request->string('class'));
            });
        }

        $records = $query->orderByDesc('attendance_date')->orderByDesc('attendance_time')->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get();

        return view('absensi.index', compact('records', 'students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'attendance_date' => 'required|date',
            'attendance_time' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $student = Student::find($data['student_id']);

        // Sync with external API
        $this->syncToExternalApi([
            'nis' => $student->nis,
            'nama' => $student->name,
            'kelas' => $student->class_name,
            'jurusan' => $student->major,
            'status' => $data['status'],
            'tanggal' => $data['attendance_date'],
            'waktu' => $data['attendance_time'] ?? Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'keterangan' => $data['note'] ?? '',
        ]);

        $data['attendance_time'] = $data['attendance_time'] ?? Carbon::now('Asia/Jakarta')->format('H:i:s');

        Attendance::create($data);

        return back()->with('success', 'Data absensi berhasil disimpan dan disinkronisasi.');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'note' => 'nullable|string',
        ]);

        $attendance->update([
            'status' => $data['status'],
            'note' => $data['note'],
        ]);

        $student = $attendance->student;
        if ($student) {
            $this->syncToExternalApi([
                'nis' => $student->nis,
                'nama' => $student->name,
                'kelas' => $student->class_name,
                'jurusan' => $student->major,
                'status' => $data['status'],
                'tanggal' => $attendance->attendance_date->toDateString(),
                'waktu' => $attendance->attendance_time,
                'keterangan' => $data['note'] ?? '',
            ]);
        }

        return back()->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    public function syncFromExternal(Request $request)
    {
        if ($this->externalApiUrl === '') {
            return back()->with('error', 'Konfigurasi API eksternal belum diatur.');
        }

        try {
            $response = Http::timeout(10)->retry(2, 250)->get($this->externalApiUrl, [
                'action' => 'get_all',
                'start_date' => $request->query('start_date'),
                'end_date' => $request->query('end_date'),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                foreach ($data['data'] ?? [] as $item) {
                    $student = Student::where('nis', $item['nis'])->first();

                    if ($student) {
                        Attendance::updateOrCreate(
                            [
                                'student_id' => $student->id,
                                'attendance_date' => $item['tanggal'],
                            ],
                            [
                                'attendance_time' => $item['waktu'],
                                'status' => $item['status'],
                                'note' => $item['keterangan'] ?? null,
                            ]
                        );
                    }
                }

                return back()->with('success', 'Sinkronisasi dari API eksternal berhasil.');
            }

            return back()->with('error', 'Gagal menghubungi API eksternal.');
        } catch (\Exception $e) {
            Log::error('External API sync error', ['exception' => $e]);
            return back()->with('error', 'Terjadi kesalahan saat sinkronisasi.');
        }
    }

    private function syncToExternalApi(array $data)
    {
        if ($this->externalApiUrl === '') {
            return;
        }

        try {
            $response = Http::timeout(10)->retry(2, 250)->post($this->externalApiUrl, array_merge($data, ['action' => 'create']));

            if (!$response->successful()) {
                Log::warning('External API push failed', [
                    'status' => $response->status(),
                    'body' => mb_substr((string) $response->body(), 0, 500),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('External API push error', ['exception' => $e]);
            // Don't fail the local operation if external API fails
        }
    }
}
