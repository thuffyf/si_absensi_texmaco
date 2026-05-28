<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('email', $user->email)->first();

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Akun siswa belum terhubung dengan data siswa.');
        }

        $today = Carbon::today();
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
        $weekStart = Carbon::now()->locale('id')->startOfWeek(Carbon::MONDAY);
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
}
