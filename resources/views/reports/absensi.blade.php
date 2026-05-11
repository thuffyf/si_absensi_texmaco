@extends('layouts.app')

@section('title', 'Laporan — SITEXA Absensi')
@section('page_title', 'Laporan')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gradient mb-2">📊 Laporan Absensi</h1>
    <p class="text-gray-400">Analisis data absensi siswa dan statistik kehadiran</p>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8 animate-fade-in">
    <div class="stat-card">
        <p class="stat-label">Total Siswa</p>
        <div class="stat-number">340</div>
    </div>
    <div class="stat-card">
        <p class="stat-label">Kehadiran Bulan Ini</p>
        <div class="stat-number">85.2%</div>
        <p class="text-xs text-emerald-400 mt-2">Rata-rata</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Izin</p>
        <div class="stat-number">12.5%</div>
    </div>
    <div class="stat-card">
        <p class="stat-label">Sakit</p>
        <div class="stat-number">2.1%</div>
    </div>
    <div class="stat-card">
        <p class="stat-label">Alpha</p>
        <div class="stat-number">0.2%</div>
    </div>
</div>

<!-- Filter & Report Options -->
<div class="glass-card p-6 rounded-2xl mb-6">
    <h3 class="text-lg font-bold text-white mb-4">Filter Laporan</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Date Range -->
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Rentang Tanggal</label>
            <input type="date" class="input-field text-sm" />
        </div>
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Hingga</label>
            <input type="date" class="input-field text-sm" />
        </div>

        <!-- Kelas -->
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Kelas</label>
            <select class="input-field text-sm">
                <option>Semua Kelas</option>
                <option>XII IPA 1</option>
                <option>XII IPA 2</option>
                <option>XII IPS 1</option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Status</label>
            <select class="input-field text-sm">
                <option>Semua Status</option>
                <option>Hadir</option>
                <option>Izin</option>
                <option>Sakit</option>
                <option>Alpha</option>
            </select>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mt-4">
        <button class="btn-primary text-sm">
            🔍 Tampilkan Laporan
        </button>
        <button class="btn-secondary text-sm">
            📥 Import Data
        </button>
        <button class="btn-secondary text-sm">
            📤 Export Excel
        </button>
        <button class="btn-secondary text-sm">
            🖨️ Cetak
        </button>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-in">
    <!-- Daily Attendance Chart -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Grafik Absensi Harian</h3>
        <div class="h-64 flex items-end gap-2 justify-between">
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 70%;"></div>
                <p class="text-xs text-gray-400 mt-2">Sen</p>
                <p class="text-xs font-bold text-neon-cyan">89%</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 80%;"></div>
                <p class="text-xs text-gray-400 mt-2">Sel</p>
                <p class="text-xs font-bold text-neon-cyan">92%</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 68%;"></div>
                <p class="text-xs text-gray-400 mt-2">Rab</p>
                <p class="text-xs font-bold text-neon-cyan">84%</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 78%;"></div>
                <p class="text-xs text-gray-400 mt-2">Kam</p>
                <p class="text-xs font-bold text-neon-cyan">88%</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-neon-cyan/30 to-neon-cyan rounded-t-lg" style="height: 85%;"></div>
                <p class="text-xs text-gray-400 mt-2">Jum</p>
                <p class="text-xs font-bold text-neon-cyan">91%</p>
            </div>
        </div>
    </div>

    <!-- Class Comparison -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Perbandingan Per Kelas</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">XII IPA 1</span>
                    <span class="text-sm font-bold text-neon-cyan">92%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-neon-cyan to-neon-blue" style="width: 92%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">XII IPA 2</span>
                    <span class="text-sm font-bold text-neon-cyan">88%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-neon-cyan to-neon-blue" style="width: 88%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">XII IPS 1</span>
                    <span class="text-sm font-bold text-neon-cyan">85%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-neon-cyan to-neon-blue" style="width: 85%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">XII IPS 2</span>
                    <span class="text-sm font-bold text-neon-cyan">81%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-neon-cyan to-neon-blue" style="width: 81%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-300">XI IPA 1</span>
                    <span class="text-sm font-bold text-neon-cyan">90%</span>
                </div>
                <div class="w-full h-3 bg-glass-light/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-neon-cyan to-neon-blue" style="width: 90%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Report Table -->
