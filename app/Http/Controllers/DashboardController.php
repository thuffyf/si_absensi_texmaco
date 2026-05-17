<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NfcDevice;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get X TEI class data
        $targetClass = 'X TEI';
        $targetMajor = 'TEI';
        
        // Get all students in X TEI class
        $students = Student::where('class_name', $targetClass)
            ->where('major', $targetMajor)
            ->get();
        
        $totalStudents = $students->count();
        
        // Get today's attendance for X TEI
        $today = Carbon::today();
        $todayAttendances = Attendance::whereDate('attendance_date', $today)
            ->whereHas('student', function($query) use ($targetClass, $targetMajor) {
                $query->where('class_name', $targetClass)
                    ->where('major', $targetMajor);
            })
            ->with('student')
            ->orderBy('attendance_time', 'desc')
            ->get();
        
        $presentToday = $todayAttendances->count();
        $absentToday = $totalStudents - $presentToday;
        
        // Get attendance percentage
        $attendancePercentage = $totalStudents > 0 ? round(($presentToday / $totalStudents) * 100, 1) : 0;
        
        // Get recent tap-ins (last 10)
        $recentTapIns = $todayAttendances->take(10);
        
        // Get weekly attendance data for chart
        $weekStart = Carbon::now()->locale('id')->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(4);
        
        $weeklyData = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        
        for ($i = 0; $i < 5; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $count = Attendance::whereDate('attendance_date', $date)
                ->whereHas('student', function($query) use ($targetClass, $targetMajor) {
                    $query->where('class_name', $targetClass)
                        ->where('major', $targetMajor);
                })
                ->count();
            
            $weeklyData[] = [
                'day' => $days[$i],
                'date' => $date->format('d'),
                'count' => $count
            ];
        }
        
        // Calculate statistics
        $counts = array_column($weeklyData, 'count');
        $avgAttendance = count($counts) > 0 ? round(array_sum($counts) / count($counts), 1) : 0;
        $maxAttendance = count($counts) > 0 ? max($counts) : 0;
        $maxDay = count($counts) > 0 ? $weeklyData[array_search($maxAttendance, $counts)]['day'] : '-';
        $minAttendance = count($counts) > 0 ? min($counts) : 0;
        
        // Get device status
        $totalDevices = 1;
        $onlineDevices = 1;
        
        // Get late students today
        $lateStudents = $todayAttendances->filter(function($attendance) {
            return $attendance->status === 'late';
        })->count();
        
        return view('dashboard.index', compact(
            'totalStudents',
            'presentToday',
            'absentToday',
            'attendancePercentage',
            'recentTapIns',
            'weeklyData',
            'avgAttendance',
            'maxAttendance',
            'maxDay',
            'minAttendance',
            'weekStart',
            'weekEnd',
            'totalDevices',
            'onlineDevices',
            'lateStudents',
            'targetClass'
        ));
    }
}
