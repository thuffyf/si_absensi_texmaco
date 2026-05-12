@extends('layouts.app')

@section('title', 'Dashboard — SITEXA Absensi')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'SITEXA Absensi Texmaco Purwasari')

@section('content')
@php
    $points = [
        ['x' => 40, 'y' => 140, 'label' => 'Senin', 'val' => 29],
        ['x' => 136, 'y' => 118, 'label' => 'Selasa', 'val' => 32],
        ['x' => 232, 'y' => 112, 'label' => 'Rabu', 'val' => 33],
        ['x' => 328, 'y' => 132, 'label' => 'Kamis', 'val' => 27],
        ['x' => 424, 'y' => 126, 'label' => 'Jumat', 'val' => 28],
    ];
    $avg = round(array_sum(array_column($points, 'val')) / count($points), 1);
    $maxVal = max(array_column($points, 'val'));
    $maxDay = collect($points)->firstWhere('val', $maxVal)['label'] ?? '-';
    $weekStart = \Carbon\Carbon::now()->locale('id')->startOfWeek(\Carbon\Carbon::MONDAY);
    $weekEnd = $weekStart->copy()->addDays(4);
@endphp

<div class="mx-auto flex w-full max-w-none flex-col gap-4 max-lg:space-y-1 lg:h-full lg:min-h-0 lg:gap-4 lg:overflow-hidden">
    <div class="grid min-h-0 flex-1 grid-cols-1 gap-4 lg:grid-cols-12 lg:gap-5 lg:overflow-hidden">
        <!-- Statistik (lebih lebar) -->
        <section class="flex min-h-0 flex-col lg:col-span-9 lg:overflow-hidden">
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 lg:text-2xl">Statistik Setiap Hari</h2>
                        <p class="mt-1 text-sm text-slate-500 lg:text-base">Jumlah siswa tap in per hari kerja</p>
                    </div>
                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 lg:text-sm">
                            {{ $weekStart->format('d') }} — {{ $weekEnd->format('d M Y') }}
                        </span>
                        <span class="rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-800 lg:text-sm">Minggu ini</span>
                    </div>
                </div>

                <div class="mt-4 grid shrink-0 grid-cols-2 gap-3 sm:grid-cols-4 lg:mt-5 lg:gap-4">
                    <div class="rounded-2xl border border-slate-100 bg-gradient-to-br from-slate-50 to-white p-4 lg:p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 lg:text-sm">Rata-rata / hari</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900 lg:text-4xl xl:text-5xl">{{ $avg }}</p>
                    </div>
                    <div class="rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-4 lg:p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-sky-800 lg:text-sm">Puncak minggu</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-sky-900 lg:text-4xl xl:text-5xl">{{ $maxVal }}</p>
                        <p class="mt-1 text-sm text-sky-800/90 lg:text-base">{{ $maxDay }}</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50/80 to-white p-4 lg:p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-900/90 lg:text-sm">vs minggu lalu</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-emerald-800 lg:text-4xl xl:text-5xl">+4%</p>
                        <p class="mt-1 text-sm text-emerald-800/90">Volume naik</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 lg:p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 lg:text-sm">Rentang</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900 lg:text-4xl xl:text-5xl">27–33</p>
                        <p class="mt-1 text-sm text-slate-500">Min — max</p>
                    </div>
                </div>

                <div class="mt-4 min-h-0 flex-1 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/40 p-3 lg:mt-5 lg:flex lg:items-stretch lg:justify-center lg:p-4">
                    <svg viewBox="0 0 520 200" class="h-48 w-full max-lg:min-w-[300px] lg:h-full lg:min-h-[12rem] lg:max-h-none lg:w-full" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Grafik garis statistik per hari">
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
                        <text x="24" y="124" text-anchor="end" fill="#94a3b8" font-size="11">20</text>
                        <text x="24" y="94" text-anchor="end" fill="#94a3b8" font-size="11">35</text>
                        <text x="4" y="72" fill="#64748b" font-size="11" font-weight="600">Tap in</text>
                        <polygon fill="url(#lineFill)" points="40,140 136,118 232,112 328,132 424,126 424,188 40,188" />
                        <polyline
                            fill="none"
                            stroke="#0284c7"
                            stroke-width="3.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            points="40,140 136,118 232,112 328,132 424,126"
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
                        <span class="font-semibold text-sky-900">Ringkas:</span> pola minggu ini stabil; Kamis sedikit turun.
                    </p>
                    <a href="{{ route('reports.absensi') }}" class="shrink-0 text-sm font-semibold text-sky-700 underline-offset-2 hover:underline lg:text-base">Laporan detail</a>
                </div>
            </div>
        </section>

        <!-- Kanan (lebih sempit) -->
        <div class="flex min-h-0 flex-col gap-4 lg:col-span-3 lg:gap-4 lg:overflow-hidden">
            <section class="shrink-0 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500 lg:text-sm">Jumlah Siswa Tap In</h2>
                <p class="mt-2 text-5xl font-bold tabular-nums text-slate-900 lg:text-6xl xl:text-7xl">28</p>
                <p class="mt-2 text-sm text-slate-500 lg:text-base">Hari ini</p>
                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100 lg:h-3">
                    <div class="h-full w-[82%] rounded-full bg-gradient-to-r from-sky-500 to-blue-600"></div>
                </div>
                <p class="mt-2 text-xs leading-snug text-slate-500 lg:text-sm">Perkiraan vs kuota harian (contoh).</p>
            </section>

            <section class="min-h-0 flex-1 overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm max-lg:min-h-[8rem] lg:flex lg:flex-col lg:p-5">
                <h2 class="shrink-0 text-lg font-bold text-slate-900 lg:text-xl">Catatan</h2>
                <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-relaxed text-slate-600 lg:mt-3 lg:flex-1 lg:space-y-2 lg:text-base lg:leading-snug lg:overflow-hidden">
                    <li>Pastikan NFC gerbang online sebelum jam masuk.</li>
                    <li>Data grafik dapat dihubungkan ke database nanti.</li>
                    <li>Export laporan dari menu Laporan.</li>
                </ul>
            </section>
        </div>
    </div>
</div>
@endsection
