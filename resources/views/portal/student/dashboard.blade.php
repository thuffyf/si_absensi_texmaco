@extends('portal.layouts.app')

@section('title', 'Dashboard Siswa - SITEXA')
@section('page_title', 'Beranda')
@section('page_subtitle', 'Ringkasan absensi bulan ini')

@section('content')
    @include('portal.partials.student-status')

    @php
        $attendanceTotal = max(1, $summary['total']);
        $attendanceRate = $summary['total'] > 0
            ? (int) round(($summary['hadir'] / $attendanceTotal) * 100)
            : 0;
        $ringCircumference = 2 * M_PI * 36;
        $ringOffset = $ringCircumference - ($attendanceRate / 100) * $ringCircumference;
        $periodDisplay = \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('F Y');
    @endphp

    <section class="grid gap-4 lg:grid-cols-[minmax(0,1.35fr)_minmax(280px,0.65fr)]">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-600 via-sky-700 to-slate-900 px-5 py-5 text-white shadow-lg shadow-sky-200/40 sm:px-6 lg:min-h-[18rem] lg:p-7">
            <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-6 -left-6 h-24 w-24 rounded-full bg-sky-400/20 blur-xl"></div>

            <div class="relative flex items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-sky-100">Selamat datang</p>
                    <h2 class="mt-1 truncate text-2xl font-bold sm:text-3xl">{{ strtok($student->name, ' ') }}</h2>
                    <p class="mt-1 text-sm font-medium text-sky-100">{{ $student->class_name }}</p>
                    <p class="mt-2 text-xs text-sky-100/80">NIS {{ $student->nis }} &middot; {{ $periodDisplay }}</p>
                </div>

                <div class="relative shrink-0">
                    <svg class="portal-progress-ring h-20 w-20" viewBox="0 0 80 80" aria-hidden="true">
                        <circle cx="40" cy="40" r="36" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="6" />
                        <circle
                            cx="40" cy="40" r="36" fill="none"
                            stroke="white" stroke-width="6" stroke-linecap="round"
                            stroke-dasharray="{{ $ringCircumference }}"
                            stroke-dashoffset="{{ $ringOffset }}"
                        />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-lg font-bold leading-none">{{ $attendanceRate }}%</span>
                        <span class="mt-0.5 text-[9px] uppercase tracking-wider text-sky-100/70">Hadir</span>
                    </div>
                </div>
            </div>

            <div class="relative mt-6 grid gap-3 sm:grid-cols-2 lg:mt-10">
                <a href="{{ route('portal.student.schedule') }}" class="group flex items-center gap-3 rounded-2xl bg-white/10 px-3 py-3 backdrop-blur transition hover:bg-white/20 active:scale-[0.98]">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <span class="text-sm font-semibold">Jadwal</span>
                </a>
                <a href="{{ route('portal.student.leave') }}" class="group flex items-center gap-3 rounded-2xl bg-white/10 px-3 py-3 backdrop-blur transition hover:bg-white/20 active:scale-[0.98]">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    <span class="text-sm font-semibold">Ajukan Izin</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-1">
            @foreach ([
                ['key' => 'hadir', 'label' => 'Hadir', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'desc' => 'Tercatat'],
                ['key' => 'izin', 'label' => 'Izin', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500', 'text' => 'text-amber-600', 'desc' => 'Resmi'],
                ['key' => 'sakit', 'label' => 'Sakit', 'border' => 'border-rose-100', 'dot' => 'bg-rose-500', 'text' => 'text-rose-600', 'desc' => 'Keterangan'],
                ['key' => 'alpa', 'label' => 'Alpa', 'border' => 'border-slate-200', 'dot' => 'bg-slate-500', 'text' => 'text-slate-700', 'desc' => 'Perhatian'],
            ] as $stat)
                <div class="portal-stat-card rounded-3xl border {{ $stat['border'] }} bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $stat['label'] }}</p>
                        <span class="h-2 w-2 rounded-full {{ $stat['dot'] }}"></span>
                    </div>
                    <p class="mt-2 text-3xl font-bold {{ $stat['text'] }}">{{ $summary[$stat['key']] }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ $stat['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-5 grid gap-4 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
        <div>
            <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Jadwal Hari Ini</h3>
                            <p class="text-xs text-slate-500">{{ $todayName }}</p>
                        </div>
                    </div>
                    <a href="{{ route('portal.student.schedule') }}" class="text-xs font-semibold text-sky-700">Lihat semua &rarr;</a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($todaySchedules->take(3) as $schedule)
                        <div class="flex items-center gap-3 rounded-2xl bg-slate-50 px-3 py-3">
                            <div class="shrink-0 text-center">
                                <p class="text-xs font-bold text-sky-700">{{ $schedule->start_time?->format('H:i') ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $schedule->end_time?->format('H:i') ?? '' }}</p>
                            </div>
                            <div class="h-8 w-px bg-slate-200"></div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-slate-900">{{ $schedule->subject }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $schedule->teacher?->name ?? 'Guru belum diatur' }}</p>
                            </div>
                        </div>
                    @empty
                        @include('portal.partials.student-empty-state', [
                            'icon' => 'calendar',
                            'title' => 'Tidak ada jadwal hari ini',
                            'description' => 'Nikmati hari libur atau cek jadwal lengkap.',
                        ])
                    @endforelse
                </div>
            </section>
        </div>

        <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-1">
            <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Riwayat Terbaru</h3>
                            <p class="text-xs text-slate-500">4 absensi terakhir</p>
                        </div>
                    </div>
                    <a href="{{ route('portal.student.history') }}" class="text-xs font-semibold text-sky-700">Lihat semua &rarr;</a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($latestRecords as $record)
                        <div class="flex items-start gap-3 rounded-2xl bg-slate-50 px-3 py-3">
                            <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ portalStatusDot($record->status) }}"></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="font-semibold text-slate-900">{{ portalStatusLabel($record->status) }}</p>
                                    <span class="shrink-0 rounded-full px-2.5 py-0.5 text-[10px] font-semibold ring-1 {{ portalStatusBadge($record->status) }}">
                                        {{ portalStatusLabel($record->status) }}
                                    </span>
                                </div>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    {{ portalFormatDate($record->attendance_date, 'D, d M Y') }}
                                    @if ($record->attendance_time && $record->attendance_time !== '00:00:00')
                                        &middot; {{ substr($record->attendance_time, 0, 5) }}
                                    @endif
                                </p>
                                @if ($record->note)
                                    <p class="mt-1.5 text-xs text-slate-600">{{ $record->note }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        @include('portal.partials.student-empty-state', [
                            'icon' => 'clock',
                            'title' => 'Belum ada absensi',
                            'description' => 'Data absensi bulan ini akan muncul di sini.',
                        ])
                    @endforelse
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Pengajuan Izin/Sakit</h3>
                            <p class="text-xs text-slate-500">Status terbaru</p>
                        </div>
                    </div>
                    <a href="{{ route('portal.student.leave') }}" class="text-xs font-semibold text-sky-700">Buka &rarr;</a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($latestRequests as $leaveRequest)
                        <div class="rounded-2xl bg-slate-50 px-3 py-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-semibold capitalize text-slate-900">{{ $leaveRequest->type }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ portalFormatDate($leaveRequest->start_date, 'd M') }}
                                        @if ($leaveRequest->end_date && $leaveRequest->end_date->ne($leaveRequest->start_date))
                                            &ndash; {{ portalFormatDate($leaveRequest->end_date, 'd M Y') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full px-2.5 py-0.5 text-[10px] font-semibold ring-1 {{ portalRequestBadge($leaveRequest->status) }}">
                                    {{ portalRequestLabel($leaveRequest->status) }}
                                </span>
                            </div>
                            <p class="mt-2 line-clamp-2 text-xs text-slate-600">{{ $leaveRequest->reason }}</p>
                        </div>
                    @empty
                        @include('portal.partials.student-empty-state', [
                            'icon' => 'document',
                            'title' => 'Belum ada pengajuan',
                            'description' => 'Ajukan izin atau sakit jika diperlukan.',
                        ])
                    @endforelse
                </div>
            </section>
        </div>
    </section>
@endsection
