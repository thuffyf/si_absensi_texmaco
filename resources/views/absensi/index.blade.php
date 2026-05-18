@extends('layouts.app')

@section('title', 'Absensi — SITEXA Absensi')
@section('page_title', 'Absensi')
@section('page_subtitle', 'Kelola data absensi siswa dan sinkronisasi API')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap gap-2">
            <form method="GET" action="{{ route('absensi.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" class="w-64 rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Cari siswa..." />
                <select name="class" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">Semua Kelas</option>
                    <option value="X" @selected(request('class') === 'X')">X</option>
                    <option value="XI" @selected(request('class') === 'XI')">XI</option>
                    <option value="XII" @selected(request('class') === 'XII')">XII</option>
                </select>
                <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">Semua Status</option>
                    <option value="hadir" @selected(request('status') === 'hadir')">Hadir</option>
                    <option value="izin" @selected(request('status') === 'izin')">Izin</option>
                    <option value="sakit" @selected(request('status') === 'sakit')">Sakit</option>
                    <option value="alpha" @selected(request('status') === 'alpha')">Alpha</option>
                </select>
                <button type="submit" class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari
                </button>
                <a href="{{ route('absensi.index') }}" class="flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Reset</a>
            </form>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('add-absensi-modal').classList.remove('hidden')" class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Absensi
            </button>
            <form method="POST" action="{{ route('absensi.sync') }}" class="inline">
                @csrf
                <button type="submit" class="flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Sync API
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Absensi -->
    <div id="add-absensi-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Tambah Data Absensi Baru</h3>
                <button onclick="document.getElementById('add-absensi-modal').classList.add('hidden')" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('absensi.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <select name="student_id" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="">Pilih siswa</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->name }} ({{ $student->nis }})</option>
                    @endforeach
                </select>
                <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="hadir" @selected(old('status') === 'hadir')">Hadir</option>
                    <option value="izin" @selected(old('status') === 'izin')">Izin</option>
                    <option value="sakit" @selected(old('status') === 'sakit')">Sakit</option>
                    <option value="alpha" @selected(old('status') === 'alpha')">Alpha</option>
                </select>
                <input name="attendance_date" type="date" value="{{ old('attendance_date', now()->format('Y-m-d')) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required />
                <input name="attendance_time" type="time" value="{{ old('attendance_time', now()->format('H:i')) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
                <textarea name="note" class="md:col-span-2 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" rows="3" placeholder="Keterangan">{{ old('note') }}</textarea>
                <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Absensi</button>
            </form>
        </div>
    </div>

    <div class="flex flex-col rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">NIS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">UID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $statusClasses = [
                            'hadir' => 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800',
                            'izin' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                            'sakit' => 'inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800',
                            'alpha' => 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800',
                        ];
                    @endphp

                    @forelse($records as $record)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 font-semibold text-sky-600">
                                        {{ strtoupper(substr($record->student?->name ?? '-', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $record->student?->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-sm text-slate-600 whitespace-nowrap">{{ $record->student?->nis ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->student?->class_name ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono text-sm text-slate-600 whitespace-nowrap">{{ $record->student?->uid_kartu ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->attendance_date?->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->attendance_time }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="{{ $statusClasses[$record->status] ?? 'inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-center">
        {{ $records->links() }}
    </div>
</div>
@endsection
