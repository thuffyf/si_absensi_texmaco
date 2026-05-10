@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">👨‍🏫 Data Guru</h1>
            <p class="text-gray-400">Kelola data guru dan monitoring kehadiran</p>
        </div>
        <button class="btn-primary">
            + Tambah Guru
        </button>
    </div>
</div>

<!-- Filter -->
<div class="glass-card p-6 rounded-2xl mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" class="input-field" placeholder="🔍 Cari nama atau NIP..." />
        <select class="input-field text-sm">
            <option>Semua Mata Pelajaran</option>
            <option>Matematika</option>
            <option>Fisika</option>
            <option>Kimia</option>
            <option>Biologi</option>
        </select>
        <select class="input-field text-sm">
            <option>Semua Status</option>
            <option>Aktif</option>
            <option>Cuti</option>
            <option>Non-Aktif</option>
        </select>
        <button class="btn-secondary text-sm">
            📥 Import | 📤 Export
        </button>
    </div>
</div>

<!-- Teachers Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Teacher 1 -->
    <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all animate-fade-in">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm">👨</div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-white">Budi Santoso</h3>
                <p class="text-sm text-gray-400">NIP: 196812251992031003</p>
            </div>
            <span class="badge-success">Aktif</span>
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Mata Pelajaran</span>
                <span class="text-white font-semibold">Matematika</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Jam Mengajar</span>
                <span class="text-neon-cyan font-semibold">12 jam/minggu</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Email</span>
                <span class="text-neon-cyan font-mono text-xs">budi@texmaco.id</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Telepon</span>
                <span class="text-white font-semibold">+62-812-1234567</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kehadiran</p>
                <p class="text-lg font-bold text-emerald-400">98%</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kelas</p>
                <p class="text-lg font-bold text-neon-cyan">3</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">✏️ Edit</button>
            <button class="btn-secondary text-xs flex-1">👁️ Detail</button>
            <button class="btn-icon text-xs">🗑️</button>
        </div>
    </div>

    <!-- Teacher 2 -->
    <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all animate-fade-in" style="animation-delay: 0.1s;">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm">👩</div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-white">Siti Nurhaliza</h3>
                <p class="text-sm text-gray-400">NIP: 197503141998032001</p>
            </div>
            <span class="badge-success">Aktif</span>
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Mata Pelajaran</span>
                <span class="text-white font-semibold">Fisika</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Jam Mengajar</span>
                <span class="text-neon-cyan font-semibold">14 jam/minggu</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Email</span>
                <span class="text-neon-cyan font-mono text-xs">siti@texmaco.id</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Telepon</span>
                <span class="text-white font-semibold">+62-812-2345678</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kehadiran</p>
                <p class="text-lg font-bold text-emerald-400">96%</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kelas</p>
                <p class="text-lg font-bold text-neon-cyan">4</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">✏️ Edit</button>
            <button class="btn-secondary text-xs flex-1">👁️ Detail</button>
            <button class="btn-icon text-xs">🗑️</button>
        </div>
    </div>

    <!-- Teacher 3 -->
    <div class="glass-card p-6 rounded-2xl border border-yellow-500/20">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gray-600 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm">👨</div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-white">Hendra Gunawan</h3>
                <p class="text-sm text-gray-400">NIP: 197001151995031001</p>
            </div>
            <span class="badge-warning">Cuti</span>
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Mata Pelajaran</span>
                <span class="text-white font-semibold">Sejarah</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Jam Mengajar</span>
                <span class="text-neon-cyan font-semibold">10 jam/minggu</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Email</span>
                <span class="text-neon-cyan font-mono text-xs">hendra@texmaco.id</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Cuti Hingga</span>
                <span class="text-yellow-400 font-semibold">15 Mei 2024</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kehadiran</p>
                <p class="text-lg font-bold text-yellow-400">80%</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kelas</p>
                <p class="text-lg font-bold text-neon-cyan">2</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">✏️ Edit</button>
            <button class="btn-secondary text-xs flex-1">👁️ Detail</button>
            <button class="btn-icon text-xs">🗑️</button>
        </div>
    </div>

    <!-- Teacher 4 -->
    <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all animate-fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm">👩</div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-white">Ani Wijaya</h3>
                <p class="text-sm text-gray-400">NIP: 197806231999031001</p>
            </div>
            <span class="badge-success">Aktif</span>
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Mata Pelajaran</span>
                <span class="text-white font-semibold">Kimia</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Jam Mengajar</span>
                <span class="text-neon-cyan font-semibold">12 jam/minggu</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Email</span>
                <span class="text-neon-cyan font-mono text-xs">ani@texmaco.id</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Telepon</span>
                <span class="text-white font-semibold">+62-812-3456789</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kehadiran</p>
                <p class="text-lg font-bold text-emerald-400">100%</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kelas</p>
                <p class="text-lg font-bold text-neon-cyan">3</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">✏️ Edit</button>
            <button class="btn-secondary text-xs flex-1">👁️ Detail</button>
            <button class="btn-icon text-xs">🗑️</button>
        </div>
    </div>

    <!-- Teacher 5 -->
    <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all animate-fade-in" style="animation-delay: 0.3s;">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm">👩</div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-white">Citra Kusuma</h3>
                <p class="text-sm text-gray-400">NIP: 198203241998032001</p>
            </div>
            <span class="badge-success">Aktif</span>
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Mata Pelajaran</span>
                <span class="text-white font-semibold">Biologi</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Jam Mengajar</span>
                <span class="text-neon-cyan font-semibold">11 jam/minggu</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Email</span>
                <span class="text-neon-cyan font-mono text-xs">citra@texmaco.id</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Telepon</span>
                <span class="text-white font-semibold">+62-812-4567890</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kehadiran</p>
                <p class="text-lg font-bold text-emerald-400">94%</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kelas</p>
                <p class="text-lg font-bold text-neon-cyan">3</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">✏️ Edit</button>
            <button class="btn-secondary text-xs flex-1">👁️ Detail</button>
            <button class="btn-icon text-xs">🗑️</button>
        </div>
    </div>

    <!-- Teacher 6 -->
    <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all animate-fade-in" style="animation-delay: 0.4s;">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl bg-gray-600 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm">👨</div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-white">Ricky Pratama</h3>
                <p class="text-sm text-gray-400">NIP: 199001151999031003</p>
            </div>
            <span class="badge-success">Aktif</span>
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-neon-cyan/10">
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Mata Pelajaran</span>
                <span class="text-white font-semibold">Olahraga</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Jam Mengajar</span>
                <span class="text-neon-cyan font-semibold">8 jam/minggu</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Email</span>
                <span class="text-neon-cyan font-mono text-xs">ricky@texmaco.id</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Telepon</span>
                <span class="text-white font-semibold">+62-812-5678901</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kehadiran</p>
                <p class="text-lg font-bold text-emerald-400">99%</p>
            </div>
            <div class="bg-glass-light/10 rounded-lg p-2 text-center">
                <p class="text-xs text-gray-400">Kelas</p>
                <p class="text-lg font-bold text-neon-cyan">5</p>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-secondary text-xs flex-1">✏️ Edit</button>
            <button class="btn-secondary text-xs flex-1">👁️ Detail</button>
            <button class="btn-icon text-xs">🗑️</button>
        </div>
    </div>
</div>

<!-- Teacher Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="glass-card p-6 rounded-2xl">
        <p class="stat-label">Total Guru</p>
        <div class="stat-number">10</div>
    </div>
    <div class="glass-card p-6 rounded-2xl">
        <p class="stat-label">Guru Aktif</p>
        <div class="stat-number">9</div>
        <p class="text-xs text-emerald-400 mt-2">90% Aktif</p>
    </div>
    <div class="glass-card p-6 rounded-2xl">
        <p class="stat-label">Rata-rata Kehadiran</p>
        <div class="stat-number">95%</div>
    </div>
    <div class="glass-card p-6 rounded-2xl">
        <p class="stat-label">Total Jam Mengajar</p>
        <div class="stat-number">82</div>
        <p class="text-xs text-gray-400 mt-2">jam/minggu</p>
    </div>
</div>
@endsection
