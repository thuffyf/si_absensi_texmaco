<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $weekStart = $today->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(4);

        $dayLabels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $xPositions = [40, 136, 232, 328, 424];

        $attendanceByDate = Attendance::query()
            ->whereBetween('attendance_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->where('status', 'hadir')
            ->get()
            ->groupBy(function ($attendance) {
                return $attendance->attendance_date?->toDateString();
            });

        $counts = [];
        foreach (range(0, 4) as $index) {
            $date = $weekStart->copy()->addDays($index)->toDateString();
            $counts[$index] = $attendanceByDate->get($date)?->count() ?? 0;
        }

        $maxVal = max($counts) ?: 0;
        $minVal = min($counts) ?: 0;
        $avg = count($counts) ? round(array_sum($counts) / count($counts), 1) : 0;

        $maxDayIndex = array_search($maxVal, $counts, true);
        $maxDay = $maxDayIndex !== false ? $dayLabels[$maxDayIndex] : '-';

        $minY = 85;
        $maxY = 170;
        $scale = $maxVal > 0 ? ($maxY - $minY) / $maxVal : 0;

        $points = [];
        foreach ($counts as $index => $value) {
            $y = $maxVal > 0 ? $maxY - ($value * $scale) : $maxY;
            $points[] = [
                'x' => $xPositions[$index],
                'y' => round($y, 1),
                'label' => $dayLabels[$index],
                'val' => $value,
            ];
        }

        $todayCount = Attendance::query()
            ->whereDate('attendance_date', $today->toDateString())
            ->where('status', 'hadir')
            ->count();

        $totalStudents = Student::count();
        $todayPercent = $totalStudents > 0 ? round(($todayCount / $totalStudents) * 100) : 0;

        $currentTotal = array_sum($counts);
        $lastWeekStart = $weekStart->copy()->subWeek();
        $lastWeekEnd = $weekEnd->copy()->subWeek();
        $lastTotal = Attendance::query()
            ->whereBetween('attendance_date', [$lastWeekStart->toDateString(), $lastWeekEnd->toDateString()])
            ->where('status', 'hadir')
            ->count();

        $trendPercent = $lastTotal > 0 ? round((($currentTotal - $lastTotal) / $lastTotal) * 100) : null;
        $trendLabel = $trendPercent !== null ? ($trendPercent >= 0 ? '+' : '') . $trendPercent . '%' : '—';
        $trendText = $trendPercent === null
            ? 'Belum ada pembanding'
            : ($trendPercent >= 0 ? 'Volume naik' : 'Volume turun');

        $summaryText = $maxVal > 0
            ? "Puncak minggu ini di {$maxDay} dengan {$maxVal} tap in."
            : 'Belum ada data tap in minggu ini.';

        return view('dashboard.index', [
            'points' => $points,
            'avg' => $avg,
            'maxVal' => $maxVal,
            'maxDay' => $maxDay,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'rangeLabel' => $minVal . '–' . $maxVal,
            'todayCount' => $todayCount,
            'todayPercent' => $todayPercent,
            'trendLabel' => $trendLabel,
            'trendText' => $trendText,
            'summaryText' => $summaryText,
        ]);
    }
}
