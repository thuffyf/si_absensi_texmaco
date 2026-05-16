@extends('layouts.app')

@section('title', 'Edit Siswa — SITEXA Absensi')
@section('page_title', 'Edit Siswa')

@section('content')
<div class="mb-6">
    <a href="{{ route('students.index') }}" class="btn-secondary text-sm">Kembali</a>
</div>

<div class="glass-card p-6 rounded-2xl">
    <h2 class="text-lg font-bold text-white mb-4">Perbarui Data Siswa</h2>

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('students.update', $student) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @csrf
        @method('PUT')
        <input name="nis" value="{{ old('nis', $student->nis) }}" class="input-field" placeholder="NIS" required />
        <input name="name" value="{{ old('name', $student->name) }}" class="input-field" placeholder="Nama siswa" required />
        <input name="email" value="{{ old('email', $student->email) }}" class="input-field" placeholder="Email" />
        <input name="class_name" value="{{ old('class_name', $student->class_name) }}" class="input-field" placeholder="Kelas" required />
        <input name="major" value="{{ old('major', $student->major) }}" class="input-field" placeholder="Jurusan" />
        <select name="status" class="input-field text-sm" required>
            <option value="aktif" @selected(old('status', $student->status) === 'aktif')>Aktif</option>
            <option value="tidak_aktif" @selected(old('status', $student->status) === 'tidak_aktif')>Tidak aktif</option>
            <option value="lulus" @selected(old('status', $student->status) === 'lulus')>Lulus</option>
        </select>
        <select name="nfc_type" class="input-field text-sm" required>
            <option value="belum_terdaftar" @selected(old('nfc_type', $student->nfc_type) === 'belum_terdaftar')>Belum terdaftar</option>
            <option value="kartu" @selected(old('nfc_type', $student->nfc_type) === 'kartu')>Kartu</option>
            <option value="handphone" @selected(old('nfc_type', $student->nfc_type) === 'handphone')>Handphone</option>
        </select>
        <input name="uid_kartu" value="{{ old('uid_kartu', $student->uid_kartu) }}" class="input-field" placeholder="UID kartu" />
        <input name="phone" value="{{ old('phone', $student->phone) }}" class="input-field" placeholder="No telepon" />
        <input name="username" value="{{ old('username', $student->username) }}" class="input-field" placeholder="Username" />
        <input name="password" type="password" class="input-field" placeholder="Password baru (opsional)" />
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-3">Simpan Perubahan</button>
    </form>
</div>
@endsection
