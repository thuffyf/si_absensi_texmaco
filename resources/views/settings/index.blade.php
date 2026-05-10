@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gradient mb-2">⚙️ Pengaturan Sistem</h1>
    <p class="text-gray-400">Kelola konfigurasi dan preferensi admin dashboard</p>
</div>

<!-- Settings Tabs -->
<div class="glass-card p-4 rounded-2xl mb-6 flex items-center gap-2 overflow-x-auto">
    <button class="px-4 py-2 rounded-lg bg-neon-cyan text-dark-bg font-semibold text-sm whitespace-nowrap">
        Umum
    </button>
    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
        Notifikasi
    </button>
    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
        Keamanan
    </button>
    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
        API
    </button>
    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
        Backup
    </button>
</div>

<!-- General Settings -->
<div class="space-y-6">
    <!-- School Info -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Informasi Sekolah</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Nama Sekolah</label>
                <input type="text" class="input-field" value="Texmaco School" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">NPSN</label>
                <input type="text" class="input-field" value="20504001" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Alamat</label>
                <input type="text" class="input-field col-span-full" value="Jl. Pendidikan No. 123, Kota" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Email Sekolah</label>
                <input type="email" class="input-field" value="info@texmaco.sch.id" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Telepon</label>
                <input type="text" class="input-field" value="+62-274-123456" />
            </div>
        </div>
    </div>

    <!-- System Configuration -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Konfigurasi Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Jam Masuk</label>
                <input type="time" class="input-field" value="07:00" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Jam Pulang</label>
                <input type="time" class="input-field" value="14:30" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Toleransi Terlambat (menit)</label>
                <input type="number" class="input-field" value="15" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Threshold Alpha (hari)</label>
                <input type="number" class="input-field" value="3" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Zona Waktu</label>
                <select class="input-field">
                    <option selected>Asia/Jakarta (UTC+7)</option>
                    <option>Asia/Surabaya (UTC+7)</option>
                    <option>Asia/Makassar (UTC+8)</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Bahasa</label>
                <select class="input-field">
                    <option selected>Indonesian (Bahasa Indonesia)</option>
                    <option>English</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Notifikasi & Alert</h3>
        <div class="space-y-4">
            <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                <input type="checkbox" checked class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                <div class="flex-1">
                    <p class="font-semibold text-white">Notifikasi Absensi Real-Time</p>
                    <p class="text-xs text-gray-400">Tampilkan alert saat ada scan NFC baru</p>
                </div>
            </label>

            <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                <input type="checkbox" checked class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                <div class="flex-1">
                    <p class="font-semibold text-white">Alert Alat Offline</p>
                    <p class="text-xs text-gray-400">Notifikasi jika ada alat NFC yang offline</p>
                </div>
            </label>

            <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                <input type="checkbox" class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                <div class="flex-1">
                    <p class="font-semibold text-white">Notifikasi Email</p>
                    <p class="text-xs text-gray-400">Kirim email untuk alert penting</p>
                </div>
            </label>

            <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                <input type="checkbox" checked class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                <div class="flex-1">
                    <p class="font-semibold text-white">Notifikasi Izin/Sakit Pending</p>
                    <p class="text-xs text-gray-400">Alert untuk pengajuan izin/sakit yang menunggu persetujuan</p>
                </div>
            </label>
        </div>
    </div>

    <!-- Display Settings -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Pengaturan Tampilan</h3>
        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Tema</label>
                <div class="flex gap-3">
                    <button class="px-4 py-2 rounded-lg bg-neon-cyan text-dark-bg font-semibold text-sm">
                        🌙 Dark (Aktif)
                    </button>
                    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm">
                        ☀️ Light
                    </button>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Ukuran Font</label>
                <select class="input-field w-full md:w-1/3">
                    <option>Kecil</option>
                    <option selected>Normal</option>
                    <option>Besar</option>
                </select>
            </div>

            <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                <input type="checkbox" checked class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                <div class="flex-1">
                    <p class="font-semibold text-white">Animasi UI</p>
                    <p class="text-xs text-gray-400">Aktifkan efek animasi di dashboard</p>
                </div>
            </label>

            <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                <input type="checkbox" checked class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                <div class="flex-1">
                    <p class="font-semibold text-white">Auto-Refresh Dashboard</p>
                    <p class="text-xs text-gray-400">Refresh data otomatis setiap 30 detik</p>
                </div>
            </label>
        </div>
    </div>

    <!-- Data Management -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Manajemen Data</h3>
        <div class="space-y-3">
            <button class="btn-secondary w-full text-left">
                📥 Import Data Siswa
            </button>
            <button class="btn-secondary w-full text-left">
                📤 Export Database
            </button>
            <button class="btn-secondary w-full text-left">
                🗑️ Hapus Data Lama (> 1 tahun)
            </button>
            <button class="btn-danger w-full text-left">
                ⚠️ Reset Semua Data (Irreversible)
            </button>
        </div>
    </div>

    <!-- Admin Information -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Informasi Admin</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Nama Lengkap</label>
                <input type="text" class="input-field" value="Admin Tata Usaha" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Email</label>
                <input type="email" class="input-field" value="admin@texmaco.sch.id" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Telepon</label>
                <input type="text" class="input-field" value="+62-812-3456789" />
            </div>
            <div>
                <label class="text-sm font-semibold text-neon-cyan mb-2 block">Ubah Password</label>
                <button class="btn-secondary w-full">
                    🔐 Ubah Password
                </button>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-6">Informasi Sistem</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between p-3 rounded-lg bg-glass-light/10">
                <span class="text-gray-400">Versi Sistem</span>
                <span class="text-neon-cyan font-mono font-bold">v1.0.0</span>
            </div>
            <div class="flex justify-between p-3 rounded-lg bg-glass-light/10">
                <span class="text-gray-400">Database Version</span>
                <span class="text-neon-cyan font-mono font-bold">PostgreSQL 15.2</span>
            </div>
            <div class="flex justify-between p-3 rounded-lg bg-glass-light/10">
                <span class="text-gray-400">Last Update</span>
                <span class="text-neon-cyan font-mono font-bold">08 Mei 2024, 06:30</span>
            </div>
            <div class="flex justify-between p-3 rounded-lg bg-glass-light/10">
                <span class="text-gray-400">Total Users</span>
                <span class="text-neon-cyan font-mono font-bold">3 (Admin, Guru, Siswa)</span>
            </div>
            <div class="flex justify-between p-3 rounded-lg bg-glass-light/10">
                <span class="text-gray-400">Server Uptime</span>
                <span class="text-emerald-400 font-mono font-bold">28 hari 15 jam</span>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex gap-3">
        <button class="btn-primary">
            💾 Simpan Perubahan
        </button>
        <button class="btn-secondary">
            🔄 Reset ke Default
        </button>
    </div>
</div>
@endsection
