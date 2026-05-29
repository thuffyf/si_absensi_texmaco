@extends('layouts.app')

@section('title', 'Dashboard — SITEXA Absensi')
@section('page_title', 'Dashboard tesweb123')
@section('page_subtitle')
    SITEXA Absensi Texmaco Purwasari - Kelas {{ $targetClass }}
@endsection

@section('content')
@php
    $weekStart = $weekStart->locale('id');
    $weekEnd = $weekEnd->locale('id');
@endphp

<div class="mx-auto flex w-full max-w-none flex-col gap-3 max-lg:space-y-1 lg:h-full lg:min-h-0 lg:gap-3 lg:overflow-hidden">
    <div class="grid min-h-0 flex-1 grid-cols-1 gap-3 lg:grid-cols-12 lg:gap-4 lg:overflow-hidden">
        <!-- Statistik (lebih lebar) -->
        <section class="flex min-h-0 flex-col lg:col-span-9 lg:overflow-hidden">
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 lg:text-xl">Statistik Kehadiran - {{ $targetClass }}</h2>
                        <p class="mt-1 text-xs text-slate-500 lg:text-sm">Jumlah siswa tap in per hari kerja</p>
                    </div>
                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                            {{ $weekStart->format('d') }} — {{ $weekEnd->format('d M Y') }}
                        </span>
                        <span class="rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-800">Minggu ini</span>
                    </div>
                </div>

                <div class="mt-3 grid shrink-0 grid-cols-2 gap-2 sm:grid-cols-4 lg:mt-4 lg:gap-3">
                    <div class="rounded-xl border border-slate-100 bg-gradient-to-br from-slate-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Rata-rata / hari</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900 lg:text-3xl">{{ $avgAttendance }}</p>
                    </div>
                    <div class="rounded-xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-sky-800 lg:text-xs">Puncak minggu</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-sky-900 lg:text-3xl">{{ $maxAttendance }}</p>
                        <p class="mt-0.5 text-xs text-sky-800/90 lg:text-sm">{{ $maxDay }}</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50/80 to-white p-4 lg:p-5 overflow-hidden">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-900/90 lg:text-sm">vs minggu lalu</p>
                        <p class="mt-2 text-2xl lg:text-3xl font-bold tabular-nums text-emerald-800 truncate">{{ $trendLabel }}</p>
                        <p class="mt-1 text-xs lg:text-sm text-emerald-800/90 line-clamp-1">{{ $trendText }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 lg:p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 lg:text-sm">Rentang</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900 lg:text-4xl xl:text-5xl">{{ $rangeLabel }}</p>
                        <p class="mt-1 text-sm text-slate-500">Min — max</p>
                    </div>
                </div>

                <div class="mt-3 min-h-0 flex-1 overflow-hidden rounded-xl border border-slate-100 bg-slate-50/40 p-2.5 lg:mt-4 lg:flex lg:items-stretch lg:justify-center lg:p-3">
                    <svg viewBox="0 0 520 200" class="h-40 w-full max-lg:min-w-[300px] lg:h-full lg:min-h-[10rem] lg:max-h-none lg:w-full" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Grafik garis statistik per hari">
                        <defs>
                            <linearGradient id="lineFill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#0ea5e9" stop-opacity="0.22" />
                                <stop offset="100%" stop-color="#0ea5e9" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        @foreach ([170, 140, 110, 85] as $gy)
                            <line x1="28" y1="{{ $gy }}" x2="480" y2="{{ $gy }}" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 6" />
                        @endforeach
                        <text x="24" y="184" text-anchor="end" fill="#94a3b8" font-size="11">0</text>
                        <text x="24" y="124" text-anchor="end" fill="#94a3b8" font-size="11">{{ round($maxVal * 0.6) }}</text>
                        <text x="24" y="94" text-anchor="end" fill="#94a3b8" font-size="11">{{ $maxVal }}</text>
                        <text x="4" y="72" fill="#64748b" font-size="11" font-weight="600">Tap in</text>
                        @php
                            $polygonPoints = '';
                            $polylinePoints = '';
                            foreach ($points as $p) {
                                $polygonPoints .= $p['x'] . ',' . $p['y'] . ' ';
                                $polylinePoints .= $p['x'] . ',' . $p['y'] . ' ';
                            }
                            $polygonPoints .= $points[count($points)-1]['x'] . ',188 ' . $points[0]['x'] . ',188';
                        @endphp
                        <polygon fill="url(#lineFill)" points="{{ $polygonPoints }}" />
                        <polyline
                            fill="none"
                            stroke="#0284c7"
                            stroke-width="3.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            points="{{ $polylinePoints }}"
                        />
                        @foreach ($points as $p)
                            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="6" fill="#ffffff" stroke="#0284c7" stroke-width="2.5" />
                            <text x="{{ $p['x'] }}" y="196" text-anchor="middle" fill="#64748b" font-size="12" font-weight="600">{{ $p['label'] }}</text>
                            <text x="{{ $p['x'] }}" y="{{ $p['y'] - 14 }}" text-anchor="middle" fill="#0f172a" font-size="14" font-weight="700">{{ $p['val'] }}</text>
                        @endforeach
                    </svg>
                </div>

                <div class="mt-3 flex shrink-0 flex-col gap-2 rounded-2xl border border-sky-100 bg-sky-50/50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between lg:mt-4 lg:px-5 lg:py-3.5">
                    <p class="text-sm text-slate-700 lg:text-base">
                        <span class="font-semibold text-sky-900">Ringkas:</span> {{ $summaryText }}
                    </p>
                    <a href="{{ route('reports.absensi') }}" class="shrink-0 text-xs font-semibold text-sky-700 underline-offset-2 hover:underline lg:text-sm">Laporan detail</a>
                </div>
            </div>
        </section>

        <!-- Kanan (lebih sempit) -->
        <div class="flex min-h-0 flex-col gap-4 lg:col-span-3 lg:gap-4 lg:overflow-hidden">
            <section class="shrink-0 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500 lg:text-sm">Jumlah Siswa Tap In</h2>
                <p class="mt-2 text-5xl font-bold tabular-nums text-slate-900 lg:text-6xl xl:text-7xl">{{ $todayCount }}</p>
                <p class="mt-2 text-sm text-slate-500 lg:text-base">Hari ini</p>
                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100 lg:h-3">
                    <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-blue-600" style="width: {{ $todayPercent }}%"></div>
                </div>
                <p class="mt-2 text-xs leading-snug text-slate-500 lg:text-sm">{{ $todayPercent }}% dari total siswa.</p>
            </section>

            <!-- Status Perangkat NFC -->
            <section class="shrink-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <h2 class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Status Perangkat NFC</h2>
                <div class="mt-2 flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $onlineDevices > 0 ? 'bg-emerald-100' : 'bg-red-100' }}">
                        <svg class="h-4 w-4 {{ $onlineDevices > 0 ? 'text-emerald-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($onlineDevices > 0)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v6"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 00-12.728 0M12 3v6"></path>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-bold text-slate-900 lg:text-lg">{{ $onlineDevices }}/{{ $totalDevices }}</p>
                        <p class="text-[10px] text-slate-500 lg:text-xs">Perangkat online</p>
                    </div>
                </div>
            </section>

            <!-- Tap In Terbaru -->
            <section class="min-h-0 flex-1 overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:flex lg:flex-col lg:p-4">
                <h2 class="shrink-0 text-sm font-bold text-slate-900 lg:text-base">Tap In Terbaru</h2>
                @if($recentTapIns->count() > 0)
                    <div class="mt-2 flex-1 space-y-2 overflow-hidden lg:mt-2 lg:flex-1 lg:overflow-y-auto">
                        @foreach($recentTapIns as $attendance)
                            <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-2">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-sky-100 text-[10px] font-bold text-sky-700">
                                    {{ substr($attendance->student->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-xs font-medium text-slate-900 lg:text-sm">{{ $attendance->student->name }}</p>
                                    <p class="text-[10px] text-slate-500 lg:text-xs">{{ $attendance->attendance_time }}</p>
                                </div>
                                @if($attendance->status === 'late')
                                    <span class="rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-800 lg:text-xs">Terlambat</span>
                                @else
                                    <span class="rounded-full bg-emerald-100 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-800 lg:text-xs">Tepat</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-2 text-xs text-slate-500 lg:text-sm">Belum ada data tap in hari ini.</p>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
