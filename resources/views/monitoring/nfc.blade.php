@extends('layouts.app')

@section('title', 'NFC Monitor — SITEXA Absensi')
@section('page_title', 'NFC Monitor')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gradient mb-2">📡 NFC Real-Time Monitor</h1>
    <p class="text-gray-400">Monitoring tap-in siswa secara real-time dengan visualisasi interaktif</p>
</div>

<!-- Control & Filter -->
<div class="glass-card p-6 rounded-2xl mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
    <div class="flex items-center gap-4 w-full md:w-auto">
        <div class="flex-1 md:flex-initial">
            <select class="input-field text-sm">
                <option>Semua Pintu</option>
                <option>Pintu Utama</option>
                <option>Pintu Belakang</option>
                <option>Kantor TU</option>
            </select>
        </div>
        <div class="flex-1 md:flex-initial">
            <select class="input-field text-sm">
                <option>Semua Status</option>
                <option>Berhasil ✓</option>
                <option>Gagal ✕</option>
                <option>Tidak Terdaftar 🔑</option>
            </select>
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

    <!-- Side Panel - Statistics & Visualization -->
    <div class="space-y-6">
        <!-- Live Status -->
        <div class="glass-card p-6 rounded-2xl">
            <h4 class="text-lg font-bold text-white mb-4">Visualisasi Live</h4>
            
            <!-- Scan Animation -->
            <div class="relative w-32 h-32 mx-auto mb-6">
                <svg class="w-full h-full" viewBox="0 0 100 100">
                    <!-- Background Circle -->
                    <circle cx="50" cy="50" r="45" fill="rgba(0, 217, 255, 0.1)" stroke="rgba(0, 217, 255, 0.3)" stroke-width="0.5"/>
                    
                    <!-- Scanning Circles -->
                    <circle class="animate-pulse" cx="50" cy="50" r="25" fill="none" stroke="rgba(0, 217, 255, 0.8)" stroke-width="1"/>
                    <circle class="animate-pulse" cx="50" cy="50" r="35" fill="none" stroke="rgba(0, 217, 255, 0.4)" stroke-width="1" style="animation-delay: 0.3s;"/>
                    
                    <!-- Center Icon -->
                    <text x="50" y="60" text-anchor="middle" font-size="35" fill="rgba(0, 217, 255, 0.8)">📡</text>
                </svg>
            </div>

            <div class="text-center space-y-2">
                <p class="text-neon-cyan font-mono text-sm font-bold">SCANNING...</p>
                <p class="text-gray-400 text-xs">Menunggu tap kartu NFC</p>
            </div>
        </div>

        <!-- Device Status Monitor -->
        <div class="glass-card p-6 rounded-2xl">
            <h4 class="text-lg font-bold text-white mb-4">Status Perangkat</h4>

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

        <!-- Action Panel -->
        <div class="glass-card p-6 rounded-2xl">
            <h4 class="text-lg font-bold text-white mb-4">Aksi Cepat</h4>
            <div class="space-y-2">
                <button class="btn-secondary w-full text-sm">
                    🔧 Troubleshoot
                </button>
                <button class="btn-secondary w-full text-sm">
                    📊 Export Data
                </button>
                <button class="btn-secondary w-full text-sm">
                    🔴 Berhenti Monitor
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
