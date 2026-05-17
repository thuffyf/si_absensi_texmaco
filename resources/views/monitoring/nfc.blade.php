@extends('layouts.app')

@section('title', 'NFC Monitor — SITEXA Absensi')
@section('page_title', 'NFC Monitor')
@section('page_subtitle', 'Monitoring tap-in siswa secara real-time')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Scan</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">1.240</p>
            <p class="text-xs text-emerald-600 mt-2">Hari ini</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Scan Berhasil</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">1.232</p>
            <p class="text-xs text-emerald-600 mt-2">99.4% Success Rate</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="flex items-center gap-2 text-neon-cyan text-sm font-semibold">
            <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
            Live Monitoring
        </span>
        <button class="btn-secondary text-sm">
            🔄 Refresh
        </button>
    </div>
</div>

<!-- Stats Overview -->
@php
    $successRate = $totalScans > 0 ? round(($successCount / $totalScans) * 100, 1) : 0;
    $errorRate = $totalScans > 0 ? round(($failedCount / $totalScans) * 100, 1) : 0;
@endphp
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in">
    <div class="stat-card">
        <p class="stat-label">Total Scan</p>
        <div class="stat-number">{{ $totalScans }}</div>
        <p class="text-xs text-emerald-400 mt-2">Hari ini</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Scan Berhasil</p>
        <div class="stat-number">{{ $successCount }}</div>
        <p class="text-xs text-emerald-400 mt-2">{{ $successRate }}% Success Rate</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Scan Gagal</p>
        <div class="stat-number">{{ $failedCount }}</div>
        <p class="text-xs text-red-400 mt-2">{{ $errorRate }}% Error Rate</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Kartu Tidak Terdaftar</p>
        <div class="stat-number">{{ $unknownCount }}</div>
        <p class="text-xs text-yellow-400 mt-2">Perlu Verifikasi</p>
    </div>
</div>

