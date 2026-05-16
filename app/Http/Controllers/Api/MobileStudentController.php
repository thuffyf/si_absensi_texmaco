<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MobileStudentController extends Controller
{
    public function summary(Request $request)
    {
        $student = $this->resolveStudent($request);
        if (!$student) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        $from = $request->query('from');
        $until = $request->query('until');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->startOfMonth();
        $untilDate = $until ? Carbon::parse($until)->endOfDay() : Carbon::now()->endOfMonth();

        $records = Attendance::query()
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$fromDate->toDateString(), $untilDate->toDateString()])
            ->get();

        $counts = $records->countBy('status');
        $hadir = $counts->get('hadir', 0);
        $izin = $counts->get('izin', 0);
        $sakit = $counts->get('sakit', 0);
        $alpha = $counts->get('alpha', 0);

        return response()->json([
            'message' => 'Ringkasan absensi siswa.',
            'summary' => [
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alfa' => $alpha,
                'total' => $hadir + $izin + $sakit + $alpha,
            ],
            'period' => [
                'from' => $fromDate->toDateString(),
                'until' => $untilDate->toDateString(),
            ],
        ]);
    }

    private function resolveStudent(Request $request): ?Student
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        $hash = hash('sha256', $token);
        return Student::where('api_token', $hash)->first();
    }
}
