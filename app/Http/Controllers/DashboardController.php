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
        $targetClass = 'X TEI';
        $targetMajor = 'TEI';

        $students = Student::query()
            ->where('class_name', $targetClass)
            ->where('major', $targetMajor)
            ->get();

        $totalStudents = $students->count();
        $today = Carbon::today();

        $todayAttendances = Attendance::query()
            ->whereDate('attendance_date', $today)
            ->whereHas('student', function ($query) use ($targetClass, $targetMajor) {
                $query->where('class_name', $targetClass)
                    ->where('major', $targetMajor);
            })
            ->with('student')
            ->orderBy('attendance_time', 'desc')
            ->get();

        $presentToday = $todayAttendances->count();
        $absentToday = max($totalStudents - $presentToday, 0);
        $attendancePercentage = $totalStudents > 0 ? round(($presentToday / $totalStudents) * 100, 1) : 0;
        $recentTapIns = $todayAttendances->take(10);

        $weekStart = Carbon::now()->locale('id')->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(4);
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $weeklyData = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $count = Attendance::query()
                ->whereDate('attendance_date', $date)
                ->whereHas('student', function ($query) use ($targetClass, $targetMajor) {
                    $query->where('class_name', $targetClass)
                        ->where('major', $targetMajor);
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
            ->whereHas('student', function ($query) use ($targetClass, $targetMajor) {
                $query->where('class_name', $targetClass)
                    ->where('major', $targetMajor);
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
        $onlineDevices = NfcDevice::query()->where('status', 'online')->count();

        return view('dashboard.index', compact(
            'targetClass',
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
