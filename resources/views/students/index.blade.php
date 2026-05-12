@extends('layouts.app')

@section('title', 'Data Siswa — SITEXA Absensi')
@section('page_title', 'Data Siswa')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">👥 Data Siswa</h1>
            <p class="text-gray-400">Kelola data siswa dan status NFC</p>
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
    <h2 class="text-lg font-bold text-white mb-4">Tambah Siswa</h2>
    <form method="POST" action="{{ route('students.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @csrf
        <input name="nim" value="{{ old('nim') }}" class="input-field" placeholder="NIM" required />
        <input name="name" value="{{ old('name') }}" class="input-field" placeholder="Nama siswa" required />
        <input name="email" value="{{ old('email') }}" class="input-field" placeholder="Email (opsional)" />
        <input name="class_name" value="{{ old('class_name') }}" class="input-field" placeholder="Kelas" required />
        <input name="major" value="{{ old('major') }}" class="input-field" placeholder="Jurusan" />
        <select name="status" class="input-field text-sm" required>
            <option value="aktif" @selected(old('status') === 'aktif')>Aktif</option>
            <option value="tidak_aktif" @selected(old('status') === 'tidak_aktif')>Tidak aktif</option>
            <option value="lulus" @selected(old('status') === 'lulus')>Lulus</option>
        </select>
        <select name="nfc_type" class="input-field text-sm" required>
            <option value="belum_terdaftar" @selected(old('nfc_type') === 'belum_terdaftar')>Belum terdaftar</option>
            <option value="kartu" @selected(old('nfc_type') === 'kartu')>Kartu</option>
            <option value="handphone" @selected(old('nfc_type') === 'handphone')>Handphone</option>
        </select>
        <input name="uid_kartu" value="{{ old('uid_kartu') }}" class="input-field" placeholder="UID kartu (opsional)" />
        <input name="phone" value="{{ old('phone') }}" class="input-field" placeholder="No telepon" />
        <input name="username" value="{{ old('username') }}" class="input-field" placeholder="Username (opsional)" />
        <input name="password" type="password" class="input-field" placeholder="Password (opsional)" />
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-4">Simpan Siswa</button>
    </form>
</div>

<div class="glass-card p-6 rounded-2xl mb-6">
    <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}" class="input-field w-full" placeholder="Cari nama, NIM, atau kelas" />
        </div>
        <input type="text" name="class" value="{{ request('class') }}" class="input-field text-sm" placeholder="Kelas" />
        <select name="status" class="input-field text-sm">
            <option value="">Semua Status</option>
            <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
            <option value="tidak_aktif" @selected(request('status') === 'tidak_aktif')>Tidak aktif</option>
            <option value="lulus" @selected(request('status') === 'lulus')>Lulus</option>
        </select>
        <select name="nfc" class="input-field text-sm">
            <option value="">Semua NFC</option>
            <option value="kartu" @selected(request('nfc') === 'kartu')>Kartu NFC</option>
            <option value="handphone" @selected(request('nfc') === 'handphone')>Handphone</option>
            <option value="belum_terdaftar" @selected(request('nfc') === 'belum_terdaftar')>Belum terdaftar</option>
        </select>
        <div class="flex gap-2 lg:col-span-5">
            <button class="btn-secondary text-sm" type="submit">Terapkan Filter</button>
            <a href="{{ route('students.index') }}" class="btn-secondary text-sm">Reset</a>
        </div>
    </form>
</div>

<div class="glass-card p-6 rounded-2xl overflow-x-auto">
    <table class="data-table">
        <thead>
            <tr>
                <th>Foto & Nama</th>
                <th>NIM / NIS</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>NFC</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusClasses = [
                    'aktif' => 'badge-success',
                    'tidak_aktif' => 'badge-warning',
                    'lulus' => 'badge-info',
                ];
                $nfcClasses = [
                    'kartu' => 'badge-neon',
                    'handphone' => 'badge-info',
                    'belum_terdaftar' => 'badge-warning',
                ];
            @endphp

            @forelse($students as $student)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neon-cyan to-neon-blue flex items-center justify-center font-bold shadow-glow-cyan-sm">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $student->name }}</p>
                                <p class="text-xs text-gray-400">{{ $student->email ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="font-mono text-neon-cyan">{{ $student->nim }}</span></td>
                    <td>{{ $student->class_name }}</td>
                    <td>{{ $student->major ?? '-' }}</td>
                    <td>
                        <span class="{{ $statusClasses[$student->status] ?? 'badge-info' }}">
                            {{ ucfirst(str_replace('_', ' ', $student->status)) }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ $nfcClasses[$student->nfc_type] ?? 'badge-info' }}">
                            {{ ucfirst(str_replace('_', ' ', $student->nfc_type)) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex justify-end gap-2">
                            <a class="btn-icon text-sm" href="{{ route('students.edit', $student) }}" title="Edit">✏️</a>
                            <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-icon text-sm" type="submit" title="Delete">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-sm text-gray-400">Belum ada data siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $students->links() }}
</div>
@endsection
