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
                        Masuk
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Jadwal per Hari</h2>
                <p class="text-sm text-slate-500">Daftar mapel per hari dengan waktu dan jumlah siswa yang sudah absen hari ini.</p>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            @forelse($schedulesByDay as $day => $items)
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <div class="flex items-center justify-between bg-slate-50 px-4 py-3">
                        <h3 class="text-sm font-semibold text-slate-700">{{ $day }}</h3>
                        <span class="text-xs font-medium text-slate-500">{{ $items->count() }} mapel</span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($items as $schedule)
                            <div class="flex flex-col gap-3 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $schedule->subject }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $schedule->class_name }} · {{ $schedule->teacher?->name ?? '—' }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-600">
                                        {{ $schedule->start_time?->format('H:i') ?? '-' }} - {{ $schedule->end_time?->format('H:i') ?? '-' }}
                                    </span>
                                    <span class="rounded-full bg-sky-50 px-2.5 py-1 font-semibold text-sky-700">
                                        Absen: {{ $attendanceCounts[$schedule->id] ?? 0 }}
                                    </span>
                                    <a
                                        href="{{ route('schedules.edit', $schedule) }}"
                                        class="rounded-full border border-slate-200 px-3 py-1 font-semibold text-slate-600 hover:bg-slate-100"
                                    >
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                    Belum ada jadwal yang terdaftar.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