<div class="glass-card p-6 rounded-2xl mb-8">
    <h3 class="text-lg font-bold text-white mb-4">Detail Absensi Per Siswa</h3>
    
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alpha</th>
                    <th>Total Hari</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-neon-cyan to-neon-blue flex items-center justify-center text-sm font-bold">👨</div>
                            <span>Rafa Prakasa</span>
                        </div>
                    </td>
                    <td>XII IPA 1</td>
                    <td><span class="badge-success">45</span></td>
                    <td><span class="badge-warning">2</span></td>
                    <td><span class="badge-info">1</span></td>
                    <td><span class="badge-danger">0</span></td>
                    <td>48</td>
                    <td><span class="text-neon-cyan font-bold">93.75%</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-neon-purple flex items-center justify-center text-sm font-bold">👩</div>
                            <span>Silvi Lestari</span>
                        </div>
                    </td>
                    <td>XII IPA 1</td>
                    <td><span class="badge-success">44</span></td>
                    <td><span class="badge-warning">3</span></td>
                    <td><span class="badge-info">1</span></td>
                    <td><span class="badge-danger">0</span></td>
                    <td>48</td>
                    <td><span class="text-neon-cyan font-bold">91.67%</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-sm font-bold">👨</div>
                            <span>Adi Pratama</span>
                        </div>
                    </td>
                    <td>XII IPA 1</td>
                    <td><span class="badge-success">42</span></td>
                    <td><span class="badge-warning">4</span></td>
                    <td><span class="badge-info">2</span></td>
                    <td><span class="badge-danger">0</span></td>
                    <td>48</td>
                    <td><span class="text-neon-cyan font-bold">87.50%</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-sm font-bold">👩</div>
                            <span>Mira Putri</span>
                        </div>
                    </td>
                    <td>XII IPA 2</td>
                    <td><span class="badge-success">46</span></td>
                    <td><span class="badge-warning">1</span></td>
                    <td><span class="badge-info">1</span></td>
                    <td><span class="badge-danger">0</span></td>
                    <td>48</td>
                    <td><span class="text-neon-cyan font-bold">95.83%</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-sm font-bold">👨</div>
                            <span>Danu Wijaya</span>
                        </div>
                    </td>
                    <td>XII IPA 2</td>
                    <td><span class="badge-success">40</span></td>
                    <td><span class="badge-warning">2</span></td>
                    <td><span class="badge-info">5</span></td>
                    <td><span class="badge-danger">1</span></td>
                    <td>48</td>
                    <td><span class="text-yellow-400 font-bold">83.33%</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Bottom Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Best Attendance -->
    <div class="glass-card p-6 rounded-2xl">
        <h4 class="text-lg font-bold text-white mb-4">🏆 Kehadiran Terbaik</h4>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-white">Mira Putri</p>
                    <p class="text-xs text-gray-400">XII IPA 2</p>
                </div>
                <p class="text-2xl font-bold text-emerald-400">95.83%</p>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-white">Rafa Prakasa</p>
                    <p class="text-xs text-gray-400">XII IPA 1</p>
                </div>
                <p class="text-2xl font-bold text-emerald-400">93.75%</p>
            </div>
        </div>
    </div>

    <!-- Needs Attention -->
    <div class="glass-card p-6 rounded-2xl">
        <h4 class="text-lg font-bold text-white mb-4">⚠️ Perlu Perhatian</h4>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-white">Danu Wijaya</p>
                    <p class="text-xs text-gray-400">XII IPA 2</p>
                </div>
                <p class="text-2xl font-bold text-yellow-400">83.33%</p>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-white">Adi Pratama</p>
                    <p class="text-xs text-gray-400">XII IPA 1</p>
                </div>
                <p class="text-2xl font-bold text-yellow-400">87.50%</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="glass-card p-6 rounded-2xl">
        <h4 class="text-lg font-bold text-white mb-4">📈 Statistik</h4>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Rata-rata Kehadiran:</span>
                <span class="font-bold text-neon-cyan">89.8%</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Total Jam Belajar:</span>
                <span class="font-bold text-neon-cyan">8640 jam</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Total Izin:</span>
                <span class="font-bold text-yellow-400">12 hari</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Total Sakit:</span>
                <span class="font-bold text-red-400">10 hari</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Total Alpha:</span>
                <span class="font-bold text-red-400">1 hari</span>
            </div>
        </div>
    </div>
</div>
@endsection
