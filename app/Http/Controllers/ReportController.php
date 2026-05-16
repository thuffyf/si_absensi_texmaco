<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function absensi(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        $className = $request->input('class');

        $attendanceQuery = Attendance::query()->with('student');

        if ($startDate) {
            $attendanceQuery->whereDate('attendance_date', '>=', $startDate);
        }

        if ($endDate) {
            $attendanceQuery->whereDate('attendance_date', '<=', $endDate);
        }

        if ($status) {
            $attendanceQuery->where('status', $status);
        }

        if ($className) {
            $attendanceQuery->whereHas('student', function ($builder) use ($className) {
                $builder->where('class_name', $className);
            });
        }

        $attendances = $attendanceQuery->get();
        $totalRecords = $attendances->count();

        $statusCounts = $attendances->countBy('status');

        $students = Student::query()
            ->when($className, function ($builder) use ($className) {
                $builder->where('class_name', $className);
            })
            ->orderBy('name')
            ->get();

        $rows = $students->map(function ($student) use ($attendances) {
            $studentRecords = $attendances->where('student_id', $student->id);
            $lastWithTime = $studentRecords
                ->whereNotNull('attendance_time')
                ->sortByDesc(function ($record) {
                    $date = $record->attendance_date?->format('Y-m-d') ?? '';
                    $time = (string) ($record->attendance_time ?? '');
                    return $date . ' ' . $time;
                })
                ->first();

            $lastTime = '-';
            if ($lastWithTime && is_string($lastWithTime->attendance_time)) {
                $lastTime = substr($lastWithTime->attendance_time, 0, 5);
            }

            return [
                'student' => $student,
                'hadir' => $studentRecords->where('status', 'hadir')->count(),
                'izin' => $studentRecords->where('status', 'izin')->count(),
                'sakit' => $studentRecords->where('status', 'sakit')->count(),
                'alpha' => $studentRecords->where('status', 'alpha')->count(),
                'total' => $studentRecords->count(),
                'last_time' => $lastTime,
            ];
        });

        return view('reports.absensi', [
            'rows' => $rows,
            'totalStudents' => $students->count(),
            'totalRecords' => $totalRecords,
            'statusCounts' => [
                'hadir' => $statusCounts->get('hadir', 0),
                'izin' => $statusCounts->get('izin', 0),
                'sakit' => $statusCounts->get('sakit', 0),
                'alpha' => $statusCounts->get('alpha', 0),
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'class' => $className,
            ],
        ]);
    }
}
