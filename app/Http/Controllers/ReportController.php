<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * @return array<int, string>
     */
    private function classOptions(): array
    {
        return Student::query()
            ->select('class_name')
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name')
            ->values()
            ->all();
    }

    /**
     * Normalisasi nilai filter kelas:
     * - '' / null / 'all' => null (tanpa filter)
     * - selain itu => hanya diterima jika ada di daftar kelas
     */
    private function normalizeClassFilter(?string $className, array $classOptions): ?string
    {
        $className = $className !== null ? trim($className) : null;

        if (empty($className) || $className === 'all') {
            return null;
        }

        return in_array($className, $classOptions, true) ? $className : null;
    }

    public function absensi(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        $classOptions = $this->classOptions();
        $classFilter = $request->input('class');
        $className = $this->normalizeClassFilter($classFilter, $classOptions);

        $attendanceQuery = Attendance::query()->with('student');

        if ($startDate) {
            $attendanceQuery->whereDate('attendance_date', '>=', $startDate);
        }

        if ($endDate) {
            $attendanceQuery->whereDate('attendance_date', '<=', $endDate);
        }

        if ($className) {
            $attendanceQuery->whereHas('student', function ($builder) use ($className) {
                $builder->where('class_name', $className);
            });
        }

        $attendances = $attendanceQuery->get();

        $students = Student::query()
            ->where('status', 'aktif')
            ->when($className, function ($builder) use ($className) {
                $builder->where('class_name', $className);
            })
            ->orderBy('name')
            ->get();

        // Hitung expected hari kerja (Senin-Jumat) dalam rentang tanggal
        $start = $startDate ? \Carbon\Carbon::parse($startDate) : null;
        $end = $endDate ? \Carbon\Carbon::parse($endDate) : null;
        
        $expectedDays = 0;
        if ($start && $end) {
            $current = $start->copy();
            while ($current->lte($end)) {
                // Hitung hanya hari Senin-Jumat
                if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                    $expectedDays++;
                }
                $current->addDay();
            }
        }

        $rows = $students->map(function ($student) use ($attendances, $expectedDays, $start, $end) {
            $studentRecords = $attendances->where('student_id', $student->id);
            
            $hadirCount = $studentRecords->where('status', 'hadir')->count();
            $izinCount = $studentRecords->where('status', 'izin')->count();
            $sakitCount = $studentRecords->where('status', 'sakit')->count();
            $alpaCount = $studentRecords->where('status', 'alpa')->count();
            $totalCount = $studentRecords->count();

            // Hitung alpa otomatis jika ada rentang tanggal dan expected days
            if ($expectedDays > 0 && $start && $end) {
                // Alpa = expected days - (hadir + izin + sakit)
                $calculatedAlpa = max(0, $expectedDays - ($hadirCount + $izinCount + $sakitCount));
                // Gunakan yang lebih besar antara alpa dari DB atau calculated
                $alpaCount = max($alpaCount, $calculatedAlpa);
                // Update total dengan alpa yang dihitung
                $totalCount = $hadirCount + $izinCount + $sakitCount + $alpaCount;
            }

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
                'hadir' => $hadirCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpa' => $alpaCount,
                'total' => $totalCount,
                'last_time' => $lastTime,
            ];
        });

        if ($status) {
            $rows = $rows->filter(function ($row) use ($status) {
                return $row[$status] > 0;
            })->values();
        }

        // Hitung total status counts dengan alpa yang sudah diperhitungkan
        $totalHadir = $rows->sum('hadir');
        $totalIzin = $rows->sum('izin');
        $totalSakit = $rows->sum('sakit');
        $totalAlpa = $rows->sum('alpa');
        $totalRecords = $totalHadir + $totalIzin + $totalSakit + $totalAlpa;

        return view('reports.absensi', [
            'rows' => $rows,
            'totalStudents' => $students->count(),
            'totalRecords' => $totalRecords,
            'statusCounts' => [
                'hadir' => $totalHadir,
                'izin' => $totalIzin,
                'sakit' => $totalSakit,
                'alpa' => $totalAlpa,
            ],
            'classOptions' => $classOptions,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'class' => $classFilter,
            ],
        ]);
    }

    public function downloadCsv(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        $classOptions = $this->classOptions();
        $className = $this->normalizeClassFilter($request->input('class'), $classOptions);

        $attendanceQuery = Attendance::query()->with('student');

        if ($startDate) {
            $attendanceQuery->whereDate('attendance_date', '>=', $startDate);
        }

        if ($endDate) {
            $attendanceQuery->whereDate('attendance_date', '<=', $endDate);
        }

        if ($className) {
            $attendanceQuery->whereHas('student', function ($builder) use ($className) {
                $builder->where('class_name', $className);
            });
        }

        $attendances = $attendanceQuery->get();

        $students = Student::query()
            ->where('status', 'aktif')
            ->when($className, function ($builder) use ($className) {
                $builder->where('class_name', $className);
            })
            ->orderBy('name')
            ->get();

        // Hitung expected hari kerja (Senin-Jumat) dalam rentang tanggal
        $start = $startDate ? \Carbon\Carbon::parse($startDate) : null;
        $end = $endDate ? \Carbon\Carbon::parse($endDate) : null;
        
        $expectedDays = 0;
        if ($start && $end) {
            $current = $start->copy();
            while ($current->lte($end)) {
                if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                    $expectedDays++;
                }
                $current->addDay();
            }
        }

        $rows = $students->map(function ($student) use ($attendances, $expectedDays, $start, $end) {
            $studentRecords = $attendances->where('student_id', $student->id);
            
            $hadirCount = $studentRecords->where('status', 'hadir')->count();
            $izinCount = $studentRecords->where('status', 'izin')->count();
            $sakitCount = $studentRecords->where('status', 'sakit')->count();
            $alpaCount = $studentRecords->where('status', 'alpa')->count();
            $totalCount = $studentRecords->count();

            if ($expectedDays > 0 && $start && $end) {
                $calculatedAlpa = max(0, $expectedDays - ($hadirCount + $izinCount + $sakitCount));
                $alpaCount = max($alpaCount, $calculatedAlpa);
                $totalCount = $hadirCount + $izinCount + $sakitCount + $alpaCount;
            }

            return [
                'student' => $student,
                'hadir' => $hadirCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpa' => $alpaCount,
                'total' => $totalCount,
            ];
        });
        
        if ($status) {
            $rows = $rows->filter(function ($row) use ($status) {
                return $row[$status] > 0;
            });
        } else {
            $rows = $rows->filter(function ($row) {
                return $row['total'] > 0;
            });
        }
        $rows = $rows->values();

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
        $classOptions = $this->classOptions();
        $className = $this->normalizeClassFilter($request->input('class'), $classOptions);

        $attendanceQuery = Attendance::query()->with('student');

        if ($startDate) {
            $attendanceQuery->whereDate('attendance_date', '>=', $startDate);
        }

        if ($endDate) {
            $attendanceQuery->whereDate('attendance_date', '<=', $endDate);
        }

        if ($className) {
            $attendanceQuery->whereHas('student', function ($builder) use ($className) {
                $builder->where('class_name', $className);
            });
        }

        $attendances = $attendanceQuery->get();

        $students = Student::query()
            ->where('status', 'aktif')
            ->when($className, function ($builder) use ($className) {
                $builder->where('class_name', $className);
            })
            ->orderBy('name')
            ->get();

        // Hitung expected hari kerja (Senin-Jumat) dalam rentang tanggal
        $start = $startDate ? \Carbon\Carbon::parse($startDate) : null;
        $end = $endDate ? \Carbon\Carbon::parse($endDate) : null;
        
        $expectedDays = 0;
        if ($start && $end) {
            $current = $start->copy();
            while ($current->lte($end)) {
                if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                    $expectedDays++;
                }
                $current->addDay();
            }
        }

        $rows = $students->map(function ($student) use ($attendances, $expectedDays, $start, $end) {
            $studentRecords = $attendances->where('student_id', $student->id);
            
            $hadirCount = $studentRecords->where('status', 'hadir')->count();
            $izinCount = $studentRecords->where('status', 'izin')->count();
            $sakitCount = $studentRecords->where('status', 'sakit')->count();
            $alpaCount = $studentRecords->where('status', 'alpa')->count();
            $totalCount = $studentRecords->count();

            if ($expectedDays > 0 && $start && $end) {
                $calculatedAlpa = max(0, $expectedDays - ($hadirCount + $izinCount + $sakitCount));
                $alpaCount = max($alpaCount, $calculatedAlpa);
                $totalCount = $hadirCount + $izinCount + $sakitCount + $alpaCount;
            }

            return [
                'student' => $student,
                'hadir' => $hadirCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpa' => $alpaCount,
                'total' => $totalCount,
            ];
        });

        if ($status) {
            $rows = $rows->filter(function ($row) use ($status) {
                return $row[$status] > 0;
            });
        } else {
            $rows = $rows->filter(function ($row) {
                return $row['total'] > 0;
            });
        }
        $rows = $rows->values();

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
