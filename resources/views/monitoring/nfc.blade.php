@extends('layouts.app')

@section('title', 'NFC Monitor — SITEXA Absensi')
@section('page_title', 'NFC Monitor')
@section('page_subtitle', 'Monitoring tap-in siswa secara real-time')

@section('content')
<!-- Stats Overview -->
@php
    $successRate = $totalScans > 0 ? round(($successCount / $totalScans) * 100, 1) : 0;
    $errorRate = $totalScans > 0 ? round(($failedCount / $totalScans) * 100, 1) : 0;
@endphp
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
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

    <!-- Device Status Sidebar -->
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
                <div class="text-xs text-gray-400">Belum ada perangkat NFC. (Total: 0/1)</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

