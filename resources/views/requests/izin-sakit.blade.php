@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">📋 Request Izin & Sakit</h1>
            <p class="text-gray-400">Kelola pengajuan izin dan sakit siswa</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="badge-warning">
                <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                3 Tertunda
            </span>
        </div>
    </div>
</div>

<!-- Tab Filter -->
<div class="glass-card p-4 rounded-2xl mb-6 flex items-center gap-2 overflow-x-auto">
    <button class="px-4 py-2 rounded-lg bg-neon-cyan text-dark-bg font-semibold text-sm whitespace-nowrap">
        Semua (15)
    </button>
    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
        Tertunda (3)
    </button>
    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
        Disetujui (10)
    </button>
    <button class="px-4 py-2 rounded-lg glass-effect hover:bg-glass-light/20 text-sm whitespace-nowrap">
        Ditolak (2)
    </button>
</div>

<!-- Pending Requests -->
<div class="space-y-6 mb-8">
    <!-- Request 1 - Izin (Pending) -->
    <div class="glass-card p-6 rounded-2xl border border-yellow-500/20 hover:border-yellow-500/50 transition-all animate-slide-in">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left - Student Info -->
            <div>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-neon-cyan to-neon-blue flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm flex-shrink-0">👨</div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Rafa Prakasa</h3>
                        <p class="text-sm text-gray-400">NIM: 12001</p>
                        <p class="text-sm text-gray-400">Kelas: XII IPA 1</p>
                        <p class="text-xs text-neon-cyan mt-2 font-mono">request_id: REQ-2024-001</p>
                    </div>
                </div>
            </div>

            <!-- Center - Request Details -->
            <div>
                <p class="text-xs text-gray-400 mb-2 uppercase tracking-wide">Detail Pengajuan</p>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Jenis Pengajuan</p>
                        <p class="text-sm font-semibold text-yellow-300">📋 Izin</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-sm font-semibold text-white">08 Mei 2024, 06:30</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Izin</p>
                        <p class="text-sm font-semibold text-white">08 - 09 Mei 2024</p>
                    </div>
                </div>
            </div>

            <!-- Right - Reason & Action -->
            <div>
                <p class="text-xs text-gray-400 mb-3 uppercase tracking-wide">Alasan</p>
                <p class="text-sm text-gray-300 mb-4 line-clamp-3">Mengikuti acara lomba di tingkat kabupaten yang akan diselenggarakan di SMA Negeri 2 Kota.</p>
                
                <div class="flex flex-col gap-2 mt-auto">
                    <button class="btn-success text-sm">
                        ✓ Terima
                    </button>
                    <button class="btn-danger text-sm">
                        ✕ Tolak
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-yellow-500/10">
            <span class="badge-warning">
                <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                Menunggu Verifikasi
            </span>
        </div>
    </div>

    <!-- Request 2 - Sakit (Pending) -->
    <div class="glass-card p-6 rounded-2xl border border-red-500/20 hover:border-red-500/50 transition-all animate-slide-in" style="animation-delay: 0.1s;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left - Student Info -->
            <div>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-400 to-neon-purple flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm flex-shrink-0">👩</div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Silvi Lestari</h3>
                        <p class="text-sm text-gray-400">NIM: 12002</p>
                        <p class="text-sm text-gray-400">Kelas: XII IPA 1</p>
                        <p class="text-xs text-neon-cyan mt-2 font-mono">request_id: REQ-2024-002</p>
                    </div>
                </div>
            </div>

            <!-- Center - Request Details -->
            <div>
                <p class="text-xs text-gray-400 mb-2 uppercase tracking-wide">Detail Pengajuan</p>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Jenis Pengajuan</p>
                        <p class="text-sm font-semibold text-red-300">🏥 Sakit</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-sm font-semibold text-white">08 Mei 2024, 05:45</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Sakit</p>
                        <p class="text-sm font-semibold text-white">08 Mei 2024 (1 hari)</p>
                    </div>
                </div>
            </div>

            <!-- Right - Reason & Action -->
            <div>
                <p class="text-xs text-gray-400 mb-3 uppercase tracking-wide">Alasan</p>
                <p class="text-sm text-gray-300 mb-4 line-clamp-3">Sakit demam tinggi dan batuk, akan periksa ke dokter hari ini. Estimasi pulih 1 hari.</p>
                
                <div class="flex flex-col gap-2 mt-auto">
                    <button class="btn-success text-sm">
                        ✓ Terima
                    </button>
                    <button class="btn-danger text-sm">
                        ✕ Tolak
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-red-500/10">
            <span class="badge-danger">
                <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                Menunggu Verifikasi
            </span>
        </div>
    </div>

    <!-- Request 3 - Izin (Pending) -->
    <div class="glass-card p-6 rounded-2xl border border-yellow-500/20 hover:border-yellow-500/50 transition-all animate-slide-in" style="animation-delay: 0.2s;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left - Student Info -->
            <div>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gray-600 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm flex-shrink-0">👨</div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Adi Pratama</h3>
                        <p class="text-sm text-gray-400">NIM: 12003</p>
                        <p class="text-sm text-gray-400">Kelas: XII IPA 1</p>
                        <p class="text-xs text-neon-cyan mt-2 font-mono">request_id: REQ-2024-003</p>
                    </div>
                </div>
            </div>

            <!-- Center - Request Details -->
            <div>
                <p class="text-xs text-gray-400 mb-2 uppercase tracking-wide">Detail Pengajuan</p>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Jenis Pengajuan</p>
                        <p class="text-sm font-semibold text-yellow-300">📋 Izin</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-sm font-semibold text-white">08 Mei 2024, 07:00</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Izin</p>
                        <p class="text-sm font-semibold text-white">08 Mei 2024 (Pulang Awal)</p>
                    </div>
                </div>
            </div>

            <!-- Right - Reason & Action -->
            <div>
                <p class="text-xs text-gray-400 mb-3 uppercase tracking-wide">Alasan</p>
                <p class="text-sm text-gray-300 mb-4 line-clamp-3">Pulang awal karena ada keperluan keluarga yang mendesak. Akan pulang saat jam istirahat kedua.</p>
                
                <div class="flex flex-col gap-2 mt-auto">
                    <button class="btn-success text-sm">
                        ✓ Terima
                    </button>
                    <button class="btn-danger text-sm">
                        ✕ Tolak
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-yellow-500/10">
            <span class="badge-warning">
                <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                Menunggu Verifikasi
            </span>
        </div>
    </div>
