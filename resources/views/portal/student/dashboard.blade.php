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

    <section class="mb-5">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-500 to-sky-700 px-6 py-6 text-white shadow-lg">
            <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/5"></div>

            <div class="relative flex items-center justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-sky-100">Selamat datang kembali, 👋</p>
                    <h2 class="mt-1 break-words text-2xl font-bold">{{ $student->name }}</h2>
                    <p class="mt-1 text-sm text-sky-100/90">{{ $student->class_name }} &middot; NIS: {{ $student->nis }}</p>
                </div>

                <div class="relative shrink-0 hidden sm:block">
                    <svg class="portal-progress-ring h-16 w-16" viewBox="0 0 80 80" aria-hidden="true">
                        <circle cx="40" cy="40" r="36" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="8" />
                        <circle
                            cx="40" cy="40" r="36" fill="none"
                            stroke="white" stroke-width="8" stroke-linecap="round"
                            stroke-dasharray="{{ $ringCircumference }}"
                            stroke-dashoffset="{{ $ringOffset }}"
                        />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-sm font-bold">{{ $attendanceRate }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach ([
                ['key' => 'hadir', 'label' => 'Hadir', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50'],
                ['key' => 'izin', 'label' => 'Izin', 'dot' => 'bg-amber-500', 'text' => 'text-amber-700', 'bg' => 'bg-amber-50'],
                ['key' => 'sakit', 'label' => 'Sakit', 'dot' => 'bg-rose-500', 'text' => 'text-rose-700', 'bg' => 'bg-rose-50'],
                ['key' => 'alpa', 'label' => 'Alpa', 'dot' => 'bg-slate-500', 'text' => 'text-slate-700', 'bg' => 'bg-slate-100'],
            ] as $stat)
                <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $stat['bg'] }}">
                            <span class="h-2 w-2 rounded-full {{ $stat['dot'] }}"></span>
                        </span>
                        <p class="text-xs font-semibold text-slate-600">{{ $stat['label'] }}</p>
                    </div>
                    <p class="text-lg font-bold {{ $stat['text'] }}">{{ $summary[$stat['key']] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-5 grid gap-4 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
        <div>
            <section class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900">Jadwal Hari Ini</h3>
                            <p class="text-xs text-slate-500">{{ $todayName }}</p>
                        </div>
                    </div>
                    <a href="{{ route('portal.student.schedule') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Lihat semua →</a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($todaySchedules->take(3) as $schedule)
                        <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-3">
                            <div class="shrink-0 text-center">
                                <p class="text-xs font-bold text-sky-600">{{ $schedule->start_time?->format('H:i') ?? '-' }}</p>
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
            <section class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900">Riwayat Terbaru</h3>
                            <p class="text-xs text-slate-500">4 absensi terakhir</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($latestRecords as $record)
                        <div class="flex items-start gap-3 rounded-xl bg-slate-50 px-3 py-3">
                            <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full {{ portalStatusDot($record->status) }}"></span>
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

            <section class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900">Pengajuan Izin</h3>
                            <p class="text-xs text-slate-500">Status terbaru</p>
                        </div>
                    </div>
                    <a href="{{ route('portal.student.absensi') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Buka →</a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($latestRequests as $leaveRequest)
                        <div class="rounded-xl bg-slate-50 px-3 py-3">
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
