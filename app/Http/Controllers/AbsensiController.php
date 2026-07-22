<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceLog;
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
        $targetDate = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();
        $targetDateString = $targetDate->toDateString();
        $isPastDate = $targetDate->isBefore(Carbon::today());
        $isToday = $targetDate->isSameDay(Carbon::today());

        $studentsQuery = Student::query();
        if ($request->filled('class')) {
            $studentsQuery->where('class_name', $request->string('class'));
        }
        if ($request->filled('search')) {
            $search = $request->string('search');
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }
        
        // Cuma ambil students kalau class atau search diisi ATAU isPastDate = true (agar alpa muncul semua)
        if ($request->filled('class') || $request->filled('search') || $isPastDate) {
            $studentsList = $studentsQuery->orderBy('class_name')->orderBy('name')->get();
        } else {
            // Jika hari ini dan gak ada filter, ambil student yang SUDAH ABSEN saja biar listnya gak penuh org "Belum"
            // (seperti default lama yg ada aja dulu)
            $studentsList = collect(); // Nanti akan diisi dari attendanceRecords aja
        }

        // Ambil data attendance di tanggal tersebut
        $attendanceRecords = Attendance::query()
            ->with('student')
            ->whereDate('attendance_date', $targetDate)
            ->get()
            ->keyBy('student_id');

        $mergedRecords = collect();

        if ($studentsList->isEmpty() && !$isPastDate) {
            // Jika hari ini dan gada filter, hanya list yg absen
            foreach ($attendanceRecords as $att) {
                if (!$att->student) continue;

                if ($request->filled('status') && $att->status !== $request->string('status')) {
                    continue;
                }

                $mergedRecords->push((object)[
                    'id' => $att->id,
                    'student_id' => $att->student_id,
                    'student' => $att->student,
                    'attendance_date' => $att->attendance_date,
                    'attendance_time' => $att->attendance_time,
                    'status' => $att->status,
                    'note' => $att->note,
                    'is_existing' => true,
                ]);
            }
        } else {
            foreach ($studentsList as $student) {
                $att = $attendanceRecords->get($student->id);
                
                // Hitung status jika tidak ada di DB
                $derivedStatus = 'belum';
                if ($isPastDate) {
                    $derivedStatus = 'alpa';
                }

                if ($request->filled('status')) {
                    $filterStatus = $request->string('status');
                    $actualStatus = $att ? $att->status : $derivedStatus;
                    // pastikan filter sesuai (contoh: cari alpa dapet juga yg belum untuk isPastDate)
                    if ($actualStatus !== $filterStatus && !($filterStatus === 'alpa' && $actualStatus === 'belum')) {
                        continue;
                    }
                }

                $mergedRecords->push((object)[
                    'id' => $att ? $att->id : 'new-' . $student->id,
                    'student_id' => $student->id,
                    'student' => $student,
                    'attendance_date' => $att ? $att->attendance_date : clone $targetDate,
                    'attendance_time' => $att ? $att->attendance_time : '-',
                    'status' => $att ? $att->status : $derivedStatus,
                    'note' => $att ? $att->note : null,
                    'is_existing' => $att ? true : false,
                ]);
            }
        }

        // Paginasi manual untuk collection
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 20;
        $records = new \Illuminate\Pagination\LengthAwarePaginator(
            $mergedRecords->forPage($page, $perPage),
            $mergedRecords->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $students = Student::orderBy('name')->get();

        return view('absensi.index', compact('records', 'students', 'targetDate'));
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

        $attendance = Attendance::create($data);

        // Log attendance creation
        AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'old_status' => null,
            'new_status' => $data['status'],
            'old_note' => null,
            'new_note' => $data['note'] ?? null,
            'changed_by_name' => auth()->user()->name ?? auth()->user()->email,
            'changed_by_role' => auth()->user()->role ?? 'admin',
        ]);

        return back()->with('success', 'Data absensi berhasil disimpan dan disinkronisasi.');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'note' => 'nullable|string',
        ]);

        // Save old values for log
        $oldStatus = $attendance->status;
        $oldNote = $attendance->note;

        $attendance->update([
            'status' => $data['status'],
            'note' => $data['note'],
        ]);

        // Log attendance update
        AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'action' => 'updated',
            'old_status' => $oldStatus,
            'new_status' => $data['status'],
            'old_note' => $oldNote,
            'new_note' => $data['note'] ?? null,
            'changed_by_name' => auth()->user()->name ?? auth()->user()->email,
            'changed_by_role' => auth()->user()->role ?? 'admin',
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
        // Log before deletion
        AttendanceLog::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'old_status' => $attendance->status,
            'new_status' => null,
            'old_note' => $attendance->note,
            'new_note' => null,
            'changed_by_name' => auth()->user()->name ?? auth()->user()->email,
            'changed_by_role' => auth()->user()->role ?? 'admin',
        ]);

        $attendance->delete();
        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    public function showLogs(Attendance $attendance)
    {
        $logs = $attendance->logs()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'old_status' => $log->old_status,
                    'new_status' => $log->new_status,
                    'old_note' => $log->old_note,
                    'new_note' => $log->new_note,
                    'changed_by_name' => $log->changed_by_name,
                    'changed_by_role' => $log->changed_by_role,
                    'created_at' => $log->created_at->format('d M Y H:i'),
                ];
            }),
        ]);
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

