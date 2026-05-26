<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MobileTeacherController extends Controller
{
    public function absences(Request $request)
    {
        $teacher = $this->resolveTeacher($request);
        if (!$teacher) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        $date = Carbon::parse($request->query('date') ?: Carbon::now()->toDateString());
        $dateString = $date->toDateString();
        $dayName = $this->dayName($date);
        $selectedClass = $request->query('class_name');

        $dailySchedules = Schedule::query()
            ->where('teacher_id', $teacher->id)
            ->where('day_of_week', $dayName)
            ->orderBy('start_time')
            ->get();

        $teacherClasses = Schedule::query()
            ->where('teacher_id', $teacher->id)
            ->orderBy('class_name')
            ->pluck('class_name')
            ->unique()
            ->values();

        $scopedClasses = $selectedClass
            ? collect([$selectedClass])
            : ($dailySchedules->isNotEmpty()
                ? $dailySchedules->pluck('class_name')->unique()->values()
                : $teacherClasses);

        $records = Attendance::query()
            ->with('student')
            ->whereDate('attendance_date', $dateString)
            ->when($scopedClasses->isNotEmpty(), function ($query) use ($scopedClasses) {
                $query->whereHas('student', function ($studentQuery) use ($scopedClasses) {
                    $studentQuery->whereIn('class_name', $scopedClasses);
                });
            })
            ->orderBy('attendance_time')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'student_name' => $attendance->student?->name ?? '-',
                    'nis' => $attendance->student?->nis ?? '-',
                    'classroom' => $attendance->student?->class_name ?? '-',
                    'status' => $attendance->status === 'alpha' ? 'alfa' : $attendance->status,
                    'time' => $attendance->attendance_time,
                    'note' => $attendance->note,
                ];
            })
            ->values();

        $recordedNis = $records->pluck('nis')->filter()->values();
        $notRecorded = collect();

        if ($scopedClasses->isNotEmpty()) {
            $notRecorded = Student::query()
                ->whereIn('class_name', $scopedClasses)
                ->when($recordedNis->isNotEmpty(), fn ($query) => $query->whereNotIn('nis', $recordedNis))
                ->orderBy('class_name')
                ->orderBy('name')
                ->get()
                ->map(fn ($student) => [
                    'id' => null,
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
        $absences = $records->whereIn('status', ['izin', 'sakit', 'alfa'])->values();
        $allItems = $records->concat($notRecorded)->values();

        return response()->json([
            'message' => 'Data kehadiran siswa.',
            'date' => $dateString,
            'day_name' => $dayName,
            'selected_class' => $selectedClass,
            'classes' => $teacherClasses,
            'schedules' => $dailySchedules->map(fn ($schedule) => [
                'class_name' => $schedule->class_name,
                'subject' => $schedule->subject,
                'start_time' => $schedule->start_time?->format('H:i'),
                'end_time' => $schedule->end_time?->format('H:i'),
                'status' => $schedule->status,
            ])->values(),
            'summary' => [
                'total_students' => $allItems->count(),
                'hadir' => $present->count(),
                'izin' => $records->where('status', 'izin')->count(),
                'sakit' => $records->where('status', 'sakit')->count(),
                'alfa' => $records->where('status', 'alfa')->count(),
                'belum_absen' => $notRecorded->count(),
            ],
            'present' => $present,
            'absences' => $absences,
            'not_recorded' => $notRecorded,
            'all' => $allItems,
        ]);
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

    private function resolveTeacher(Request $request): ?Teacher
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        $hash = hash('sha256', $token);
        return Teacher::where('api_token', $hash)->first();
    }
}
