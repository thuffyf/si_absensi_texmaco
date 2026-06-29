@extends('layouts.app')

@section('title', 'Edit Jadwal — SITEXA Absensi')
@section('page_title', 'Edit Jadwal')

@section('content')
<div class="mb-6">
    <a href="{{ route('schedules.index') }}" class="btn-secondary text-sm">Kembali</a>
</div>

<div class="glass-card p-6 rounded-2xl">
    <h2 class="text-lg font-bold text-white mb-4">Perbarui Jadwal</h2>

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @csrf
        @method('PUT')
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Guru</label>
            <select name="teacher_id" class="input-field text-sm" required>
                <option value="">Pilih guru</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('teacher_id', $schedule->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Kelas</label>
            <select name="class_name" class="input-field text-sm" required>
                @foreach($classOptions as $className)
                    <option value="{{ $className }}" @selected(old('class_name', $schedule->class_name) === $className)>{{ $className }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Mata Pelajaran</label>
            <input name="subject" value="{{ old('subject', $schedule->subject) }}" class="input-field" placeholder="Mata pelajaran" required />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Hari</label>
            <select name="day_of_week" class="input-field text-sm" required>
                @foreach($dayOptions as $dayName)
                    <option value="{{ $dayName }}" @selected(old('day_of_week', $schedule->day_of_week) === $dayName)>{{ $dayName }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Jam Mulai</label>
            <input name="start_time" type="time" value="{{ old('start_time', $schedule->start_time?->format('H:i')) }}" class="input-field" required />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Jam Selesai</label>
            <input name="end_time" type="time" value="{{ old('end_time', $schedule->end_time?->format('H:i')) }}" class="input-field" required />
        </div>
        <div class="flex flex-col gap-1 col-span-1 md:col-span-2">
            <label class="text-xs font-semibold text-white ml-1">Status</label>
            <select name="status" class="input-field text-sm" required>
                <option value="aktif" @selected(old('status', $schedule->status) === 'aktif')>Aktif</option>
                <option value="idle" @selected(old('status', $schedule->status) === 'idle')>Idle</option>
            </select>
        </div>
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-4">Simpan Perubahan</button>
    </form>
</div>
@endsection
