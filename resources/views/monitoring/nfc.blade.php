@extends('layouts.app')

@section('title', 'NFC Monitor — SITEXA Absensi')
@section('page_title', 'NFC Monitor')
@section('page_subtitle', 'Monitoring tap-in siswa secara real-time')

@section('content')
@php
    $successRate = $totalScans > 0 ? round(($successCount / $totalScans) * 100, 1) : 0;
    $errorRate = $totalScans > 0 ? round(($failedCount / $totalScans) * 100, 1) : 0;
@endphp

<div class="mx-auto flex w-full max-w-none flex-col gap-3 lg:h-full lg:min-h-0 lg:gap-3 lg:overflow-hidden">
    <div class="grid min-h-0 flex-1 grid-cols-1 gap-3 lg:grid-cols-12 lg:gap-4 lg:overflow-hidden">
        <!-- Main Content -->
        <section class="flex min-h-0 flex-col lg:col-span-9 lg:overflow-hidden order-2 lg:order-1">
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                            Auto-refresh setiap 2s
                        </span>
                        <span class="rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-800">Hari ini</span>
                    </div>
                </div>

                <div class="mt-3 grid shrink-0 grid-cols-2 gap-2 sm:grid-cols-4 lg:mt-4 lg:gap-3">
                    <div class="rounded-xl border border-slate-100 bg-gradient-to-br from-slate-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Total Scan</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900 lg:text-3xl" id="total-scans">{{ $totalScans }}</p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800 lg:text-xs">Scan Berhasil</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-900 lg:text-3xl" id="success-count">{{ $successCount }}</p>
                        <p class="mt-0.5 text-xs text-emerald-800/90 lg:text-sm" id="success-rate">{{ $successRate }}% Success Rate</p>
                    </div>
                    <div class="rounded-xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-red-800 lg:text-xs">Scan Gagal</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-red-900 lg:text-3xl" id="failed-count">{{ $failedCount }}</p>
                        <p class="mt-0.5 text-xs text-red-800/90 lg:text-sm" id="error-rate">{{ $errorRate }}% Error Rate</p>
                    </div>
                    <div class="rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-800 lg:text-xs">Kartu Tidak Terdaftar</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-amber-900 lg:text-3xl" id="unknown-count">{{ $unknownCount }}</p>
                        <p class="mt-0.5 text-xs text-amber-800/90 lg:text-sm">Perlu Verifikasi</p>
                    </div>
                </div>

                <div class="mt-3 min-h-[400px] lg:min-h-0 flex-1 overflow-hidden rounded-xl border border-slate-100 bg-slate-50/40 p-2.5 lg:mt-4 lg:flex lg:items-stretch lg:justify-center lg:p-3">
                    <div class="h-full w-full overflow-y-auto space-y-2" id="events-container">
                        @forelse($events as $event)
                            @php
                                $borderClass = match ($event['status']) {
                                    'hadir' => 'border-emerald-200',
                                    'izin' => 'border-amber-200',
                                       'sakit', 'alpa' => 'border-red-200',
                                    'unregistered' => 'border-amber-300',
                                    default => 'border-slate-200',
                                };
                                $iconClass = match ($event['status']) {
                                    'hadir' => 'bg-emerald-100 text-emerald-700',
                                    'izin' => 'bg-amber-100 text-amber-700',
                                       'sakit', 'alpa' => 'bg-red-100 text-red-700',
                                    'unregistered' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                                $iconText = match ($event['status']) {
                                    'hadir' => '✓',
                                    'izin' => '⚠',
                                       'sakit', 'alpa' => '✕',
                                    'unregistered' => '?',
                                    default => '•',
                                };
                                $badgeClass = match ($event['status']) {
                                    'hadir' => 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800',
                                    'izin' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                                       'sakit', 'alpa' => 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800',
                                    'unregistered' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                                    default => 'inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800',
                                };
                                $scanStatus = $event['is_unregistered'] ?? false ? 'Scan Gagal' : 'Scan OK';
                                $scanStatusClass = $event['is_unregistered'] ?? false ? 'bg-amber-100 text-amber-700' : 'bg-sky-100 text-sky-700';
                            @endphp
                            <div class="flex items-start gap-3 rounded-xl border {{ $borderClass }} bg-white p-3 hover:bg-slate-50 transition-colors">
                                <div class="flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-full {{ $iconClass }} font-bold text-sm sm:text-lg">
                                    {{ $iconText }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-semibold text-slate-900 lg:text-base">{{ $event['student_name'] }}</p>
                                            @if(!$event['is_unregistered'] ?? false)
                                                <p class="text-xs text-slate-500 lg:text-sm">NIS: {{ $event['nis'] }} | {{ $event['class_name'] }}</p>
                                            @endif
                                            <p class="text-xs text-slate-400 mt-1">UID: {{ $event['uid_kartu'] }}</p>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            <span class="{{ $badgeClass }}">{{ $event['status_label'] }}</span>
                                            <p class="mt-1 text-xs font-mono text-slate-500 lg:text-sm">{{ $event['time'] }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-emerald-700">{{ $event['device_name'] }}</span>
                                        @if($event['status'] === 'hadir' || ($event['is_unregistered'] ?? false))
                                            <span class="rounded-full {{ $scanStatusClass }} px-2 py-0.5">{{ $scanStatus }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                                Belum ada event absensi hari ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- Sidebar -->
        <div class="flex min-h-0 flex-col gap-4 lg:col-span-3 lg:gap-4 lg:overflow-hidden order-1 lg:order-2">
            <section class="shrink-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <h2 class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Status Perangkat</h2>
                <div class="mt-3 space-y-3" id="devices-container">
                    @forelse($devices as $device)
                        @php
                            $statusBorder = match ($device->status) {
                                'online' => 'border-emerald-200',
                                'idle' => 'border-amber-200',
                                'offline' => 'border-red-200',
                                default => 'border-slate-200',
                            };
                            $statusDot = match ($device->status) {
                                'online' => 'bg-emerald-500',
                                'idle' => 'bg-amber-500',
                                'offline' => 'bg-red-500',
                                default => 'bg-slate-500',
                            };
                            $statusText = match ($device->status) {
                                'online' => 'Online',
                                'idle' => 'Idle',
                                'offline' => 'Offline',
                                default => ucfirst($device->status),
                            };
                        @endphp
                        <div class="rounded-lg border {{ $statusBorder }} bg-slate-50 p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-slate-900">{{ $device->name }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 {{ $statusDot }} rounded-full animate-pulse"></span>
                                    <span class="text-xs text-slate-600">{{ $statusText }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500">Scans hari ini: <span class="font-semibold text-slate-900">{{ $device->scan_today }}</span></p>
                        </div>
                    @empty
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-center text-xs text-slate-500">
                            Belum ada perangkat NFC.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh setiap 2 detik menggunakan AJAX
    (function () {
        let timer = null;
        let consecutiveErrors = 0;
        let delayMs = 2000;

        function scheduleNext(ms) {
            if (timer) window.clearTimeout(timer);
            timer = window.setTimeout(refresh, ms);
        }

        async function refresh() {
            if (document.hidden) {
                scheduleNext(2000);
                return;
            }

            try {

                const response = await fetch('{{ route('monitoring.nfc-data') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Request gagal');
                }

                const data = await response.json();

                if (!data || !Array.isArray(data.events) || !Array.isArray(data.devices)) {
                    throw new Error('Response monitoring tidak valid');
                }

                // Update stats
                document.getElementById('total-scans').textContent = data.totalScans;
                document.getElementById('success-count').textContent = data.successCount;
                document.getElementById('failed-count').textContent = data.failedCount;
                document.getElementById('unknown-count').textContent = data.unknownCount;

                const successRate = data.totalScans > 0 ? ((data.successCount / data.totalScans) * 100).toFixed(1) : '0.0';
                const errorRate = data.totalScans > 0 ? ((data.failedCount / data.totalScans) * 100).toFixed(1) : '0.0';
                document.getElementById('success-rate').textContent = successRate + '% Success Rate';
                document.getElementById('error-rate').textContent = errorRate + '% Error Rate';

                // Update events
                const eventsContainer = document.getElementById('events-container');
                const previousScrollTop = eventsContainer.scrollTop;
                const previousScrollHeight = eventsContainer.scrollHeight;
                const isUserScrollingList = previousScrollTop > 0;

                let eventsHtml = '';

                data.events.forEach(function(event) {
                    const borderClass = {
                        'hadir': 'border-emerald-200',
                        'izin': 'border-amber-200',
                           'sakit': 'border-red-200',
                           'alpa': 'border-red-200',
                        'unregistered': 'border-amber-300',
                    }[event.status] || 'border-slate-200';
                    
                    const iconClass = {
                        'hadir': 'bg-emerald-100 text-emerald-700',
                        'izin': 'bg-amber-100 text-amber-700',
                           'sakit': 'bg-red-100 text-red-700',
                           'alpa': 'bg-red-100 text-red-700',
                        'unregistered': 'bg-amber-100 text-amber-700',
                    }[event.status] || 'bg-slate-100 text-slate-700';
                    
                    const iconText = {
                        'hadir': '✓',
                        'izin': '⚠',
                           'sakit': '✕',
                           'alpa': '✕',
                        'unregistered': '?',
                    }[event.status] || '•';
                    
                    const badgeClass = {
                        'hadir': 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800',
                        'izin': 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                           'sakit': 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800',
                           'alpa': 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800',
                        'unregistered': 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                    }[event.status] || 'inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800';
                    
                    const scanStatus = event.is_unregistered ? 'Scan Gagal' : 'Scan OK';
                    const scanStatusClass = event.is_unregistered ? 'bg-amber-100 text-amber-700' : 'bg-sky-100 text-sky-700';
                    
                    eventsHtml += `
                        <div class="flex items-start gap-3 rounded-xl border ${borderClass} bg-white p-3 hover:bg-slate-50 transition-colors">
                            <div class="flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-full ${iconClass} font-bold text-sm sm:text-lg">
                                ${iconText}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-900 lg:text-base">${event.student_name}</p>
                                        ${!event.is_unregistered ? `<p class="text-xs text-slate-500 lg:text-sm">NIS: ${event.nis} | ${event.class_name}</p>` : ''}
                                        <p class="text-xs text-slate-400 mt-1">UID: ${event.uid_kartu}</p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <span class="${badgeClass}">${event.status_label}</span>
                                        <p class="mt-1 text-xs font-mono text-slate-500 lg:text-sm">${event.time}</p>
                                    </div>
                                </div>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-emerald-700">${event.device_name}</span>
                                    ${(event.status === 'hadir' || event.is_unregistered) ? `<span class="rounded-full ${scanStatusClass} px-2 py-0.5">${scanStatus}</span>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });

                eventsContainer.innerHTML = eventsHtml || `
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                        Belum ada event absensi hari ini.
                    </div>
                `;

                // Kalau user sedang scroll untuk melihat history, jangan bikin tampilan "hilang"
                if (isUserScrollingList) {
                    const maxScrollTop = Math.max(0, eventsContainer.scrollHeight - eventsContainer.clientHeight);
                    // Pertahankan posisi scroll relatif
                    const ratio = previousScrollHeight > 0 ? (previousScrollTop / previousScrollHeight) : 0;
                    eventsContainer.scrollTop = Math.min(maxScrollTop, Math.round(ratio * eventsContainer.scrollHeight));
                } else {
                    eventsContainer.scrollTop = 0;
                }
                
                // Update devices
                const devicesContainer = document.getElementById('devices-container');
                let devicesHtml = '';
                
                data.devices.forEach(function(device) {
                    const statusBorder = {
                        'online': 'border-emerald-200',
                        'idle': 'border-amber-200',
                        'offline': 'border-red-200',
                    }[device.status] || 'border-slate-200';
                    
                    const statusDot = {
                        'online': 'bg-emerald-500',
                        'idle': 'bg-amber-500',
                        'offline': 'bg-red-500',
                    }[device.status] || 'bg-slate-500';
                    
                    const statusText = {
                        'online': 'Online',
                        'idle': 'Idle',
                        'offline': 'Offline',
                    }[device.status] || device.status;
                    
                    const deviceHtml = `
                        <div class="rounded-lg border ${statusBorder} bg-slate-50 p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-slate-900">${device.name}</span>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 ${statusDot} rounded-full animate-pulse"></span>
                                    <span class="text-xs text-slate-600">${statusText}</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500">Scans hari ini: <span class="font-semibold text-slate-900">${device.scan_today}</span></p>
                        </div>
                    `;
                    devicesHtml += deviceHtml;
                });

                devicesContainer.innerHTML = devicesHtml || `
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-center text-xs text-slate-500">
                        Belum ada perangkat NFC.
                    </div>
                `;
                consecutiveErrors = 0;
                delayMs = 2000;
            } catch (error) {
                consecutiveErrors += 1;
                delayMs = Math.min(15000, 2000 * Math.pow(2, consecutiveErrors));
            } finally {
                scheduleNext(delayMs);
            }
        }

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                scheduleNext(0);
            }
        });

        scheduleNext(0);
    })();
</script>
@endpush
@endsection
