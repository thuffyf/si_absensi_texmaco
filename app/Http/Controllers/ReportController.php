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

        $attendances = $attendanceQuery->orderByDesc('attendance_date')->orderByDesc('attendance_time')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-absensi-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Siswa', 'NIS', 'Kelas', 'Jurusan', 'Tanggal', 'Waktu', 'Status', 'Keterangan']);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->student?->name ?? '-',
                    $attendance->student?->nis ?? '-',
                    $attendance->student?->class_name ?? '-',
                    $attendance->student?->major ?? '-',
                    $attendance->attendance_date?->format('Y-m-d') ?? '-',
                    $attendance->attendance_time ?? '-',
                    ucfirst($attendance->status),
                    $attendance->note ?? '-',
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

        $attendances = $attendanceQuery->orderByDesc('attendance_date')->orderByDesc('attendance_time')->get();

        $html = view('reports.pdf-absensi', [
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
