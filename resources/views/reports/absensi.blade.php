@extends('layouts.app')

@section('title', 'Laporan — SITEXA Absensi')
@section('page_title', 'Laporan')

@section('content')
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gradient mb-2">📊 Laporan Absensi</h1>
    <p class="text-gray-400">Analisis data absensi siswa dan statistik kehadiran</p>
</div>

@php
    $hadir = $statusCounts['hadir'] ?? 0;
    $izin = $statusCounts['izin'] ?? 0;
    $sakit = $statusCounts['sakit'] ?? 0;
    $alpha = $statusCounts['alpha'] ?? 0;
    $total = max($totalRecords, 1);
    $attendanceRate = round(($hadir / $total) * 100, 1);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8 animate-fade-in">
    <div class="stat-card">
        <p class="stat-label">Total Siswa</p>
        <div class="stat-number">{{ $totalStudents }}</div>
    </div>
    <div class="stat-card">
        <p class="stat-label">Kehadiran</p>
        <div class="stat-number">{{ $attendanceRate }}%</div>
        <p class="text-xs text-emerald-400 mt-2">Dari {{ $totalRecords }} data</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Izin</p>
        <div class="stat-number">{{ $izin }}</div>
    </div>
    <div class="stat-card">
        <p class="stat-label">Sakit</p>
        <div class="stat-number">{{ $sakit }}</div>
    </div>
    <div class="stat-card">
        <p class="stat-label">Alpha</p>
        <div class="stat-number">{{ $alpha }}</div>
    </div>
</div>

<div class="glass-card p-6 rounded-2xl mb-6">
    <h3 class="text-lg font-bold text-white mb-4">Filter Laporan</h3>
    <form method="GET" action="{{ route('reports.absensi') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Dari</label>
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="input-field text-sm" />
        </div>
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Hingga</label>
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="input-field text-sm" />
        </div>
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Kelas</label>
            <input type="text" name="class" value="{{ $filters['class'] ?? '' }}" class="input-field text-sm" placeholder="Kelas" />
        </div>
        <div>
            <label class="text-sm font-semibold text-neon-cyan mb-2 block">Status</label>
            <select name="status" class="input-field text-sm">
                <option value="">Semua Status</option>
                <option value="hadir" @selected(($filters['status'] ?? '') === 'hadir')>Hadir</option>
                <option value="izin" @selected(($filters['status'] ?? '') === 'izin')>Izin</option>
                <option value="sakit" @selected(($filters['status'] ?? '') === 'sakit')>Sakit</option>
                <option value="alpha" @selected(($filters['status'] ?? '') === 'alpha')>Alpha</option>
            </select>
        </div>
        <div class="flex flex-wrap gap-2 md:col-span-2 lg:col-span-4">
            <button class="btn-primary text-sm" type="submit">Tampilkan Laporan</button>
            <a href="{{ route('reports.absensi') }}" class="btn-secondary text-sm">Reset</a>
        </div>
    </form>
</div>

<div class="glass-card p-6 rounded-2xl mb-8">
    <h3 class="text-lg font-bold text-white mb-4">Detail Absensi Per Siswa</h3>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Hadir</th>
                    <th>Jam Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alpha</th>
                    <th>Total</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    @php
                        $student = $row['student'];
                        $totalRow = max($row['total'], 1);
                        $percent = round(($row['hadir'] / $totalRow) * 100, 1);
                    @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-neon-cyan to-neon-blue flex items-center justify-center text-sm font-bold">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                                <span>{{ $student->name }}</span>
                            </div>
                        </td>
                        <td>{{ $student->class_name }}</td>
                        <td><span class="badge-success">{{ $row['hadir'] }}</span></td>
                        <td>{{ $row['last_time'] }}</td>
                        <td><span class="badge-warning">{{ $row['izin'] }}</span></td>
                        <td><span class="badge-info">{{ $row['sakit'] }}</span></td>
                        <td><span class="badge-danger">{{ $row['alpha'] }}</span></td>
                        <td>{{ $row['total'] }}</td>
                        <td><span class="text-neon-cyan font-bold">{{ $percent }}%</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-sm text-gray-400">Belum ada data absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
