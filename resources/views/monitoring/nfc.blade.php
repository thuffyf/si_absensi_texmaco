@extends('layouts.app')

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
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in">
    <div class="stat-card">
        <p class="stat-label">Total Scan</p>
        <div class="stat-number">1.240</div>
        <p class="text-xs text-emerald-400 mt-2">Hari ini</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Scan Berhasil</p>
        <div class="stat-number">1.232</div>
        <p class="text-xs text-emerald-400 mt-2">99.4% Success Rate</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Scan Gagal</p>
        <div class="stat-number">8</div>
        <p class="text-xs text-red-400 mt-2">0.6% Error Rate</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Kartu Tidak Terdaftar</p>
        <div class="stat-number">0</div>
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
            <!-- Event 1 - Success -->
            <div class="flex items-center gap-4 p-4 rounded-xl border border-emerald-500/20 hover:bg-glass-light/5 transition-all group animate-slide-in">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white font-bold shadow-glow-cyan-sm">✓</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-white truncate">Rafa Prakasa</p>
                            <p class="text-xs text-gray-400">NIM: 12001 | XII IPA 1</p>
                            <p class="text-xs text-gray-500 mt-1">UID Card: E4-27-9B-04</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="badge-success">Hadir</span>
                            <p class="text-xs text-neon-cyan font-mono mt-1">07:45:23</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300">Pintu Utama</span>
                        <span class="px-2 py-1 rounded bg-neon-blue/20 text-neon-blue">Scan OK</span>
                    </div>
                </div>
            </div>

            <!-- Event 2 - Success -->
            <div class="flex items-center gap-4 p-4 rounded-xl border border-emerald-500/20 hover:bg-glass-light/5 transition-all group animate-slide-in" style="animation-delay: 0.1s;">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white font-bold shadow-glow-cyan-sm">✓</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-white truncate">Silvi Lestari</p>
                            <p class="text-xs text-gray-400">NIM: 12002 | XII IPA 1</p>
                            <p class="text-xs text-gray-500 mt-1">UID Card: A1-5F-8C-12</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="badge-success">Hadir</span>
                            <p class="text-xs text-neon-cyan font-mono mt-1">07:44:52</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300">Pintu Utama</span>
                        <span class="px-2 py-1 rounded bg-neon-blue/20 text-neon-blue">Scan OK</span>
                    </div>
                </div>
            </div>

            <!-- Event 3 - Warning -->
            <div class="flex items-center gap-4 p-4 rounded-xl border border-yellow-500/20 hover:bg-glass-light/5 transition-all group animate-slide-in" style="animation-delay: 0.2s;">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-white font-bold shadow-glow-cyan-sm">⚠</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-white truncate">Adi Pratama</p>
                            <p class="text-xs text-gray-400">NIM: 12003 | XII IPA 1</p>
                            <p class="text-xs text-gray-500 mt-1">UID Card: D3-8A-1F-67</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="badge-warning">Izin</span>
                            <p class="text-xs text-yellow-400 font-mono mt-1">07:43:15</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded bg-yellow-500/20 text-yellow-300">Pintu Utama</span>
                        <span class="px-2 py-1 rounded bg-yellow-500/20 text-yellow-300">Izin Terverifikasi</span>
                    </div>
                </div>
            </div>

            <!-- Event 4 - Success -->
            <div class="flex items-center gap-4 p-4 rounded-xl border border-emerald-500/20 hover:bg-glass-light/5 transition-all group animate-slide-in" style="animation-delay: 0.3s;">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white font-bold shadow-glow-cyan-sm">✓</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-white truncate">Mira Putri</p>
                            <p class="text-xs text-gray-400">NIM: 12004 | XII IPA 2</p>
                            <p class="text-xs text-gray-500 mt-1">UID Card: F2-B4-3E-09</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="badge-success">Hadir</span>
                            <p class="text-xs text-neon-cyan font-mono mt-1">07:42:41</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300">Pintu Belakang</span>
                        <span class="px-2 py-1 rounded bg-neon-blue/20 text-neon-blue">Scan OK</span>
                    </div>
                </div>
            </div>

            <!-- Event 5 - Danger -->
            <div class="flex items-center gap-4 p-4 rounded-xl border border-red-500/20 hover:bg-glass-light/5 transition-all group animate-slide-in" style="animation-delay: 0.4s;">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-400 to-red-500 flex items-center justify-center text-white font-bold shadow-glow-cyan-sm">✕</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-white truncate">Danu Wijaya</p>
                            <p class="text-xs text-gray-400">NIM: 12005 | XII IPA 2</p>
                            <p class="text-xs text-gray-500 mt-1">UID Card: C6-9D-5A-14</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="badge-danger">Sakit</span>
                            <p class="text-xs text-red-400 font-mono mt-1">07:15:30</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded bg-red-500/20 text-red-300">Pintu Utama</span>
                        <span class="px-2 py-1 rounded bg-red-500/20 text-red-300">Sakit Terverifikasi</span>
                    </div>
                </div>
            </div>

            <!-- Event 6 - Error -->
            <div class="flex items-center gap-4 p-4 rounded-xl border border-red-500/20 hover:bg-glass-light/5 transition-all group animate-slide-in" style="animation-delay: 0.5s;">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-bold shadow-lg">!</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-white truncate">Error: Kartu Tidak Terdaftar</p>
                            <p class="text-xs text-gray-400">UID: 42-7C-E5-8B (Unknown)</p>
                            <p class="text-xs text-gray-500 mt-1">Diperlukan pendaftaran ulang kartu</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="badge-danger">Error</span>
                            <p class="text-xs text-red-400 font-mono mt-1">07:05:18</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded bg-red-500/20 text-red-300">Pintu Utama</span>
                        <button class="px-2 py-1 rounded bg-neon-cyan/20 text-neon-cyan hover:bg-neon-cyan/30">Daftarkan Kartu</button>
                    </div>
                </div>
            </div>
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
                <!-- Device 1 -->
                <div class="p-3 rounded-lg bg-glass-light/10 border border-emerald-500/30">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-white">Gate 1</span>
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    </div>
                    <p class="text-xs text-gray-400">Scans: <span class="text-neon-cyan font-bold">245</span></p>
                </div>

                <!-- Device 2 -->
                <div class="p-3 rounded-lg bg-glass-light/10 border border-emerald-500/30">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-white">Gate 2</span>
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    </div>
                    <p class="text-xs text-gray-400">Scans: <span class="text-neon-cyan font-bold">189</span></p>
                </div>

                <!-- Device 3 -->
                <div class="p-3 rounded-lg bg-glass-light/10 border border-yellow-500/30">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-white">Gate 3</span>
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                    </div>
                    <p class="text-xs text-gray-400">Scans: <span class="text-gray-500">0</span></p>
                </div>

                <!-- Device 4 -->
                <div class="p-3 rounded-lg bg-glass-light/10 border border-red-500/30">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-white">Gate 4</span>
                        <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                    </div>
                    <p class="text-xs text-gray-400">Status: <span class="text-red-400 font-bold">Offline</span></p>
                </div>
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
