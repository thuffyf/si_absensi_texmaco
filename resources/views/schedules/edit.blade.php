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
        <select name="teacher_id" class="input-field text-sm" required>
            <option value="">Pilih guru</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $schedule->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
            @endforeach
        </select>
        <select name="class_name" class="input-field text-sm" required>
            <option value="X" @selected(old('class_name', $schedule->class_name) === 'X')">X</option>
            <option value="XI" @selected(old('class_name', $schedule->class_name) === 'XI')">XI</option>
            <option value="XII" @selected(old('class_name', $schedule->class_name) === 'XII')">XII</option>
        </select>
        <input name="subject" value="{{ old('subject', $schedule->subject) }}" class="input-field" placeholder="Mata pelajaran" required />
        <input name="day_of_week" value="{{ old('day_of_week', $schedule->day_of_week) }}" class="input-field" placeholder="Hari" required />
        <input name="start_time" type="time" value="{{ old('start_time', $schedule->start_time?->format('H:i')) }}" class="input-field" required />
        <input name="end_time" type="time" value="{{ old('end_time', $schedule->end_time?->format('H:i')) }}" class="input-field" required />
        <input name="total_students" type="number" min="0" value="{{ old('total_students', $schedule->total_students) }}" class="input-field" placeholder="Jumlah siswa" />
        <select name="status" class="input-field text-sm" required>
            <option value="aktif" @selected(old('status', $schedule->status) === 'aktif')>Aktif</option>
            <option value="idle" @selected(old('status', $schedule->status) === 'idle')>Idle</option>
        </select>
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-4">Simpan Perubahan</button>
    </form>
</div>
@endsection