<!-- Real-Time Event Stream -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Event Stream -->
    <div class="lg:col-span-2 glass-card p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span class="w-3 h-3 bg-neon-cyan rounded-full animate-pulse"></span>
                Event Stream (Real-Time)
            </h3>
            <span class="text-xs text-gray-400 font-mono">Auto-refresh setiap 2s</span>
        </div>

        <!-- Event Items -->
        <div class="space-y-2 max-h-[600px] overflow-y-auto custom-scrollbar">
            @forelse($events as $event)
                @php
                    $borderClass = match ($event['status']) {
                        'hadir' => 'border-emerald-500/20',
                        'izin' => 'border-yellow-500/20',
                        'sakit', 'alpha' => 'border-red-500/20',
                        default => 'border-slate-500/20',
                    };
                    $iconClass = match ($event['status']) {
                        'hadir' => 'from-emerald-400 to-emerald-500',
                        'izin' => 'from-yellow-400 to-yellow-500',
                        'sakit', 'alpha' => 'from-red-400 to-red-500',
                        default => 'from-slate-400 to-slate-500',
                    };
                    $iconText = match ($event['status']) {
                        'hadir' => '✓',
                        'izin' => '⚠',
                        'sakit', 'alpha' => '✕',
                        default => '•',
                    };
                    $timeClass = match ($event['status']) {
                        'hadir' => 'text-neon-cyan',
                        'izin' => 'text-yellow-400',
                        'sakit', 'alpha' => 'text-red-400',
                        default => 'text-gray-400',
                    };
                @endphp
                <div class="flex items-center gap-4 p-4 rounded-xl border {{ $borderClass }} hover:bg-glass-light/5 transition-all group">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $iconClass }} flex items-center justify-center text-white font-bold shadow-glow-cyan-sm">{{ $iconText }}</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-white truncate">{{ $event['student_name'] }}</p>
                                <p class="text-xs text-gray-400">NIS: {{ $event['nis'] }} | {{ $event['class_name'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">UID Card: {{ $event['uid_kartu'] }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="{{ $event['badge_class'] }}">{{ $event['status_label'] }}</span>
                                <p class="text-xs {{ $timeClass }} font-mono mt-1">{{ $event['time'] }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-2 text-xs">
                            <span class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300">{{ $event['device_name'] }}</span>
                            <span class="px-2 py-1 rounded bg-neon-blue/20 text-neon-blue">Scan OK</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-gray-400">
                    Belum ada event absensi hari ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Device Status -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Status Perangkat</h3>
        <div class="space-y-3">
            @forelse($devices as $device)
                @php
                    $statusBorder = match ($device->status) {
                        'online' => 'border-emerald-500/30',
                        'idle' => 'border-yellow-500/30',
                        'offline' => 'border-red-500/30',
                        default => 'border-slate-500/30',
                    };
                    $statusDot = match ($device->status) {
                        'online' => 'bg-emerald-400',
                        'idle' => 'bg-yellow-400',
                        'offline' => 'bg-red-400',
                        default => 'bg-slate-400',
                    };
                @endphp
                <div class="p-3 rounded-lg bg-glass-light/10 border {{ $statusBorder }}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-white">{{ $device->name }}</span>
                        <span class="w-2 h-2 {{ $statusDot }} rounded-full animate-pulse"></span>
                    </div>
                    <p class="text-xs text-gray-400">Scans: <span class="text-neon-cyan font-bold">{{ $device->scan_today }}</span></p>
                </div>
            @empty
                <div class="text-xs text-gray-400">Belum ada perangkat NFC.</div>
            @endforelse
        </div>
    </div>
</div>

    <!-- Control & Filter -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap gap-2">
            <form method="GET" action="{{ route('monitoring.nfc') }}" class="flex gap-2">
                <select name="gate" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">Semua Pintu</option>
                    <option value="main" @selected(request('gate') === 'main')">Pintu Utama</option>
                    <option value="back" @selected(request('gate') === 'back')">Pintu Belakang</option>
                    <option value="office" @selected(request('gate') === 'office')">Kantor TU</option>
                </select>
                <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">Semua Status</option>
                    <option value="success" @selected(request('status') === 'success')">Berhasil</option>
                    <option value="failed" @selected(request('status') === 'failed')">Gagal</option>
                    <option value="unregistered" @selected(request('status') === 'unregistered')">Tidak Terdaftar</option>
                </select>
                <button type="submit" class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('monitoring.nfc') }}" class="flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Reset</a>
            </form>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex items-center gap-2 text-sky-600 text-sm font-semibold">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Live Monitoring
            </span>
            <button class="flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Event Stream Table -->
    <div class="flex flex-col rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">NIS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">UID Card</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Pintu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $statusClasses = [
                            'hadir' => 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800',
                            'izin' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                            'sakit' => 'inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800',
                            'alpha' => 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800',
                            'error' => 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800',
                        ];
                    @endphp

            <div class="space-y-3">
                @forelse($devices as $device)
                    @php
                        $statusBorder = match ($device->status) {
                            'online' => 'border-emerald-500/30',
                            'idle' => 'border-yellow-500/30',
                            'offline' => 'border-red-500/30',
                            default => 'border-slate-500/30',
                        };
                        $statusDot = match ($device->status) {
                            'online' => 'bg-emerald-400',
                            'idle' => 'bg-yellow-400',
                            'offline' => 'bg-red-400',
                            default => 'bg-slate-400',
                        };
                    @endphp
                    <div class="p-3 rounded-lg bg-glass-light/10 border {{ $statusBorder }}">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-white">{{ $device->name }}</span>
                            <span class="w-2 h-2 {{ $statusDot }} rounded-full animate-pulse"></span>
                        </div>
                        <p class="text-xs text-gray-400">Scans: <span class="text-neon-cyan font-bold">{{ $device->scan_today }}</span></p>
                    </div>
                @empty
                    <div class="text-xs text-gray-400">Belum ada perangkat NFC.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Device Status -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Status Perangkat NFC</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-slate-900">Gate 1</span>
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                </div>
                <p class="text-xs text-slate-600">Scans: <span class="font-semibold text-slate-900">245</span></p>
                <p class="text-xs text-emerald-600 mt-1">Online</p>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-slate-900">Gate 2</span>
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                </div>
                <p class="text-xs text-slate-600">Scans: <span class="font-semibold text-slate-900">189</span></p>
                <p class="text-xs text-emerald-600 mt-1">Online</p>
            </div>
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-slate-900">Gate 3</span>
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                </div>
                <p class="text-xs text-slate-600">Scans: <span class="font-semibold text-slate-900">0</span></p>
                <p class="text-xs text-amber-600 mt-1">Idle</p>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-slate-900">Gate 4</span>
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                </div>
                <p class="text-xs text-slate-600">Status: <span class="font-semibold text-red-600">Offline</span></p>
                <p class="text-xs text-red-600 mt-1">Perlu perhatian</p>
            </div>
        </div>
    </div>
</div>
@endsection
