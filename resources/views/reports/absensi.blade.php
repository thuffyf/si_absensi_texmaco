@extends('layouts.app')

@section('title', 'Laporan — SITEXA Absensi')
@section('page_title', 'Laporan')
@section('page_subtitle', 'Analisis data absensi siswa dan statistik kehadiran')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    @php
        $hadir = $statusCounts['hadir'] ?? 0;
        $izin = $statusCounts['izin'] ?? 0;
        $sakit = $statusCounts['sakit'] ?? 0;
        $alpa = $statusCounts['alpa'] ?? 0;
        $total = max($totalRecords, 1);
        $attendanceRate = round(($hadir / $total) * 100, 1);
    @endphp

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Siswa</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalStudents }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Kehadiran</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $attendanceRate }}%</p>
            <p class="text-xs text-emerald-600 mt-2">Dari {{ $totalRecords }} data</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Izin</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $izin }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Sakit</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $sakit }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Alpa</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $alpa }}</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Filter Laporan</h3>
        <form method="GET" action="{{ route('reports.absensi') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Dari</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Hingga</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Kelas</label>
                <input type="text" name="class" value="{{ $filters['class'] ?? '' }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 w-full" placeholder="Kelas" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 w-full">
                    <option value="">Semua Status</option>
                    <option value="hadir" @selected(($filters['status'] ?? '') === 'hadir')>Hadir</option>
                    <option value="izin" @selected(($filters['status'] ?? '') === 'izin')>Izin</option>
                    <option value="sakit" @selected(($filters['status'] ?? '') === 'sakit')>Sakit</option>
                    <option value="alpa" @selected(($filters['status'] ?? '') === 'alpa')>Alpa</option>
                </select>
            </div>
            <div class="flex flex-wrap gap-2 md:col-span-2 lg:col-span-4">
                <button class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2" type="submit">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Tampilkan Laporan
                </button>
                <a href="{{ route('reports.absensi') }}" class="flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Reset</a>
                <a href="{{ route('reports.absensi.download-csv', request()->query()) }}" class="flex items-center justify-center rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 shadow-sm transition-colors hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Download CSV
                </a>
                <a href="{{ route('reports.absensi.download-pdf', request()->query()) }}" class="flex items-center justify-center rounded-xl border border-red-300 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 shadow-sm transition-colors hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Report Table -->
    <div class="flex flex-col rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Hadir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Jam Hadir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Izin</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Sakit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Alpa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($rows as $row)
                        @php
                            $student = $row['student'];
                            $totalRow = max($row['total'], 1);
                            $percent = round(($row['hadir'] / $totalRow) * 100, 1);
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 font-semibold text-sky-600">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $student->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $student->class_name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">{{ $row['hadir'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $row['last_time'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ $row['izin'] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">{{ $row['sakit'] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">{{ $row['alpa'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $row['total'] }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-sky-600">{{ $percent }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
