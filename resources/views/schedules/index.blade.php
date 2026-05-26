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
</div>
@endsection
