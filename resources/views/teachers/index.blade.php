@extends('layouts.app')

@section('title', 'Data Guru — SITEXA Absensi')
@section('page_title', 'Data Guru')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">👨‍🏫 Data Guru</h1>
            <p class="text-gray-400">Kelola data guru dan monitoring kehadiran</p>
        </div>
    </div>
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

<div class="glass-card p-6 rounded-2xl mb-6">
    <h2 class="text-lg font-bold text-white mb-4">Tambah Guru</h2>
    <form method="POST" action="{{ route('teachers.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @csrf
        <input name="nip" value="{{ old('nip') }}" class="input-field" placeholder="NIP" required />
        <input name="name" value="{{ old('name') }}" class="input-field" placeholder="Nama guru" required />
        <input name="email" value="{{ old('email') }}" class="input-field" placeholder="Email" />
        <input name="subject" value="{{ old('subject') }}" class="input-field" placeholder="Mata pelajaran" />
        <input name="role" value="{{ old('role') }}" class="input-field" placeholder="Role" />
        <input name="phone" value="{{ old('phone') }}" class="input-field" placeholder="No telepon" />
        <input name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" class="input-field" />
        <select name="status" class="input-field text-sm" required>
            <option value="aktif" @selected(old('status') === 'aktif')>Aktif</option>
            <option value="cuti" @selected(old('status') === 'cuti')>Cuti</option>
            <option value="non_aktif" @selected(old('status') === 'non_aktif')>Non aktif</option>
        </select>
        <input name="password" type="password" class="input-field" placeholder="Password (opsional)" />
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-4">Simpan Guru</button>
    </form>
</div>

<div class="glass-card p-6 rounded-2xl mb-6">
    <form method="GET" action="{{ route('teachers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Cari nama atau NIP" />
        <input type="text" name="subject" value="{{ request('subject') }}" class="input-field" placeholder="Mata pelajaran" />
        <select name="status" class="input-field text-sm">
            <option value="">Semua Status</option>
            <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
            <option value="cuti" @selected(request('status') === 'cuti')>Cuti</option>
            <option value="non_aktif" @selected(request('status') === 'non_aktif')>Non aktif</option>
        </select>
        <div class="flex gap-2">
            <button class="btn-secondary text-sm" type="submit">Terapkan Filter</button>
            <a href="{{ route('teachers.index') }}" class="btn-secondary text-sm">Reset</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @php
        $statusClasses = [
            'aktif' => 'badge-success',
            'cuti' => 'badge-warning',
            'non_aktif' => 'badge-info',
        ];
    @endphp

    @forelse($teachers as $teacher)
        <div class="glass-card p-6 rounded-2xl border border-neon-cyan/20 hover:border-neon-cyan/50 transition-all animate-fade-in">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-2xl font-bold shadow-glow-cyan-sm">
                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-white">{{ $teacher->name }}</h3>
                    <p class="text-sm text-gray-400">NIP: {{ $teacher->nip }}</p>
                </div>
                <span class="{{ $statusClasses[$teacher->status] ?? 'badge-info' }}">{{ ucfirst(str_replace('_', ' ', $teacher->status)) }}</span>
            </div>

            <div class="space-y-2 mb-4 pb-4 border-b border-neon-cyan/10">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Mata Pelajaran</span>
                    <span class="text-white font-semibold">{{ $teacher->subject ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Email</span>
                    <span class="text-neon-cyan font-mono text-xs">{{ $teacher->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Telepon</span>
                    <span class="text-white font-semibold">{{ $teacher->phone ?? '-' }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a class="btn-secondary text-xs flex-1" href="{{ route('teachers.edit', $teacher) }}">✏️ Edit</a>
                <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('Hapus guru ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn-icon text-xs" type="submit">🗑️</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center text-sm text-gray-400">Belum ada data guru.</div>
    @endforelse
</div>

<div class="mt-4">
    {{ $teachers->links() }}
</div>
@endsection
