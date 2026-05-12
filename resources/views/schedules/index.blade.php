@extends('layouts.app')

@section('title', 'Jadwal — SITEXA Absensi')
@section('page_title', 'Jadwal')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">📅 Data Jadwal Kelas</h1>
            <p class="text-gray-400">Kelola jadwal belajar dan monitoring kehadiran guru</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="glass-card p-6 rounded-2xl mb-6">
    <h2 class="text-lg font-bold text-white mb-4">Tambah Jadwal</h2>
    <form method="POST" action="{{ route('schedules.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @csrf
        <select name="teacher_id" class="input-field text-sm" required>
            <option value="">Pilih guru</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
            @endforeach
        </select>
        <input name="class_name" value="{{ old('class_name') }}" class="input-field" placeholder="Kelas" required />
        <input name="subject" value="{{ old('subject') }}" class="input-field" placeholder="Mata pelajaran" required />
        <input name="day_of_week" value="{{ old('day_of_week') }}" class="input-field" placeholder="Hari (Senin, Selasa, ...)" required />
        <input name="start_time" type="time" value="{{ old('start_time') }}" class="input-field" required />
        <input name="end_time" type="time" value="{{ old('end_time') }}" class="input-field" required />
        <input name="total_students" type="number" min="0" value="{{ old('total_students') }}" class="input-field" placeholder="Jumlah siswa" />
        <select name="status" class="input-field text-sm" required>
            <option value="aktif" @selected(old('status') === 'aktif')>Aktif</option>
            <option value="idle" @selected(old('status') === 'idle')>Idle</option>
        </select>
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-4">Simpan Jadwal</button>
    </form>
</div>

<div class="glass-card p-6 rounded-2xl mb-6">
    <form method="GET" action="{{ route('schedules.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="class" value="{{ request('class') }}" class="input-field text-sm" placeholder="Kelas" />
        <select name="teacher" class="input-field text-sm">
            <option value="">Semua Guru</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(request('teacher') == $teacher->id)>{{ $teacher->name }}</option>
            @endforeach
        </select>
        <input type="text" name="subject" value="{{ request('subject') }}" class="input-field text-sm" placeholder="Mata pelajaran" />
        <div class="flex gap-2">
            <button class="btn-secondary text-sm" type="submit">Terapkan Filter</button>
            <a href="{{ route('schedules.index') }}" class="btn-secondary text-sm">Reset</a>
        </div>
    </form>
</div>

<div class="space-y-6">
    @forelse($schedulesByDay as $day => $items)
        <div>
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-neon-cyan rounded-full"></span>
                {{ $day }}
            </h3>
            <div class="space-y-3">
                @foreach($items as $schedule)
                    <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-center">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Jam</p>
                                <p class="text-lg font-bold text-neon-cyan">{{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Kelas</p>
                                <p class="font-semibold text-white">{{ $schedule->class_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Mata Pelajaran</p>
                                <p class="font-semibold text-white">{{ $schedule->subject }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Guru</p>
                                <p class="font-semibold text-white">{{ $schedule->teacher?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Jumlah Siswa</p>
                                <p class="font-semibold text-white">{{ $schedule->total_students ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="{{ $schedule->status === 'aktif' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($schedule->status) }}</span>
                                <a class="btn-icon text-xs" href="{{ route('schedules.edit', $schedule) }}">✏️</a>
                                <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-icon text-xs" type="submit">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center text-sm text-gray-400">Belum ada jadwal.</div>
    @endforelse
</div>
@endsection
