@extends('portal.layouts.app')

@section('title', 'Riwayat Absensi - SITEXA')
@section('page_title', 'Riwayat Absensi')
@section('page_subtitle', 'Data absensi bulan ini')

@section('content')
    @include('portal.partials.student-status')

    @php
        $normalizeStatus = fn ($status) => in_array($status, ['alpha', 'alfa', 'alpa'], true) ? 'alpa' : ($status ?: 'unknown');
        $counts = $records->map(fn ($r) => $normalizeStatus($r->status))->countBy();
        $totalRecords = $records->count();
    @endphp

    {{-- Summary hero --}}
    <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 px-5 py-5 text-white shadow-lg">
        <p class="text-sm text-slate-300">Periode aktif</p>
        <h2 class="mt-1 text-xl font-bold">{{ $periodLabel }}</h2>
        <p class="mt-2 text-sm text-slate-300">{{ $totalRecords }} catatan absensi bulan ini</p>

        <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
            @foreach ([
                ['key' => 'hadir', 'label' => 'Hadir', 'dot' => 'bg-emerald-400'],
                ['key' => 'izin', 'label' => 'Izin', 'dot' => 'bg-amber-400'],
                ['key' => 'sakit', 'label' => 'Sakit', 'dot' => 'bg-rose-400'],
                ['key' => 'alpa', 'label' => 'Alpa', 'dot' => 'bg-slate-400'],
            ] as $stat)
                <div class="rounded-xl bg-white/10 px-3 py-3 text-center backdrop-blur">
                    <div class="flex items-center justify-center gap-1.5">
                        <span class="inline-block h-2 w-2 rounded-full {{ $stat['dot'] }}"></span>
                        <p class="text-xs font-medium text-slate-200">{{ $stat['label'] }}</p>
                    </div>
                    <p class="mt-1 text-2xl font-bold">{{ $counts->get($stat['key'], 0) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Filter chips --}}
    @if ($totalRecords > 0)
        <div class="portal-filter-bar mt-4 -mx-4 flex gap-2 overflow-x-auto px-4 pb-2 sm:-mx-6 sm:px-6" id="history-filters" role="group" aria-label="Filter status">
            @foreach ([
                ['value' => 'all', 'label' => 'Semua', 'count' => $totalRecords],
                ['value' => 'hadir', 'label' => 'Hadir', 'count' => $counts->get('hadir', 0)],
                ['value' => 'izin', 'label' => 'Izin', 'count' => $counts->get('izin', 0)],
                ['value' => 'sakit', 'label' => 'Sakit', 'count' => $counts->get('sakit', 0)],
                ['value' => 'alpa', 'label' => 'Alpa', 'count' => $counts->get('alpa', 0)],
            ] as $filter)
                <button
                    type="button"
                    data-filter="{{ $filter['value'] }}"
                    data-active="{{ $filter['value'] === 'all' ? 'true' : 'false' }}"
                    class="portal-filter-chip shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition {{ $filter['value'] === 'all' ? 'bg-sky-600 text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200' }}"
                >
                    {{ $filter['label'] }}&nbsp;<span class="opacity-70">{{ $filter['count'] }}</span>
                </button>
            @endforeach
        </div>
    @endif

    {{-- Records list --}}
    <section class="mt-4 space-y-3" id="history-list">
        @forelse ($records as $record)
            @php $normalized = $normalizeStatus($record->status); @endphp
            <article
                data-status="{{ $normalized }}"
                class="portal-history-item rounded-2xl bg-white p-4 shadow-sm transition"
            >
                <div class="flex items-start gap-3">
                    <span class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ portalStatusBadge($record->status) }}">
                        <span class="h-2.5 w-2.5 rounded-full {{ portalStatusDot($record->status) }}"></span>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h2 class="font-bold text-slate-900">{{ portalStatusLabel($record->status) }}</h2>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    {{ portalFormatDate($record->attendance_date, 'l, d M Y') }}
                                    @if ($record->attendance_time && $record->attendance_time !== '00:00:00')
                                        · {{ substr($record->attendance_time, 0, 5) }}
                                    @endif
                                </p>
                            </div>
                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-semibold ring-1 {{ portalStatusBadge($record->status) }}">
                                {{ portalStatusLabel($record->status) }}
                            </span>
                        </div>

                        @if ($record->note)
                            <div class="mt-3 rounded-xl bg-slate-50 px-3 py-2.5 text-sm text-slate-600">
                                {{ $record->note }}
                            </div>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            @include('portal.partials.student-empty-state', [
                'icon' => 'clock',
                'title' => 'Belum ada data absensi',
                'description' => 'Riwayat absensi bulan ini akan tampil di sini setelah tercatat.',
            ])
        @endforelse
    </section>

    <div id="history-empty-filter" class="portal-empty hidden rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center">
        <p class="text-sm font-semibold text-slate-700">Tidak ada data untuk filter ini</p>
        <p class="mt-1 text-xs text-slate-500">Coba pilih filter lain.</p>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const filters = document.getElementById('history-filters');
            const list = document.getElementById('history-list');
            const emptyFilter = document.getElementById('history-empty-filter');
            if (!filters || !list) return;

            const items = list.querySelectorAll('[data-status]');
            const buttons = filters.querySelectorAll('[data-filter]');

            function setButtonActive(btn, active) {
                // Reset semua class terlebih dahulu lalu set yang benar
                // Ini menghindari bug teks putih di background putih dari toggle tidak sinkron
                btn.className = btn.className
                    .replace(/\bbg-sky-600\b|\bbg-white\b|\btext-white\b|\btext-slate-600\b|\bring-1\b|\bring-slate-200\b/g, '')
                    .trim();

                if (active) {
                    btn.classList.add('bg-sky-600', 'text-white');
                } else {
                    btn.classList.add('bg-white', 'text-slate-600', 'ring-1', 'ring-slate-200');
                }
            }

            function applyFilter(value) {
                let visible = 0;
                items.forEach(function (item) {
                    const show = value === 'all' || item.getAttribute('data-status') === value;
                    item.classList.toggle('hidden', !show);
                    if (show) visible++;
                });

                if (emptyFilter) {
                    emptyFilter.classList.toggle('hidden', visible > 0 || value === 'all');
                }

                buttons.forEach(function (btn) {
                    setButtonActive(btn, btn.getAttribute('data-filter') === value);
                });
            }

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    applyFilter(btn.getAttribute('data-filter'));
                });
            });
        })();
    </script>
@endpush
