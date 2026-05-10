@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">📅 Data Jadwal Kelas</h1>
            <p class="text-gray-400">Kelola jadwal belajar dan monitoring kehadiran guru</p>
        </div>
        <button class="btn-primary">
            + Tambah Jadwal
        </button>
    </div>
</div>

<!-- Filter -->
<div class="glass-card p-6 rounded-2xl mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select class="input-field text-sm">
            <option>Semua Kelas</option>
            <option>XII IPA 1</option>
            <option>XII IPA 2</option>
            <option>XII IPS 1</option>
        </select>
        <select class="input-field text-sm">
            <option>Semua Guru</option>
            <option>Budi Santoso</option>
            <option>Siti Nurhaliza</option>
        </select>
        <select class="input-field text-sm">
            <option>Semua Mata Pelajaran</option>
            <option>Matematika</option>
            <option>Bahasa Indonesia</option>
            <option>Fisika</option>
        </select>
        <button class="btn-secondary text-sm">
            🔍 Filter
        </button>
    </div>
</div>

<!-- Schedule Grid by Day -->
<div class="space-y-6">
    <!-- Monday -->
    <div>
        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <span class="w-3 h-3 bg-neon-cyan rounded-full"></span>
            Senin, 08 Mei 2024
        </h3>
        <div class="space-y-3">
            <!-- Schedule 1 -->
            <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jam</p>
                        <p class="text-lg font-bold text-neon-cyan">07:00 - 08:30</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kelas</p>
                        <p class="font-semibold text-white">XII IPA 1</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Mata Pelajaran</p>
                        <p class="font-semibold text-white">Matematika</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Guru</p>
                        <p class="font-semibold text-white flex items-center gap-2">
                            <span>Budi Santoso</span>
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Status</p>
                        <div class="flex items-center gap-2">
                            <span class="badge-success">Aktif</span>
                            <button class="btn-icon text-xs">📊</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule 2 -->
            <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jam</p>
                        <p class="text-lg font-bold text-neon-cyan">08:45 - 10:15</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kelas</p>
                        <p class="font-semibold text-white">XII IPA 2</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Mata Pelajaran</p>
                        <p class="font-semibold text-white">Fisika</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Guru</p>
                        <p class="font-semibold text-white flex items-center gap-2">
                            <span>Siti Nurhaliza</span>
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Status</p>
                        <div class="flex items-center gap-2">
                            <span class="badge-success">Aktif</span>
                            <button class="btn-icon text-xs">📊</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule 3 -->
            <div class="glass-card p-6 rounded-2xl border border-yellow-500/20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jam</p>
                        <p class="text-lg font-bold text-yellow-400">10:30 - 12:00</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kelas</p>
                        <p class="font-semibold text-white">XII IPS 1</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Mata Pelajaran</p>
                        <p class="font-semibold text-white">Sejarah</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Guru</p>
                        <p class="font-semibold text-white flex items-center gap-2">
                            <span>Hendra Gunawan</span>
                            <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Status</p>
                        <div class="flex items-center gap-2">
                            <span class="badge-warning">Idle</span>
                            <button class="btn-icon text-xs">📊</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tuesday -->
    <div>
        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <span class="w-3 h-3 bg-neon-cyan rounded-full"></span>
            Selasa, 09 Mei 2024 (Hari ini)
        </h3>
        <div class="space-y-3">
            <!-- Schedule 1 -->
            <div class="glass-card p-6 rounded-2xl border-2 border-neon-cyan/40 hover:border-neon-cyan/60 transition-all shadow-glow-cyan-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jam</p>
                        <p class="text-lg font-bold text-neon-cyan">07:00 - 08:30</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kelas</p>
                        <p class="font-semibold text-white">XI IPA 1</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Mata Pelajaran</p>
                        <p class="font-semibold text-white">Kimia</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Guru</p>
                        <p class="font-semibold text-white flex items-center gap-2">
                            <span>Ani Wijaya</span>
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Hadir / Jumlah Siswa</p>
                        <div class="flex items-center gap-2">
                            <span class="badge-success">42 / 42</span>
                            <button class="btn-icon text-xs">📊</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule 2 - Ongoing -->
            <div class="glass-card p-6 rounded-2xl border-2 border-emerald-500/40 animate-pulse-glow">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-emerald-400 text-xs font-bold uppercase">Sedang Berlangsung</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jam</p>
                        <p class="text-lg font-bold text-emerald-400">08:45 - 10:15</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kelas</p>
                        <p class="font-semibold text-white">XII IPA 1</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Mata Pelajaran</p>
                        <p class="font-semibold text-white">Biologi</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Guru</p>
                        <p class="font-semibold text-white flex items-center gap-2">
                            <span>Citra Kusuma</span>
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Hadir / Jumlah Siswa</p>
                        <div class="flex items-center gap-2">
                            <span class="badge-success">40 / 41</span>
                            <button class="btn-icon text-xs">📊</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Summary -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="glass-card p-6 rounded-2xl">
        <h4 class="text-lg font-bold text-white mb-4">Guru Hadir Hari Ini</h4>
        <div class="space-y-2">
            <p class="text-3xl font-bold text-neon-cyan">8 / 10</p>
            <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-neon-cyan to-neon-blue" style="width: 80%;"></div>
            </div>
            <p class="text-xs text-gray-400">2 guru tidak hadir</p>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h4 class="text-lg font-bold text-white mb-4">Rata-rata Kehadiran Siswa</h4>
        <div class="space-y-2">
            <p class="text-3xl font-bold text-emerald-400">91.2%</p>
            <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-400 to-green-500" style="width: 91.2%;"></div>
            </div>
            <p class="text-xs text-gray-400">dari total 340 siswa</p>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h4 class="text-lg font-bold text-white mb-4">Kelas Berlangsung</h4>
        <div class="space-y-2">
            <p class="text-3xl font-bold text-neon-cyan">3 / 12</p>
            <p class="text-xs text-gray-400">kelas sedang aktif</p>
            <button class="btn-secondary text-xs w-full mt-3">
                👁️ Lihat Live
            </button>
        </div>
    </div>
</div>
@endsection
