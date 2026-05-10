@extends('layouts.app')

@section('content')
<!-- Dashboard Header -->
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gradient mb-2">Selamat datang kembali, Admin! 👋</h1>
    <p class="text-gray-400">Monitoring absensi siswa real-time | Kamis, 09 Mei 2024</p>
</div>

<!-- Top Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-in">
    <!-- Stat 1: Siswa Hadir -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Siswa Hadir</p>
                <div class="stat-number">285</div>
                <div class="stat-change positive">
                    <span>↑</span> 12 dari kemarin
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-2xl shadow-glow-cyan-sm">
                ✓
            </div>
        </div>
    </div>

    <!-- Stat 2: Siswa Izin -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Siswa Izin</p>
                <div class="stat-number">28</div>
                <div class="stat-change positive">
                    <span>↓</span> 5 dari kemarin
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center text-2xl shadow-glow-cyan-sm">
                📋
            </div>
        </div>
    </div>

    <!-- Stat 3: Siswa Sakit -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Siswa Sakit</p>
                <div class="stat-number">15</div>
                <div class="stat-change negative">
                    <span>↑</span> 3 dari kemarin
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center text-2xl shadow-glow-cyan-sm">
                🏥
            </div>
        </div>
    </div>

    <!-- Stat 4: Siswa Alpha -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Siswa Alpha</p>
                <div class="stat-number">12</div>
                <div class="stat-change negative">
                    <span>↑</span> 2 dari kemarin
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-neon-blue/20 flex items-center justify-center text-2xl shadow-glow-cyan-sm">
                ✕
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-fade-in">
    <!-- Scan NFC Berhasil -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Scan Berhasil</p>
                <div class="stat-number">1.240</div>
                <p class="text-xs text-gray-400 mt-2">Total scan hari ini</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-xl">📡</div>
        </div>
    </div>

    <!-- Scan Gagal -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Scan Gagal</p>
                <div class="stat-number">8</div>
                <p class="text-xs text-gray-400 mt-2">Perlu dikonfirmasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center text-xl">⚠️</div>
        </div>
    </div>

    <!-- Kartu Tidak Terdaftar -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Kartu Tidak Terdaftar</p>
                <div class="stat-number">3</div>
                <p class="text-xs text-gray-400 mt-2">Perlu pendaftaran</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center text-xl">🔑</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-in">
    <!-- Attendance Chart -->
    <div class="glass-card p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-white">Grafik Absensi Mingguan</h3>
            <select class="input-field text-sm py-2 px-3 w-32">
                <option>Minggu ini</option>
                <option>Bulan ini</option>
            </select>
        </div>
        
        <!-- Chart -->
        <div class="h-64 flex items-end gap-2 justify-between">
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 70%;" title="Senin: 285 siswa"></div>
                <p class="text-xs text-gray-400 mt-2">Senin</p>
                <p class="text-sm font-bold text-neon-cyan">285</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 75%;" title="Selasa: 290 siswa"></div>
                <p class="text-xs text-gray-400 mt-2">Selasa</p>
                <p class="text-sm font-bold text-neon-cyan">290</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 68%;" title="Rabu: 275 siswa"></div>
                <p class="text-xs text-gray-400 mt-2">Rabu</p>
                <p class="text-sm font-bold text-neon-cyan">275</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 72%;" title="Kamis: 280 siswa"></div>
                <p class="text-xs text-gray-400 mt-2">Kamis</p>
                <p class="text-sm font-bold text-neon-cyan">280</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 65%;" title="Jumat: 260 siswa"></div>
                <p class="text-xs text-gray-400 mt-2">Jumat</p>
                <p class="text-sm font-bold text-neon-cyan">260</p>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Distribusi Status Absensi</h3>
        
        <div class="space-y-4">
            <!-- Hadir -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">Hadir</span>
                    <span class="text-sm font-bold text-neon-cyan">89%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-neon-cyan to-neon-blue w-[89%]" style="width: 89%;"></div>
                </div>
            </div>

            <!-- Izin -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">Izin</span>
                    <span class="text-sm font-bold text-yellow-300">7%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500" style="width: 7%;"></div>
                </div>
            </div>

            <!-- Sakit -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">Sakit</span>
                    <span class="text-sm font-bold text-red-300">3%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-red-400 to-red-500" style="width: 3%;"></div>
                </div>
            </div>

            <!-- Alpha -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">Alpha</span>
                    <span class="text-sm font-bold text-neon-blue">1%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-neon-blue to-neon-blue" style="width: 1%;"></div>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-neon-cyan/10">
            <div>
                <p class="text-2xl font-bold text-gradient">340</p>
                <p class="text-xs text-gray-400">Total Siswa</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-neon-cyan">93.5%</p>
                <p class="text-xs text-gray-400">Kehadiran</p>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Monitor Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
    <!-- Recent Activity -->
    <div class="lg:col-span-2 glass-card p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                Aktivitas Real-Time (5 Menit Terakhir)
            </h3>
            <a href="#" class="text-neon-cyan text-sm hover:text-neon-cyan/80">Lihat Semua →</a>
        </div>

        <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
            <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-glass-light/10 border border-neon-cyan/10 transition-all animate-slide-in">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-neon-cyan to-neon-blue flex items-center justify-center font-bold shadow-glow-cyan-sm">👨</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">Rafa Prakasa</p>
                    <p class="text-xs text-gray-400">NIM: 12001 | Kelas: XII IPA 1</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="badge-success">Hadir</span>
                    <p class="text-xs text-gray-400 mt-1">07:45:23</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-glass-light/10 border border-neon-cyan/10 transition-all animate-slide-in" style="animation-delay: 0.1s;">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-neon-purple to-neon-cyan flex items-center justify-center font-bold shadow-glow-cyan-sm">👩</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">Silvi Lestari</p>
                    <p class="text-xs text-gray-400">NIM: 12002 | Kelas: XII IPA 1</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="badge-success">Hadir</span>
                    <p class="text-xs text-gray-400 mt-1">07:44:52</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-glass-light/10 border border-yellow-500/10 transition-all animate-slide-in" style="animation-delay: 0.2s;">
                <div class="w-12 h-12 rounded-full bg-gray-500 flex items-center justify-center font-bold">👨</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">Adi Pratama</p>
                    <p class="text-xs text-gray-400">NIM: 12003 | Kelas: XII IPA 1</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="badge-warning">Izin</span>
                    <p class="text-xs text-gray-400 mt-1">07:43:15</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-glass-light/10 border border-neon-cyan/10 transition-all animate-slide-in" style="animation-delay: 0.3s;">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-neon-purple flex items-center justify-center font-bold shadow-glow-cyan-sm">👩</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">Mira Putri</p>
                    <p class="text-xs text-gray-400">NIM: 12004 | Kelas: XII IPA 2</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="badge-success">Hadir</span>
                    <p class="text-xs text-gray-400 mt-1">07:42:41</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-glass-light/10 border border-red-500/10 transition-all animate-slide-in" style="animation-delay: 0.4s;">
                <div class="w-12 h-12 rounded-full bg-gray-500 flex items-center justify-center font-bold">👨</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">Danu Wijaya</p>
                    <p class="text-xs text-gray-400">NIM: 12005 | Kelas: XII IPA 2</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="badge-danger">Sakit</span>
                    <p class="text-xs text-gray-400 mt-1">07:15:30</p>
                </div>
            </div>
        </div>
    </div>

    <!-- NFC Device Status -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Status Alat NFC</h3>

        <div class="space-y-4">
            <!-- Device 1 -->
            <div class="p-4 rounded-xl bg-glass-light/10 border border-emerald-500/30">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-white">Pintu Utama</p>
                        <p class="text-xs text-gray-400">Gate 1</p>
                    </div>
                    <span class="flex items-center gap-1 text-emerald-400 text-sm font-bold">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Online
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Scan:</span>
                        <span class="text-white font-semibold">245</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Last Scan:</span>
                        <span class="text-neon-cyan">1s ago</span>
                    </div>
                </div>
            </div>

            <!-- Device 2 -->
            <div class="p-4 rounded-xl bg-glass-light/10 border border-emerald-500/30">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-white">Pintu Belakang</p>
                        <p class="text-xs text-gray-400">Gate 2</p>
                    </div>
                    <span class="flex items-center gap-1 text-emerald-400 text-sm font-bold">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Online
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Scan:</span>
                        <span class="text-white font-semibold">189</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Last Scan:</span>
                        <span class="text-neon-cyan">3s ago</span>
                    </div>
                </div>
            </div>

            <!-- Device 3 -->
            <div class="p-4 rounded-xl bg-glass-light/10 border border-yellow-500/30">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-white">Kantor TU</p>
                        <p class="text-xs text-gray-400">Gate 3</p>
                    </div>
                    <span class="flex items-center gap-1 text-yellow-400 text-sm font-bold">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                        Idle
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Scan:</span>
                        <span class="text-white font-semibold">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Last Scan:</span>
                        <span class="text-gray-500">--</span>
                    </div>
                </div>
            </div>

            <!-- Device 4 -->
            <div class="p-4 rounded-xl bg-glass-light/10 border border-red-500/30">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-white">Gudang</p>
                        <p class="text-xs text-gray-400">Gate 4</p>
                    </div>
                    <span class="flex items-center gap-1 text-red-400 text-sm font-bold">
                        <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                        Offline
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Scan:</span>
                        <span class="text-white font-semibold">125</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Last Online:</span>
                        <span class="text-red-400">12m ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
