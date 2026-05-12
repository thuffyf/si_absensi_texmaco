@extends('layouts.app')

@section('title', 'Edit Guru — SITEXA Absensi')
@section('page_title', 'Edit Guru')

@section('content')
<div class="mb-6">
    <a href="{{ route('teachers.index') }}" class="btn-secondary text-sm">Kembali</a>
</div>

<div class="glass-card p-6 rounded-2xl">
    <h2 class="text-lg font-bold text-white mb-4">Perbarui Data Guru</h2>

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('teachers.update', $teacher) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @csrf
        @method('PUT')
        <input name="nip" value="{{ old('nip', $teacher->nip) }}" class="input-field" placeholder="NIP" required />
        <input name="name" value="{{ old('name', $teacher->name) }}" class="input-field" placeholder="Nama guru" required />
        <input name="email" value="{{ old('email', $teacher->email) }}" class="input-field" placeholder="Email" />
        <input name="subject" value="{{ old('subject', $teacher->subject) }}" class="input-field" placeholder="Mata pelajaran" />
        <input name="role" value="{{ old('role', $teacher->role) }}" class="input-field" placeholder="Role" />
        <input name="phone" value="{{ old('phone', $teacher->phone) }}" class="input-field" placeholder="No telepon" />
        <input name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($teacher->date_of_birth)->format('Y-m-d')) }}" class="input-field" />
        <select name="status" class="input-field text-sm" required>
            <option value="aktif" @selected(old('status', $teacher->status) === 'aktif')>Aktif</option>
            <option value="cuti" @selected(old('status', $teacher->status) === 'cuti')>Cuti</option>
            <option value="non_aktif" @selected(old('status', $teacher->status) === 'non_aktif')>Non aktif</option>
        </select>
        <input name="password" type="password" class="input-field" placeholder="Password baru (opsional)" />
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-3">Simpan Perubahan</button>
    </form>
</div>
@endsection
