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
    private $externalApiUrl = 'http://localhost/absensi_api/absen.php';

    public function index(Request $request)
    {
        $query = Attendance::query()->with('student');

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
            'status' => 'required|in:hadir,izin,sakit,alpha',
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
            'waktu' => $data['attendance_time'] ?? Carbon::now()->format('H:i:s'),
            'keterangan' => $data['note'] ?? '',
        ]);

        $data['attendance_time'] = $data['attendance_time'] ?? Carbon::now()->format('H:i:s');

        Attendance::create($data);

        return back()->with('success', 'Data absensi berhasil disimpan dan disinkronisasi.');
    }

    public function syncFromExternal(Request $request)
    {
        try {
            $response = Http::get($this->externalApiUrl, [
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
                                'attendance_time' => $item['waktu'],
                            ],
                            [
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
            Log::error('External API sync error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage());
        }
    }

    private function syncToExternalApi(array $data)
    {
        try {
            Http::post($this->externalApiUrl, array_merge($data, ['action' => 'create']));
        } catch (\Exception $e) {
            Log::error('External API push error: ' . $e->getMessage());
            // Don't fail the local operation if external API fails
        }
    }
}
