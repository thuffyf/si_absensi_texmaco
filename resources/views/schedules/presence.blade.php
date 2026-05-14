@extends('layouts.app')

@section('title', 'Kehadiran ' . $className . ' — SITEXA Absensi')
@section('page_title', 'Kehadiran hari ini')
@section('page_subtitle', $className . ' · ' . $todayLabel . ' (WIB)')

@section('content')
<div class="mx-auto max-w-lg space-y-8 animate-fade-in">
    <div>
        <a href="{{ route('schedules.index') }}" class="text-sm font-semibold text-sky-700 hover:underline">← Kembali ke jadwal</a>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
        <p class="text-sm font-medium text-slate-500">Jumlah siswa hadir</p>
        <p class="mt-2 text-5xl font-bold tabular-nums tracking-tight text-slate-900 sm:text-6xl">{{ $hadirCount }}</p>
        <p class="mt-4 text-sm text-slate-600">
            dari <span class="font-semibold text-slate-900">{{ $totalStudents }}</span> siswa terdaftar di kelas ini
        </p>
        <p class="mt-2 text-xs text-slate-500">
            Tanggal sistem: {{ $today }} · status <span class="font-mono font-medium">hadir</span> pada tanggal tersebut (Asia/Jakarta).
        </p>
    </div>

    <p class="text-center text-sm text-slate-500">
        Untuk rincian per siswa, buka <a href="{{ route('reports.absensi', ['class' => $className, 'start_date' => $today, 'end_date' => $today]) }}" class="font-semibold text-sky-700 hover:underline">Laporan absensi</a> dengan filter kelas dan tanggal yang sama.
    </p>
</div>
@endsection