</div>

<!-- Approved Requests -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
        <span class="w-3 h-3 bg-emerald-400 rounded-full"></span>
        Pengajuan Disetujui (10)
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Approved 1 -->
        <div class="glass-card p-4 rounded-xl border border-emerald-500/20">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-semibold text-white">Mira Putri</h4>
                    <p class="text-xs text-gray-400">NIM: 12004 | Izin</p>
                </div>
                <span class="badge-success">Disetujui</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">Tanggal: 07 - 08 Mei 2024</p>
            <p class="text-xs text-gray-300">Mengikuti acara keagamaan di mushola.</p>
            <p class="text-xs text-gray-500 mt-2">Disetujui: 07 Mei 2024 - Admin TU</p>
        </div>

        <!-- Approved 2 -->
        <div class="glass-card p-4 rounded-xl border border-emerald-500/20">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-semibold text-white">Danu Wijaya</h4>
                    <p class="text-xs text-gray-400">NIM: 12005 | Sakit</p>
                </div>
                <span class="badge-success">Disetujui</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">Tanggal: 06 Mei 2024</p>
            <p class="text-xs text-gray-300">Sakit dan perlu istirahat di rumah.</p>
            <p class="text-xs text-gray-500 mt-2">Disetujui: 06 Mei 2024 - Admin TU</p>
        </div>

        <!-- Approved 3 -->
        <div class="glass-card p-4 rounded-xl border border-emerald-500/20">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-semibold text-white">Budi Santoso</h4>
                    <p class="text-xs text-gray-400">NIM: 12006 | Izin</p>
                </div>
                <span class="badge-success">Disetujui</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">Tanggal: 05 Mei 2024</p>
            <p class="text-xs text-gray-300">Menghadiri seminar pendidikan nasional.</p>
            <p class="text-xs text-gray-500 mt-2">Disetujui: 05 Mei 2024 - Admin TU</p>
        </div>

        <!-- Approved 4 -->
        <div class="glass-card p-4 rounded-xl border border-emerald-500/20">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-semibold text-white">Ani Wijaya</h4>
                    <p class="text-xs text-gray-400">NIM: 12007 | Sakit</p>
                </div>
                <span class="badge-success">Disetujui</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">Tanggal: 04 Mei 2024</p>
            <p class="text-xs text-gray-300">Migrain dan perlu istirahat total.</p>
            <p class="text-xs text-gray-500 mt-2">Disetujui: 04 Mei 2024 - Admin TU</p>
        </div>
    </div>
</div>

<!-- Rejected Requests -->
<div>
    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
        <span class="w-3 h-3 bg-red-400 rounded-full"></span>
        Pengajuan Ditolak (2)
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Rejected 1 -->
        <div class="glass-card p-4 rounded-xl border border-red-500/20">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-semibold text-white">Citra Kusuma</h4>
                    <p class="text-xs text-gray-400">NIM: 12008 | Izin</p>
                </div>
                <span class="badge-danger">Ditolak</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">Tanggal Request: 03 Mei 2024</p>
            <p class="text-xs text-gray-300">Alasan: Tidak ada bukti yang jelas. Hubungi admin untuk verifikasi lebih lanjut.</p>
            <p class="text-xs text-red-400 mt-2">Ditolak: 03 Mei 2024 - Admin TU</p>
        </div>

        <!-- Rejected 2 -->
        <div class="glass-card p-4 rounded-xl border border-red-500/20">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-semibold text-white">Hendra Gunawan</h4>
                    <p class="text-xs text-gray-400">NIM: 12009 | Sakit</p>
                </div>
                <span class="badge-danger">Ditolak</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">Tanggal Request: 02 Mei 2024</p>
            <p class="text-xs text-gray-300">Alasan: Izin sakit perlu surat dari orang tua atau dokter.</p>
            <p class="text-xs text-red-400 mt-2">Ditolak: 02 Mei 2024 - Admin TU</p>
        </div>
    </div>
</div>
@endsection
