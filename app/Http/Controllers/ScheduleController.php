<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Homeroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    private const TEI_CLASS_NAMES = ['X TEI', 'XI TEI', 'XII TEI'];
    private const DAY_NAMES = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    /** @var array<string, string> */
    private const TEI_SLUGS = [
        'X TEI' => 'x-tei',
        'XI TEI' => 'xi-tei',
        'XII TEI' => 'xii-tei',
    ];

    public function index(Request $request)
    {
        $homerooms = Homeroom::query()
            ->whereIn('class_name', self::TEI_CLASS_NAMES)
            ->get()
            ->keyBy('class_name');

        $todayLabel = Carbon::now()->isoFormat('dddd, D MMMM YYYY');
        $today = Carbon::now()->toDateString();
        $attendanceByClass = Attendance::query()
            ->selectRaw('students.class_name as class_name, COUNT(DISTINCT attendances.student_id) as total')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->whereDate('attendance_date', $today)
            ->whereNotNull('attendance_time')
            ->groupBy('students.class_name')
            ->pluck('total', 'class_name');

        $classCards = [];
        foreach (self::TEI_CLASS_NAMES as $className) {
            $classCards[] = [
                'class_name' => $className,
                'slug' => self::TEI_SLUGS[$className],
                'homeroom_teacher_name' => $homerooms->get($className)?->homeroom_teacher_name ?? '—',
                'student_count' => Student::query()->where('class_name', $className)->count(),
                'attendance_count' => (int) ($attendanceByClass[$className] ?? 0),
            ];
        }

        $attendanceCounts = Attendance::query()
            ->whereDate('attendance_date', $today)
            ->whereNotNull('schedule_id')
            ->selectRaw('schedule_id, COUNT(*) as total')
            ->groupBy('schedule_id')
            ->pluck('total', 'schedule_id');
        $teachers = Teacher::orderBy('name')->get();

        $dayNames = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $todayDayName = $dayNames[Carbon::now()->format('l')] ?? Carbon::now()->format('l');
        $now = Carbon::now();
        $currentWeek = (($now->weekOfYear - 1) % 4) + 1;

        $weeklySchedules = [
            1 => [
                'Senin' => [
                    ['subject' => 'B. Indonesia', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'Informatika', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Selasa' => [
                    ['subject' => 'MTK', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Sunda', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'IPA', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Rabu' => [
                    ['subject' => 'MTK', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Seni Budaya', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'B. Inggris', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Kamis' => [
                    ['subject' => 'PJOK', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'PPKN', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'Sejarah Indo', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Jumat' => [
                    ['subject' => 'PAI', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Inggris', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'P5', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
            ],
            2 => [
                'Senin' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK (Ibu Dwi)', 'teacher' => 'Ibu Dwi', 'start' => '08:45', 'end' => '10:15'],
                ],
                'Selasa' => [
                    ['subject' => 'Produktif (Pak Najib)', 'teacher' => 'Pak Najib', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Rabu' => [
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher' => 'Ibu Susi', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK (Ibu Vinni)', 'teacher' => 'Ibu Vinni', 'start' => '08:45', 'end' => '10:15'],
                ],
                'Kamis' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Jumat' => [
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher' => 'Ibu Susi', 'start' => '07:00', 'end' => '08:30'],
                ],
            ],
            3 => [
                'Senin' => [
                    ['subject' => 'PJOK', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Inggris', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'Sejarah Indo', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Selasa' => [
                    ['subject' => 'PAI', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'B. Indonesia', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Rabu' => [
                    ['subject' => 'MTK', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Seni Budaya', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'B. Sunda', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Kamis' => [
                    ['subject' => 'IPAS', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Inggris', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'PPKN', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Jumat' => [
                    ['subject' => 'P5', 'teacher' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Informatika', 'teacher' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'MTK', 'teacher' => '—', 'start' => '10:30', 'end' => '12:00'],
                    ['subject' => 'P5', 'teacher' => '—', 'start' => '13:00', 'end' => '14:30'],
                ],
            ],
            4 => [
                'Senin' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Selasa' => [
                    ['subject' => 'Produktif (Pak Najib)', 'teacher' => 'Pak Najib', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Rabu' => [
                    ['subject' => 'BK (Ibu Dwi)', 'teacher' => 'Ibu Dwi', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher' => 'Ibu Susi', 'start' => '08:45', 'end' => '10:15'],
                ],
                'Kamis' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Jumat' => [
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher' => 'Ibu Susi', 'start' => '07:00', 'end' => '08:30'],
                ],
            ],
        ];

        $fallbackTodaySchedules = collect($weeklySchedules[$currentWeek][$todayDayName] ?? [])->map(function ($item) use ($now) {
            return [
                'subject' => $item['subject'],
                'teacher_name' => $item['teacher'],
                'start_time' => $item['start'],
                'end_time' => $item['end'],
                'is_running' => $now->between(Carbon::parse($item['start']), Carbon::parse($item['end']), true),
            ];
        });

        $dbTodaySchedules = Schedule::query()
            ->with('teacher')
            ->whereIn('class_name', self::TEI_CLASS_NAMES)
            ->where('day_of_week', $todayDayName)
            ->where('status', 'aktif')
            ->orderBy('start_time')
            ->get()
            ->map(fn (Schedule $schedule) => [
                'subject' => $schedule->subject,
                'teacher_name' => $schedule->teacher?->name ?? 'â€”',
                'class_name' => $schedule->class_name,
                'start_time' => $schedule->start_time?->format('H:i'),
                'end_time' => $schedule->end_time?->format('H:i'),
                'is_running' => $this->isScheduleRunning($schedule, $now),
            ]);

        $todaySchedules = $dbTodaySchedules->isNotEmpty() ? $dbTodaySchedules : $fallbackTodaySchedules;

        $subjectFilter = $request->query('subject');
        $dayFilter = $request->query('day_of_week');
        $classFilter = $request->query('class_name');

        $managedSchedulesQuery = Schedule::query()
            ->with('teacher')
            ->whereIn('class_name', self::TEI_CLASS_NAMES);

        if ($subjectFilter) {
            $managedSchedulesQuery->where('subject', 'like', '%' . $subjectFilter . '%');
        }

        if ($dayFilter) {
            $managedSchedulesQuery->where('day_of_week', $dayFilter);
        }

        if ($classFilter) {
            $managedSchedulesQuery->where('class_name', $classFilter);
        }

        $managedSchedules = $managedSchedulesQuery
            ->get()
            ->sortBy(function (Schedule $schedule) {
                $dayIndex = array_search($schedule->day_of_week, self::DAY_NAMES, true);

                return sprintf(
                    '%02d-%s-%s',
                    $dayIndex === false ? 99 : $dayIndex,
                    $schedule->start_time?->format('H:i') ?? '',
                    $schedule->class_name
                );
            })
            ->values();

        $classOptions = self::TEI_CLASS_NAMES;
        $dayOptions = self::DAY_NAMES;

        return view('schedules.index', compact(
            'classCards',
            'todayLabel',
            'today',
            'todaySchedules',
            'attendanceCounts',
            'teachers',
            'currentWeek',
            'managedSchedules',
            'classOptions',
            'dayOptions'
        ));
    }

    /**
     * Menampilkan 3 mata pelajaran yang sedang berlangsung hari ini.
     */
    public function presence(string $slug)
    {
        $className = match ($slug) {
            'x-tei' => 'X TEI',
            'xi-tei' => 'XI TEI',
            'xii-tei' => 'XII TEI',
            default => abort(404),
        };

        $todayLabel = Carbon::now()->isoFormat('dddd, D MMMM YYYY');
        $today = Carbon::now()->toDateString();
        $now = Carbon::now();
        
        // Get day name in Indonesian (Senin, Selasa, etc.)
        $dayNames = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        
        $todayDayName = $dayNames[Carbon::now()->format('l')] ?? Carbon::now()->format('l');
        $weekStartReference = Carbon::create(2026, 5, 26);
        $weeksSinceStart = $weekStartReference->diffInWeeks($now);
        $currentWeek = ($weeksSinceStart % 4) + 1;

        $weeklySchedules = [
            1 => [
                'Senin' => [
                    ['subject' => 'B. Indonesia', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'Informatika', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Selasa' => [
                    ['subject' => 'MTK', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Sunda', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'IPA', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Rabu' => [
                    ['subject' => 'MTK', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Seni Budaya', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'B. Inggris', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Kamis' => [
                    ['subject' => 'PJOK', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'PPKN', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'Sejarah Indo', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Jumat' => [
                    ['subject' => 'PAI', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Inggris', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'P5', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
            ],
            2 => [
                'Senin' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher_name' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK (Ibu Dwi)', 'teacher_name' => 'Ibu Dwi', 'start' => '08:45', 'end' => '10:15'],
                ],
                'Selasa' => [
                    ['subject' => 'Produktif (Pak Najib)', 'teacher_name' => 'Pak Najib', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Rabu' => [
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher_name' => 'Ibu Susi', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK (Ibu Vinni)', 'teacher_name' => 'Ibu Vinni', 'start' => '08:45', 'end' => '10:15'],
                ],
                'Kamis' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher_name' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Jumat' => [
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher_name' => 'Ibu Susi', 'start' => '07:00', 'end' => '08:30'],
                ],
            ],
            3 => [
                'Senin' => [
                    ['subject' => 'PJOK', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Inggris', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'Sejarah Indo', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Selasa' => [
                    ['subject' => 'PAI', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'BK', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'B. Indonesia', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Rabu' => [
                    ['subject' => 'MTK', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Seni Budaya', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'B. Sunda', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Kamis' => [
                    ['subject' => 'IPAS', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'B. Inggris', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'PPKN', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                ],
                'Jumat' => [
                    ['subject' => 'P5', 'teacher_name' => '—', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Informatika', 'teacher_name' => '—', 'start' => '08:45', 'end' => '10:15'],
                    ['subject' => 'MTK', 'teacher_name' => '—', 'start' => '10:30', 'end' => '12:00'],
                    ['subject' => 'P5', 'teacher_name' => '—', 'start' => '13:00', 'end' => '14:30'],
                ],
            ],
            4 => [
                'Senin' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher_name' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Selasa' => [
                    ['subject' => 'Produktif (Pak Najib)', 'teacher_name' => 'Pak Najib', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Rabu' => [
                    ['subject' => 'BK (Ibu Dwi)', 'teacher_name' => 'Ibu Dwi', 'start' => '07:00', 'end' => '08:30'],
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher_name' => 'Ibu Susi', 'start' => '08:45', 'end' => '10:15'],
                ],
                'Kamis' => [
                    ['subject' => 'Produktif (Pak Bakti)', 'teacher_name' => 'Pak Bakti', 'start' => '07:00', 'end' => '08:30'],
                ],
                'Jumat' => [
                    ['subject' => 'Produktif (Ibu Susi)', 'teacher_name' => 'Ibu Susi', 'start' => '07:00', 'end' => '08:30'],
                ],
            ],
        ];

        $fallbackSubjectsToday = collect($weeklySchedules[$currentWeek][$todayDayName] ?? [])->map(function ($schedule) use ($now) {
            return [
                'subject' => $schedule['subject'],
                'start_time' => $schedule['start'],
                'end_time' => $schedule['end'],
                'teacher_name' => $schedule['teacher_name'],
                'is_running' => $now->between(Carbon::parse($schedule['start']), Carbon::parse($schedule['end']), true),
            ];
        });

        $dbSubjectsToday = Schedule::query()
            ->with('teacher')
            ->where('class_name', $className)
            ->where('day_of_week', $todayDayName)
            ->where('status', 'aktif')
            ->orderBy('start_time')
            ->get()
            ->map(fn (Schedule $schedule) => [
                'subject' => $schedule->subject,
                'start_time' => $schedule->start_time?->format('H:i'),
                'end_time' => $schedule->end_time?->format('H:i'),
                'teacher_name' => $schedule->teacher?->name ?? 'â€”',
                'is_running' => $this->isScheduleRunning($schedule, $now),
            ]);

        $subjectsToday = $dbSubjectsToday->isNotEmpty() ? $dbSubjectsToday : $fallbackSubjectsToday;

        return view('schedules.presence', compact('className', 'todayLabel', 'today', 'subjectsToday', 'currentWeek'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_name' => 'required|string|max:100',
            'subject' => 'required|string|max:120',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:aktif,idle',
        ]);

        $conflict = $this->scheduleConflictMessage($data);
        if ($conflict) {
            return back()->withErrors(['schedule' => $conflict])->withInput();
        }

        $data = $this->normalizeScheduleData($data);

        Schedule::create($data);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $teachers = Teacher::orderBy('name')->get();
        $classOptions = self::TEI_CLASS_NAMES;
        $dayOptions = self::DAY_NAMES;

        return view('schedules.edit', compact('schedule', 'teachers', 'classOptions', 'dayOptions'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_name' => 'required|string|max:100',
            'subject' => 'required|string|max:120',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:aktif,idle',
        ]);

        $conflict = $this->scheduleConflictMessage($data, $schedule);
        if ($conflict) {
            return back()->withErrors(['schedule' => $conflict])->withInput();
        }

        $data = $this->normalizeScheduleData($data);

        $schedule->update($data);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    private function parseScheduleTime(mixed $value, Carbon $reference): Carbon
    {
        $time = $value instanceof Carbon ? $value->copy() : Carbon::parse((string) $value);

        return $time->setDate($reference->year, $reference->month, $reference->day);
    }

    private function isScheduleRunning(Schedule $schedule, Carbon $reference): bool
    {
        if (!$schedule->start_time || !$schedule->end_time) {
            return false;
        }

        return $reference->between(
            $this->parseScheduleTime($schedule->start_time, $reference),
            $this->parseScheduleTime($schedule->end_time, $reference),
            true
        );
    }

    private function normalizeScheduleData(array $data): array
    {
        $data['total_students'] = Student::query()
            ->where('class_name', $data['class_name'])
            ->count();

        return $data;
    }

    private function scheduleConflictMessage(array $data, ?Schedule $ignore = null): ?string
    {
        $baseConflictQuery = fn ($query) => $query
            ->where('day_of_week', $data['day_of_week'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id));

        $classConflict = Schedule::query()
            ->where('class_name', $data['class_name'])
            ->where($baseConflictQuery)
            ->first();

        if ($classConflict) {
            return 'Jadwal kelas bentrok dengan ' . $classConflict->subject . ' pukul ' . $classConflict->start_time?->format('H:i') . '-' . $classConflict->end_time?->format('H:i') . '.';
        }

        $teacherConflict = Schedule::query()
            ->where('teacher_id', $data['teacher_id'])
            ->where($baseConflictQuery)
            ->first();

        if ($teacherConflict) {
            return 'Guru sudah punya jadwal lain di waktu tersebut.';
        }

        return null;
    }
}
