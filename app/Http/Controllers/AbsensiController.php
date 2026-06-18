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
    private string $externalApiUrl;

    public function __construct()
    {
        $this->externalApiUrl = (string) config('services.absensi.external_api_url');
    }

    public function index(Request $request)
    {
        $query = Attendance::query()->with('student');

        // Default filter for today's attendance only
        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        } else {
            $query->whereDate('attendance_date', Carbon::today());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('class')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_name', $request->string('class'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nis', 'like', '%' . $search . '%');
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
