@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">📡 Monitoring Alat NFC</h1>
            <p class="text-gray-400">Kelola dan monitor status alat tap-in NFC di berbagai lokasi</p>
        </div>
        <button class="btn-primary">
            + Tambah Alat
        </button>
    </div>
</div>

<!-- Overall Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in">
    <div class="stat-card">
        <p class="stat-label">Total Alat</p>
        <div class="stat-number">4</div>
        <p class="text-xs text-emerald-400 mt-2">Aktif</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Online</p>
        <div class="stat-number">3</div>
        <p class="text-xs text-emerald-400 mt-2">75% Aktif</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Offline</p>
        <div class="stat-number">1</div>
        <p class="text-xs text-yellow-400 mt-2">Perlu Maintenance</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Total Scan Hari Ini</p>
        <div class="stat-number">1.240</div>
        <p class="text-xs text-neon-cyan mt-2">Success Rate: 99.4%</p>
    </div>
</div>

<!-- Device Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Device 1 - Online -->
    <div class="glass-card p-6 rounded-2xl border-2 border-emerald-500/30 hover:border-emerald-500/50 transition-all animate-fade-in">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-white">Pintu Utama</h3>
                <p class="text-xs text-gray-400">Gate 1</p>
            </div>
            <span class="flex items-center gap-1 text-emerald-400 text-sm font-bold">
                <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                Online
            </span>
        </div>

        <!-- Device Icon -->
        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-2xl mb-4">
            📡
        </div>

        <!-- Details -->
        <div class="space-y-3 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">IP Address</span>
                <span class="text-neon-cyan font-mono">192.168.1.100</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Uptime</span>
                <span class="text-white font-semibold">15 jam</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Last Check</span>
                <span class="text-white font-semibold">1 detik lalu</span>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Scan Hari Ini</p>
                <p class="text-lg font-bold text-neon-cyan">245</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Success Rate</p>
                <p class="text-lg font-bold text-emerald-400">100%</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">
                ✏️ Edit
            </button>
            <button class="btn-secondary text-xs flex-1">
                📊 Detail
            </button>
        </div>
    </div>

    <!-- Device 2 - Online -->
    <div class="glass-card p-6 rounded-2xl border-2 border-emerald-500/30 hover:border-emerald-500/50 transition-all animate-fade-in" style="animation-delay: 0.1s;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-white">Pintu Belakang</h3>
                <p class="text-xs text-gray-400">Gate 2</p>
            </div>
            <span class="flex items-center gap-1 text-emerald-400 text-sm font-bold">
                <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                Online
            </span>
        </div>

        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-2xl mb-4">
            📡
        </div>

        <div class="space-y-3 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">IP Address</span>
                <span class="text-neon-cyan font-mono">192.168.1.101</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Uptime</span>
                <span class="text-white font-semibold">14 jam</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Last Check</span>
                <span class="text-white font-semibold">2 detik lalu</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Scan Hari Ini</p>
                <p class="text-lg font-bold text-neon-cyan">189</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Success Rate</p>
                <p class="text-lg font-bold text-emerald-400">98.9%</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">
                ✏️ Edit
            </button>
            <button class="btn-secondary text-xs flex-1">
                📊 Detail
            </button>
        </div>
    </div>

    <!-- Device 3 - Idle -->
    <div class="glass-card p-6 rounded-2xl border-2 border-yellow-500/30 hover:border-yellow-500/50 transition-all animate-fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-white">Kantor TU</h3>
                <p class="text-xs text-gray-400">Gate 3</p>
            </div>
            <span class="flex items-center gap-1 text-yellow-400 text-sm font-bold">
                <span class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></span>
                Idle
            </span>
        </div>

        <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center text-2xl mb-4">
            📡
        </div>

        <div class="space-y-3 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">IP Address</span>
                <span class="text-neon-cyan font-mono">192.168.1.102</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Uptime</span>
                <span class="text-white font-semibold">12 jam</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Last Check</span>
                <span class="text-white font-semibold">30 detik lalu</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Scan Hari Ini</p>
                <p class="text-lg font-bold text-yellow-400">0</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Status</p>
                <p class="text-lg font-bold text-yellow-400">Idle</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">
                ✏️ Edit
            </button>
            <button class="btn-secondary text-xs flex-1">
                📊 Detail
            </button>
        </div>
    </div>

    <!-- Device 4 - Offline -->
    <div class="glass-card p-6 rounded-2xl border-2 border-red-500/30 hover:border-red-500/50 transition-all animate-fade-in" style="animation-delay: 0.3s;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-white">Gudang</h3>
                <p class="text-xs text-gray-400">Gate 4</p>
            </div>
            <span class="flex items-center gap-1 text-red-400 text-sm font-bold">
                <span class="w-3 h-3 bg-red-400 rounded-full animate-pulse"></span>
                Offline
            </span>
        </div>

        <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center text-2xl mb-4 opacity-50">
            📡
        </div>

        <div class="space-y-3 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">IP Address</span>
                <span class="text-gray-500 font-mono">192.168.1.103</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Last Online</span>
                <span class="text-red-400 font-semibold">12 menit lalu</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Status</span>
                <span class="text-red-400 font-semibold">Connection Lost</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Last Scan</p>
                <p class="text-lg font-bold text-gray-500">--</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Downtime</p>
                <p class="text-lg font-bold text-red-400">12m</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">
                ✏️ Edit
            </button>
            <button class="btn-danger text-xs flex-1">
                ⚠️ Alert
            </button>
        </div>
    </div>
