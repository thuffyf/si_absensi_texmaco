@extends('layouts.app')

@section('title', 'Absensi — SITEXA Absensi')
@section('page_title', 'Absensi')
@section('page_subtitle', 'Kelola data absensi siswa')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap gap-2">
            <form method="GET" action="{{ route('absensi.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" class="w-64 rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Cari siswa..." />
                <button type="submit" class="flex items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Cari
                </button>
                <a href="{{ route('absensi.index') }}" class="flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Reset</a>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Absensi dihapus - Absensi otomatis dari tap in, notifikasi, dan penolakan siswa sakit/izin -->

    <div class="mt-6 flex flex-col rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden min-w-0">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">NIS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $statusClasses = [
                            'hadir' => 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800',
                            'izin' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                            'sakit' => 'inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800',
                            'alpa' => 'inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800',
                        ];
                    @endphp

                    @forelse($records as $record)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 font-semibold text-sky-600">
                                        {{ strtoupper(substr($record->student?->name ?? '-', 0, 1)) }}
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $record->student?->name ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 font-mono text-sm text-slate-600 whitespace-nowrap">{{ $record->student?->nis ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->student?->class_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->attendance_date?->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->attendance_time }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="{{ $statusClasses[$record->status] ?? 'inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800' }}">
                                    {{ $record->status === 'alpa' ? 'Alpa' : ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-nowrap">{{ $record->note ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('edit-modal-{{ $record->id }}').classList.remove('hidden')" class="inline-flex items-center justify-center rounded-xl bg-amber-100 p-2 text-amber-700 hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button type="button" onclick="document.getElementById('delete-modal-{{ $record->id }}').classList.remove('hidden')" class="inline-flex items-center justify-center rounded-xl bg-red-100 p-2 text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>

                                <!-- Edit Modal -->
                                <div id="edit-modal-{{ $record->id }}" class="fixed inset-0 z-[9999] hidden bg-slate-900/50 backdrop-blur-sm">
                                    <div class="flex min-h-screen items-center justify-center p-4">
                                        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl whitespace-normal text-left">
                                            <div class="mb-6 flex items-center justify-between">
                                                <h3 class="text-lg font-semibold text-slate-900">Edit Absensi</h3>
                                                <button type="button" onclick="document.getElementById('edit-modal-{{ $record->id }}').classList.add('hidden')" class="rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-500">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            <form method="POST" action="{{ route('absensi.update', $record) }}" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700">Status</label>
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700">Keterangan</label>
                                                    <textarea name="note" class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" rows="3">{{ $record->note }}</textarea>
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" onclick="document.getElementById('edit-modal-{{ $record->id }}').classList.add('hidden')" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                                                    <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Simpan Perubahan</button>
                                            </form>

                                <!-- Delete Modal -->
                                <div id="delete-modal-{{ $record->id }}" class="fixed inset-0 z-[9999] hidden bg-slate-900/50 backdrop-blur-sm">
                                    <div class="flex min-h-screen items-center justify-center p-4">
                                        <div class="w-full max-w-sm rounded-3xl bg-white p-6 shadow-xl whitespace-normal text-left">
                                            <div class="mb-6 flex flex-col items-center justify-center text-center">
                                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                <h3 class="text-lg font-semibold text-slate-900">Hapus Data Absensi?</h3>
                                                <p class="mt-2 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan. Data absensi untuk <strong>{{ $record->student?->name }}</strong> akan dihapus secara permanen.</p>
                                            <div class="flex gap-3">
                                                <button type="button" onclick="document.getElementById('delete-modal-{{ $record->id }}').classList.add('hidden')" class="flex-1 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                                                <form method="POST" action="{{ route('absensi.destroy', $record) }}" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Hapus</button>
                                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

    <div class="flex justify-center">
        {{ $records->links() }}
@endsection
