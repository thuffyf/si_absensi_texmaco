@extends('layouts.app')

@section('title', 'Jadwal — SITEXA Absensi')
@section('page_title', 'Jadwal')
@section('page_subtitle', 'Kelas TEI · waktu ditampilkan WIB (Asia/Jakarta, UTC+7)')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3 md:gap-6">
        @foreach($classCards as $card)
            <article class="flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <h2 class="border-b border-slate-100 pb-3 text-xl font-bold text-slate-900">{{ $card['class_name'] }}</h2>
                <dl class="mt-4 flex flex-1 flex-col gap-3 text-sm">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Wali kelas</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $card['homeroom_teacher_name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Sudah absen hari ini</dt>
                        <dd class="mt-0.5 text-lg font-bold tabular-nums text-slate-900">{{ $card['attendance_count'] }}</dd>
                        <p class="mt-1 text-xs font-medium text-slate-500">Total siswa: {{ $card['student_count'] }}</p>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hari ini</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $todayLabel }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <a
                        href="{{ route('schedules.presence', ['slug' => $card['slug']]) }}"
                        class="flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                    >
                        Lihat Jadwal
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Masuk Hari Ini</h2>
                <p class="text-sm text-slate-500">Jadwal Minggu ke-{{ $currentWeek }} sesuai rotasi 4 minggu.</p>
            </div>
            <div class="text-sm text-slate-500">Minggu ke-{{ $currentWeek }}</div>
        </div>

        <div class="mt-6 space-y-6">
            @forelse($todaySchedules as $schedule)
                <div class="rounded-3xl border-2 {{ $schedule['is_running'] ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm {{ $schedule['is_running'] ? 'ring-2 ring-emerald-200' : '' }}">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $schedule['subject'] }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $schedule['teacher_name'] }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                            </span>
                            @if($schedule['is_running'])
                                <span class="rounded-full bg-emerald-200 px-3 py-1 font-semibold text-emerald-900">
                                    Sedang Berlangsung
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-600">
                    Tidak ada jadwal untuk hari ini.
                </div>
            @endforelse
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Kelola Jadwal</h2>
                <p class="text-sm text-slate-500">Tambah, edit, dan nonaktifkan jadwal kelas TEI.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('schedules.store') }}" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @csrf
            <select name="teacher_id" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required>
                <option value="">Pilih guru</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                @endforeach
            </select>
            <select name="class_name" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required>
                <option value="">Pilih kelas</option>
                @foreach($classOptions as $className)
                    <option value="{{ $className }}" @selected(old('class_name') === $className)>{{ $className }}</option>
                @endforeach
            </select>
            <input name="subject" value="{{ old('subject') }}" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Mata pelajaran" required />
            <select name="day_of_week" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required>
                <option value="">Pilih hari</option>
                @foreach($dayOptions as $dayName)
                    <option value="{{ $dayName }}" @selected(old('day_of_week') === $dayName)>{{ $dayName }}</option>
                @endforeach
            </select>
            <input name="start_time" type="time" value="{{ old('start_time') }}" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required />
            <input name="end_time" type="time" value="{{ old('end_time') }}" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required />
            <select name="status" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required>
                <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                <option value="idle" @selected(old('status') === 'idle')>Idle</option>
            </select>
            <button type="submit" class="rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 md:col-span-2 lg:col-span-4">
                Tambah Jadwal
            </button>
        </form>

        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-3 py-3">Hari</th>
                        <th class="px-3 py-3">Jam</th>
                        <th class="px-3 py-3">Kelas</th>
                        <th class="px-3 py-3">Mapel</th>
                        <th class="px-3 py-3">Guru</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($managedSchedules as $schedule)
                        <tr class="text-slate-700">
                            <td class="px-3 py-3 font-medium text-slate-900">{{ $schedule->day_of_week }}</td>
                            <td class="px-3 py-3 tabular-nums">{{ $schedule->start_time?->format('H:i') }} - {{ $schedule->end_time?->format('H:i') }}</td>
                            <td class="px-3 py-3">{{ $schedule->class_name }}</td>
                            <td class="px-3 py-3">{{ $schedule->subject }}</td>
                            <td class="px-3 py-3">{{ $schedule->teacher?->name ?? 'â€”' }}</td>
                            <td class="px-3 py-3">
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-emerald-100 text-emerald-800' => $schedule->status === 'aktif',
                                    'bg-slate-100 text-slate-700' => $schedule->status !== 'aktif',
                                ])>
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('schedules.edit', $schedule) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 transition-colors hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-8 text-center text-sm text-slate-500">
                                Belum ada jadwal tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
