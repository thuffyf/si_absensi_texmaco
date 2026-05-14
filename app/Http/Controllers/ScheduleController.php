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

        $classCards = [];
        foreach (self::TEI_CLASS_NAMES as $className) {
            $classCards[] = [
                'class_name' => $className,
                'slug' => self::TEI_SLUGS[$className],
                'homeroom_teacher_name' => $homerooms->get($className)?->homeroom_teacher_name ?? '—',
                'student_count' => Student::query()->where('class_name', $className)->count(),
            ];
        }

        $query = Schedule::query()->with('teacher');

        if ($request->filled('class')) {
            $query->where('class_name', $request->string('class'));
        }

        if ($request->filled('teacher')) {
            $query->where('teacher_id', $request->integer('teacher'));
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->string('subject'));
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        $schedulesByDay = $schedules->groupBy('day_of_week');
        $teachers = Teacher::orderBy('name')->get();

        return view('schedules.index', compact('classCards', 'todayLabel', 'schedulesByDay', 'teachers'));
    }

    /**
     * Ringkasan jumlah siswa hadir hari ini (zona waktu aplikasi: Asia/Jakarta, WIB UTC+7).
     */
    public function presence(string $slug)
    {
        $className = match ($slug) {
            'x-tei' => 'X TEI',
            'xi-tei' => 'XI TEI',
            'xii-tei' => 'XII TEI',
            default => abort(404),
        };

        $today = Carbon::now()->toDateString();

        $hadirCount = Attendance::query()
            ->whereDate('attendance_date', $today)
            ->where('status', 'hadir')
            ->whereHas('student', function ($q) use ($className) {
                $q->where('class_name', $className);
            })
            ->count();

        $totalStudents = Student::query()->where('class_name', $className)->count();

        $todayLabel = Carbon::now()->isoFormat('dddd, D MMMM YYYY');

        return view('schedules.presence', compact('className', 'hadirCount', 'totalStudents', 'todayLabel', 'today'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_name' => 'required|string|max:100',
            'subject' => 'required|string|max:120',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'total_students' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,idle',
        ]);

        Schedule::create($data);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $teachers = Teacher::orderBy('name')->get();

        return view('schedules.edit', compact('schedule', 'teachers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_name' => 'required|string|max:100',
            'subject' => 'required|string|max:120',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'total_students' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,idle',
        ]);

        $schedule->update($data);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