</div>

<!-- Device History & Logs -->
<div class="glass-card p-6 rounded-2xl">
    <h3 class="text-lg font-bold text-white mb-6">📋 Riwayat Aktivitas Alat</h3>
    
    <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
        <!-- Log 1 -->
        <div class="flex items-center gap-4 p-4 rounded-xl border border-neon-cyan/10 hover:bg-glass-light/5">
            <div class="w-3 h-3 bg-emerald-400 rounded-full flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white">Gate 1 (Pintu Utama) - Online</p>
                <p class="text-xs text-gray-400">Device terhubung kembali setelah restart</p>
            </div>
            <span class="text-xs text-neon-cyan font-mono whitespace-nowrap">07:30 (1h ago)</span>
        </div>

        <!-- Log 2 -->
        <div class="flex items-center gap-4 p-4 rounded-xl border border-neon-cyan/10 hover:bg-glass-light/5">
            <div class="w-3 h-3 bg-yellow-400 rounded-full flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white">Gate 3 (Kantor TU) - Idle</p>
                <p class="text-xs text-gray-400">Tidak ada aktivitas scan dalam 30 menit terakhir</p>
            </div>
            <span class="text-xs text-neon-cyan font-mono whitespace-nowrap">06:45 (2h ago)</span>
        </div>

        <!-- Log 3 -->
        <div class="flex items-center gap-4 p-4 rounded-xl border border-neon-cyan/10 hover:bg-glass-light/5">
            <div class="w-3 h-3 bg-red-400 rounded-full flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white">Gate 4 (Gudang) - Offline</p>
                <p class="text-xs text-gray-400">Koneksi terputus, perlu pemeriksaan jaringan</p>
            </div>
            <span class="text-xs text-neon-cyan font-mono whitespace-nowrap">06:18 (2.5h ago)</span>
        </div>

        <!-- Log 4 -->
        <div class="flex items-center gap-4 p-4 rounded-xl border border-neon-cyan/10 hover:bg-glass-light/5">
            <div class="w-3 h-3 bg-emerald-400 rounded-full flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white">Gate 2 (Pintu Belakang) - Scan Success</p>
                <p class="text-xs text-gray-400">UID: A1-5F-8C-12 | Silvi Lestari berhasil di-scan</p>
            </div>
            <span class="text-xs text-neon-cyan font-mono whitespace-nowrap">07:44 (58m ago)</span>
        </div>

        <!-- Log 5 -->
        <div class="flex items-center gap-4 p-4 rounded-xl border border-neon-cyan/10 hover:bg-glass-light/5">
            <div class="w-3 h-3 bg-emerald-400 rounded-full flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white">Gate 1 (Pintu Utama) - Scan Success</p>
                <p class="text-xs text-gray-400">UID: E4-27-9B-04 | Rafa Prakasa berhasil di-scan</p>
            </div>
            <span class="text-xs text-neon-cyan font-mono whitespace-nowrap">07:45 (55m ago)</span>
        </div>

        <!-- Log 6 -->
        <div class="flex items-center gap-4 p-4 rounded-xl border border-neon-cyan/10 hover:bg-glass-light/5">
            <div class="w-3 h-3 bg-red-400 rounded-full flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white">Gate 1 (Pintu Utama) - Scan Failed</p>
                <p class="text-xs text-gray-400">Kartu tidak terdaftar - UID: 42-7C-E5-8B</p>
            </div>
            <span class="text-xs text-neon-cyan font-mono whitespace-nowrap">07:05 (1.5h ago)</span>
        </div>
    </div>
</div>
@endsection
