<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\NfcDevice;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // If user is a student, show student dashboard
        if ($user->role === 'siswa') {
            return $this->studentDashboard();
        }

        // If user is a teacher, show teacher dashboard
        if ($user->role === 'guru') {
            return $this->teacherDashboard();
        }

        // Otherwise show admin dashboard
        return $this->adminDashboard();
    }

    private function teacherDashboard()
    {
        $pendingCount = LeaveRequest::query()
            ->where('status', 'pending_teacher')
            ->count();

        $approvedCount = LeaveRequest::query()
            ->where('status', 'approved')
            ->count();

        $rejectedCount = LeaveRequest::query()
            ->where('status', 'rejected')
            ->count();

        $totalCount = LeaveRequest::query()->count();
        $approvedRate = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100, 1) : 0;

        // All leave requests with photos (the monitoring recap)
        $allRequests = LeaveRequest::query()
            ->with('student')
            ->orderByDesc('requested_at')
            ->take(10)
            ->get();

        // Teacher's own activities (approved or rejected by teacher)
        $teacherActivities = LeaveRequest::query()
            ->with('student')
            ->whereIn('status', ['approved', 'rejected'])
            ->whereNotNull('responded_at')
            ->orderByDesc('responded_at')
            ->take(10)
            ->get();

        return view('dashboard.teacher', compact(
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount',
            'approvedRate',
            'allRequests',
            'teacherActivities'
        ));
    }

    private function studentDashboard()
    {
        $student = Student::where('email', Auth::user()->email)->first();

        if (!$student) {
            return redirect()->route('login')->with('error', 'Akun siswa belum terhubung dengan data siswa.');
        }

        $today = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        // Get today's attendance
        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('attendance_date', $today)
            ->first();

        // Check if student hasn't tapped in today
        $hasNotTappedIn = !$attendance || $attendance->attendance_time === '00:00:00';

        // Get leave requests for this student
        $leaveRequests = LeaveRequest::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get pending leave requests (waiting for teacher or TU approval)
        $pendingRequests = LeaveRequest::where('student_id', $student->id)
            ->whereIn('status', ['pending_teacher', 'pending_tu'])
            ->count();

        // Get rejected leave requests (TU rejection)
        $rejectedRequests = LeaveRequest::where('student_id', $student->id)
            ->where('status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        // Get attendance history for this week
        $weekStart = Carbon::now('Asia/Jakarta')->locale('id')->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(4);
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $weeklyAttendance = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $att = Attendance::where('student_id', $student->id)
                ->whereDate('attendance_date', $date)
                ->first();

            $weeklyAttendance[] = [
                'day' => $days[$i],
                'date' => $date->format('d/m'),
                'status' => $att ? $att->status : null,
                'time' => $att ? $att->attendance_time : null,
            ];
        }

        // Calculate attendance stats
        $totalPresent = Attendance::where('student_id', $student->id)
            ->where('status', 'hadir')
            ->count();
        $totalAbsent = Attendance::where('student_id', $student->id)
            ->whereIn('status', ['izin', 'sakit', 'alpa'])
            ->count();
        $totalRecords = $totalPresent + $totalAbsent;
        $attendanceRate = $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100, 1) : 0;

        return view('dashboard.student', compact(
            'student',
            'attendance',
            'hasNotTappedIn',
            'leaveRequests',
            'pendingRequests',
            'rejectedRequests',
            'weeklyAttendance',
            'totalPresent',
            'totalAbsent',
            'attendanceRate',
            'now'
        ));
    }

    private function adminDashboard()
    {
        $targetMajor = 'TEI';

        $students = Student::query()
            ->where('major', $targetMajor)
            ->get();

        $totalStudents = $students->count();
        $today = Carbon::today('Asia/Jakarta');

        $todayAttendances = Attendance::query()
            ->whereDate('attendance_date', $today)
            ->whereNotNull('attendance_time')
            ->where('attendance_time', '!=', '00:00:00')
            ->whereHas('student', function ($query) use ($targetMajor) {
                $query->where('major', $targetMajor);
            })
            ->with('student')
            ->orderBy('attendance_time', 'desc')
            ->get();

        $presentToday = $todayAttendances->count();
        $absentToday = max($totalStudents - $presentToday, 0);
        $attendancePercentage = $totalStudents > 0 ? round(($presentToday / $totalStudents) * 100, 1) : 0;
        $recentTapIns = $todayAttendances->take(10);

        $weekStart = Carbon::now('Asia/Jakarta')->locale('id')->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(4);
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $weeklyData = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $count = Attendance::query()
                ->whereDate('attendance_date', $date)
                ->whereHas('student', function ($query) use ($targetMajor) {
                    $query->where('major', $targetMajor);
                })
                ->count();

            $weeklyData[] = [
                'day' => $days[$i],
                'date' => $date->format('d'),
                'count' => $count,
            ];
        }

        $counts = array_column($weeklyData, 'count');
        $avgAttendance = count($counts) > 0 ? round(array_sum($counts) / count($counts), 1) : 0;
        $maxAttendance = count($counts) > 0 ? max($counts) : 0;
        $maxDay = count($counts) > 0 ? $weeklyData[array_search($maxAttendance, $counts, true)]['day'] : '-';
        $minAttendance = count($counts) > 0 ? min($counts) : 0;

        $chartWidth = 520;
        $chartHeight = 200;
        $paddingLeft = 40;
        $paddingRight = 40;
        $paddingTop = 20;
        $paddingBottom = 40;
        $maxVal = max($maxAttendance, 35);
        $points = [];

        $xStep = count($weeklyData) > 1
            ? ($chartWidth - $paddingLeft - $paddingRight) / (count($weeklyData) - 1)
            : 0;

        foreach ($weeklyData as $index => $data) {
            $x = $paddingLeft + ($index * $xStep);
            $y = $paddingTop + (($maxVal - $data['count']) / $maxVal) * ($chartHeight - $paddingTop - $paddingBottom);
            $points[] = [
                'x' => round($x, 1),
                'y' => round($y, 1),
                'label' => $data['day'],
                'val' => $data['count'],
            ];
        }

        $lastWeekStart = $weekStart->copy()->subWeek();
        $lastWeekEnd = $weekEnd->copy()->subWeek();
        $currentTotal = array_sum($counts);
        $lastTotal = Attendance::query()
            ->whereBetween('attendance_date', [$lastWeekStart->toDateString(), $lastWeekEnd->toDateString()])
            ->whereHas('student', function ($query) use ($targetMajor) {
                $query->where('major', $targetMajor);
            })
            ->count();

        $trendPercent = $lastTotal > 0 ? round((($currentTotal - $lastTotal) / $lastTotal) * 100) : null;
        $trendLabel = $trendPercent !== null ? (($trendPercent >= 0 ? '+' : '') . $trendPercent . '%') : '—';
        $trendText = $trendPercent === null
            ? 'Belum ada pembanding'
            : ($trendPercent >= 0 ? 'Volume naik' : 'Volume turun');

        $todayCount = $presentToday;
        $todayPercent = $attendancePercentage;
        $rangeLabel = $minAttendance . '–' . $maxAttendance;
        $summaryText = $maxVal > 0
            ? "Puncak minggu ini di {$maxDay} dengan {$maxAttendance} tap in."
            : 'Belum ada data tap in minggu ini.';

        $totalDevices = NfcDevice::count();
        
        // Hitung online devices berdasarkan last_seen_at
        $now = Carbon::now();
        $onlineDevices = 0;
        $devices = NfcDevice::all();
        
        foreach ($devices as $device) {
            $lastSeen = $device->last_seen_at;
            if ($lastSeen && $now->diffInMinutes($lastSeen) <= 2) {
                $onlineDevices++;
            }
        }

        return view('dashboard.index', compact(
            'targetMajor',
            'totalStudents',
            'presentToday',
            'absentToday',
            'attendancePercentage',
            'recentTapIns',
            'weekStart',
            'weekEnd',
            'weeklyData',
            'avgAttendance',
            'maxAttendance',
            'maxDay',
            'minAttendance',
            'chartWidth',
            'chartHeight',
            'paddingLeft',
            'paddingRight',
            'paddingTop',
            'paddingBottom',
            'maxVal',
            'points',
            'trendPercent',
            'trendLabel',
            'trendText',
            'todayCount',
            'todayPercent',
            'rangeLabel',
            'summaryText',
            'totalDevices',
            'onlineDevices'
        ));
    }
}
