<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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

        $date = $request->query('date') ?: Carbon::now()->toDateString();

        $items = Attendance::query()
            ->with('student')
            ->whereDate('attendance_date', $date)
            ->whereIn('status', ['izin', 'sakit', 'alpha'])
            ->get()
            ->map(function ($attendance) {
                return [
                    'student_name' => $attendance->student?->name ?? '-',
                    'nis' => $attendance->student?->nis ?? '-',
                    'classroom' => $attendance->student?->class_name ?? '-',
                    'status' => $attendance->status === 'alpha' ? 'alfa' : $attendance->status,
                ];
            })
            ->values();

        return response()->json([
            'message' => 'Daftar siswa tidak hadir.',
            'date' => $date,
            'absences' => $items,
        ]);
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
