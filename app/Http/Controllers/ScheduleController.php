<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
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

        return view('schedules.index', compact('schedulesByDay', 'teachers'));
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
