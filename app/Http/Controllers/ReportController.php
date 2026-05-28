<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
                'alpa' => $studentRecords->where('status', 'alpa')->count(),
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
                'alpa' => $statusCounts->get('alpa', 0),
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'class' => $className,
            ],
        ]);
    }

    public function downloadCsv(Request $request)
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

        $students = Student::query()
            ->when($className, function ($builder) use ($className) {
                $builder->where('class_name', $className);
            })
            ->orderBy('name')
            ->get();

        $rows = $students->map(function ($student) use ($attendances) {
            $studentRecords = $attendances->where('student_id', $student->id);
            return [
                'student' => $student,
                'hadir' => $studentRecords->where('status', 'hadir')->count(),
                'izin' => $studentRecords->where('status', 'izin')->count(),
                'sakit' => $studentRecords->where('status', 'sakit')->count(),
                'alpa' => $studentRecords->where('status', 'alpa')->count(),
                'total' => $studentRecords->count(),
            ];
        })->filter(function($row) { return $row['total'] > 0; });

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-absensi-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Siswa', 'NIS', 'Kelas', 'Jurusan', 'Hadir', 'Izin', 'Sakit', 'Alpa', 'Total']);

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row['student']->name ?? '-',
                    $row['student']->nis ?? '-',
                    $row['student']->class_name ?? '-',
                    $row['student']->major ?? '-',
                    $row['hadir'],
                    $row['izin'],
                    $row['sakit'],
                    $row['alpa'],
                    $row['total'],
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function downloadPdf(Request $request)
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

        $students = Student::query()
            ->when($className, function ($builder) use ($className) {
                $builder->where('class_name', $className);
            })
            ->orderBy('name')
            ->get();

        $rows = $students->map(function ($student) use ($attendances) {
            $studentRecords = $attendances->where('student_id', $student->id);
            return [
                'student' => $student,
                'hadir' => $studentRecords->where('status', 'hadir')->count(),
                'izin' => $studentRecords->where('status', 'izin')->count(),
                'sakit' => $studentRecords->where('status', 'sakit')->count(),
                'alpa' => $studentRecords->where('status', 'alpa')->count(),
                'total' => $studentRecords->count(),
            ];
        })->filter(function($row) { return $row['total'] > 0; });

        $html = view('reports.pdf-absensi', [
            'rows' => $rows,
            'attendances' => $attendances,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'className' => $className,
        ])->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('laporan-absensi-' . date('Y-m-d') . '.pdf', 'D');
    }
}
