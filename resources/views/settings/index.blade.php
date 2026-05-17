@extends('layouts.app')

@section('title', 'Pengaturan — SITEXA Absensi')
@section('page_title', 'Pengaturan')

@section('content')
@php
    $settings = $settings ?? [];
@endphp
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gradient mb-2">⚙️ Pengaturan Sistem</h1>
    <p class="text-gray-400">Kelola konfigurasi dan preferensi admin dashboard</p>
</div>

@if(session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <!-- Settings Tabs -->
    <div class="glass-card p-4 rounded-2xl mb-6 flex items-center gap-2 overflow-x-auto">
        <button type="button" class="px-4 py-2 rounded-lg bg-neon-cyan text-dark-bg font-semibold text-sm whitespace-nowrap">
            Umum
        </button>
        <button type="button" class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
            Notifikasi
        </button>
        <button type="button" class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
            Keamanan
        </button>
        <button type="button" class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
            API
        </button>
        <button type="button" class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
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
                    <input type="text" name="school_name" class="input-field" value="{{ old('school_name', $settings['school_name'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">NPSN</label>
                    <input type="text" name="npsn" class="input-field" value="{{ old('npsn', $settings['npsn'] ?? '') }}" />
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Alamat</label>
                    <input type="text" name="school_address" class="input-field" value="{{ old('school_address', $settings['school_address'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Email Sekolah</label>
                    <input type="email" name="school_email" class="input-field" value="{{ old('school_email', $settings['school_email'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Telepon</label>
                    <input type="text" name="school_phone" class="input-field" value="{{ old('school_phone', $settings['school_phone'] ?? '') }}" />
                </div>
            </div>
        </div>

        <!-- System Configuration -->
        <div class="glass-card p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-white mb-6">Konfigurasi Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Jam Masuk</label>
                    <input type="time" name="entry_time" class="input-field" value="{{ old('entry_time', $settings['entry_time'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Jam Pulang</label>
                    <input type="time" name="exit_time" class="input-field" value="{{ old('exit_time', $settings['exit_time'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Toleransi Terlambat (menit)</label>
                    <input type="number" name="late_tolerance" class="input-field" value="{{ old('late_tolerance', $settings['late_tolerance'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Threshold Alpha (hari)</label>
                    <input type="number" name="alpha_threshold" class="input-field" value="{{ old('alpha_threshold', $settings['alpha_threshold'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Zona Waktu</label>
                    @php
                        $timezoneValue = old('timezone', $settings['timezone'] ?? 'Asia/Jakarta');
                    @endphp
                    <select name="timezone" class="input-field">
                        <option value="Asia/Jakarta" @selected($timezoneValue === 'Asia/Jakarta')>Asia/Jakarta (UTC+7)</option>
                        <option value="Asia/Surabaya" @selected($timezoneValue === 'Asia/Surabaya')>Asia/Surabaya (UTC+7)</option>
                        <option value="Asia/Makassar" @selected($timezoneValue === 'Asia/Makassar')>Asia/Makassar (UTC+8)</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Bahasa</label>
                    @php
                        $languageValue = old('language', $settings['language'] ?? 'id');
                    @endphp
                    <select name="language" class="input-field">
                        <option value="id" @selected($languageValue === 'id')>Indonesian (Bahasa Indonesia)</option>
                        <option value="en" @selected($languageValue === 'en')>English</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="glass-card p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-white mb-6">Notifikasi & Alert</h3>
            <div class="space-y-4">
                <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                    <input type="checkbox" name="notify_realtime" value="1" @checked(old('notify_realtime', $settings['notify_realtime'] ?? false)) class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                    <div class="flex-1">
                        <p class="font-semibold text-white">Notifikasi Absensi Real-Time</p>
                        <p class="text-xs text-gray-400">Tampilkan alert saat ada scan NFC baru</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                    <input type="checkbox" name="notify_device_offline" value="1" @checked(old('notify_device_offline', $settings['notify_device_offline'] ?? false)) class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                    <div class="flex-1">
                        <p class="font-semibold text-white">Alert Alat Offline</p>
                        <p class="text-xs text-gray-400">Notifikasi jika ada alat NFC yang offline</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                    <input type="checkbox" name="notify_email" value="1" @checked(old('notify_email', $settings['notify_email'] ?? false)) class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                    <div class="flex-1">
                        <p class="font-semibold text-white">Notifikasi Email</p>
                        <p class="text-xs text-gray-400">Kirim email untuk alert penting</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                    <input type="checkbox" name="notify_leave_pending" value="1" @checked(old('notify_leave_pending', $settings['notify_leave_pending'] ?? false)) class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
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
                    @php
                        $themeValue = old('theme', $settings['theme'] ?? 'dark');
                    @endphp
                    <select name="theme" class="input-field w-full md:w-1/3">
                        <option value="dark" @selected($themeValue === 'dark')>Dark</option>
                        <option value="light" @selected($themeValue === 'light')>Light</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Ukuran Font</label>
                    @php
                        $fontValue = old('font_size', $settings['font_size'] ?? 'normal');
                    @endphp
                    <select name="font_size" class="input-field w-full md:w-1/3">
                        <option value="kecil" @selected($fontValue === 'kecil')>Kecil</option>
                        <option value="normal" @selected($fontValue === 'normal')>Normal</option>
                        <option value="besar" @selected($fontValue === 'besar')>Besar</option>
                    </select>
                </div>

                <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                    <input type="checkbox" name="ui_animations" value="1" @checked(old('ui_animations', $settings['ui_animations'] ?? false)) class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                    <div class="flex-1">
                        <p class="font-semibold text-white">Animasi UI</p>
                        <p class="text-xs text-gray-400">Aktifkan efek animasi di dashboard</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-lg hover:bg-glass-light/10 cursor-pointer">
                    <input type="checkbox" name="auto_refresh" value="1" @checked(old('auto_refresh', $settings['auto_refresh'] ?? false)) class="w-5 h-5 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                    <div class="flex-1">
                        <p class="font-semibold text-white">Auto-Refresh Dashboard</p>
                        <p class="text-xs text-gray-400">Refresh data otomatis setiap 30 detik</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Admin Information -->
        <div class="glass-card p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-white mb-6">Informasi Admin</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Nama Lengkap</label>
                    <input type="text" name="admin_name" class="input-field" value="{{ old('admin_name', $settings['admin_name'] ?? '') }}" />
                </div>
                <div class="col-span-full">
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Email Admin (maks 5)</label>
                    @php
                        $adminEmails = old('admin_emails', $settings['admin_emails'] ?? []);
                        $adminEmails = is_array($adminEmails) ? $adminEmails : [];
                    @endphp
                    <div class="grid grid-cols-1 gap-3">
                        @for ($i = 0; $i < 5; $i++)
                            <input type="email" name="admin_emails[]" class="input-field" value="{{ $adminEmails[$i] ?? '' }}" placeholder="Email Admin {{ $i + 1 }}" />
                        @endfor
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Gunakan hingga 5 alamat email admin untuk anggota kelompok atau departemen lain.</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Telepon</label>
                    <input type="text" name="admin_phone" class="input-field" value="{{ old('admin_phone', $settings['admin_phone'] ?? '') }}" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Ubah Password</label>
                    <button type="button" class="btn-secondary w-full">
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
                    <span class="text-neon-cyan font-mono font-bold">MySQL</span>
                </div>
                <div class="flex justify-between p-3 rounded-lg bg-glass-light/10">
                    <span class="text-gray-400">Last Update</span>
                    <span class="text-neon-cyan font-mono font-bold">{{ now()->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between p-3 rounded-lg bg-glass-light/10">
                    <span class="text-gray-400">Total Users</span>
                    <span class="text-neon-cyan font-mono font-bold">{{ \App\Models\User::count() }} (Admin, Guru, Siswa)</span>
                </div>
            </div>
        </div>

        <!-- Data Management -->
        <div class="glass-card p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-white mb-6">Manajemen Data</h3>
            <div class="space-y-3">
                <div class="rounded-xl border border-slate-200 p-4">
                    <label class="text-sm font-semibold text-neon-cyan mb-2 block">Import Data Siswa (CSV)</label>
                    <input type="file" name="student_file" accept=".csv,.txt" class="input-field" />
                    <button type="submit" formaction="{{ route('settings.import-students') }}" formmethod="POST" formenctype="multipart/form-data" class="btn-secondary w-full text-left mt-3">
                        📥 Import Data Siswa
                    </button>
                    <p class="text-xs text-gray-400 mt-2">Kolom: nis, name, class_name, major, date_of_birth, email, phone, username, status, nfc_type, uid_kartu, password</p>
                </div>
                <button type="submit" formaction="{{ route('settings.export') }}" formmethod="POST" class="btn-secondary w-full text-left">
                    📤 Export Database
                </button>
                <button type="submit" formaction="{{ route('settings.cleanup') }}" formmethod="POST" class="btn-secondary w-full text-left">
                    🗑️ Hapus Data Lama (> 1 tahun)
                </button>
                <div class="rounded-xl border border-red-200 p-4">
                    <label class="text-sm font-semibold text-red-600 mb-2 block">Konfirmasi Reset Data</label>
                    <input type="text" name="confirm_reset" class="input-field" placeholder="Ketik RESET" />
                    <button type="submit" formaction="{{ route('settings.reset-data') }}" formmethod="POST" class="btn-danger w-full text-left mt-3" onclick="return confirm('Reset data utama? Tindakan ini tidak bisa dibatalkan.');">
                        ⚠️ Reset Semua Data (Irreversible)
                    </button>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">
                💾 Simpan Perubahan
            </button>
            <button type="submit" formaction="{{ route('settings.reset-defaults') }}" formmethod="POST" class="btn-secondary">
                🔄 Reset ke Default
            </button>
        </div>
    </div>
</form>
@endsection
