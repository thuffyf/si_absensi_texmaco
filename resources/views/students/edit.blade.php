@extends('layouts.app')

@section('title', 'Edit Siswa — SITEXA Absensi')
@section('page_title', 'Edit Siswa')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    <div class="flex items-center gap-4">
        <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Perbarui Data Siswa</h2>
        <form method="POST" action="{{ route('students.update', $student) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">NIS</label>
                <input name="nis" value="{{ old('nis', $student->nis) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="NIS" required />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Nama Siswa</label>
                <input name="name" value="{{ old('name', $student->name) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Nama siswa" required />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Email</label>
                <input name="email" type="email" value="{{ old('email', $student->email) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Email" required />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Kelas</label>
                <select name="class_name" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="X" @selected(old('class_name', $student->class_name) === 'X')>X</option>
                    <option value="XI" @selected(old('class_name', $student->class_name) === 'XI')>XI</option>
                    <option value="XII" @selected(old('class_name', $student->class_name) === 'XII')>XII</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Jurusan</label>
                <select name="major" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="Teknik Elektronika Industri" @selected(old('major', $student->major) === 'Teknik Elektronika Industri')>Teknik Elektronika Industri</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Tanggal Lahir</label>
                <input name="date_of_birth" type="date" value="{{ old('date_of_birth', $student->date_of_birth?->toDateString()) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Status</label>
                <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="aktif" @selected(old('status', $student->status) === 'aktif')>Aktif</option>
                    <option value="tidak_aktif" @selected(old('status', $student->status) === 'tidak_aktif')>Tidak aktif</option>
                    <option value="lulus" @selected(old('status', $student->status) === 'lulus')>Lulus</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Tipe NFC</label>
                <select name="nfc_type" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="belum_terdaftar" @selected(old('nfc_type', $student->nfc_type) === 'belum_terdaftar')>Belum terdaftar</option>
                    <option value="kartu" @selected(old('nfc_type', $student->nfc_type) === 'kartu')>Kartu</option>
                    <option value="handphone" @selected(old('nfc_type', $student->nfc_type) === 'handphone')>Handphone</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">UID Kartu</label>
                <input name="uid_kartu" value="{{ old('uid_kartu', $student->uid_kartu) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="UID kartu" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">No Telepon</label>
                <input name="phone" value="{{ old('phone', $student->phone) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="No telepon" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Username</label>
                <input name="username" value="{{ old('username', $student->username) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Username" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-700 ml-1">Password Baru</label>
                <input name="password" type="password" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Password baru (opsional)" />
            </div>
            <button type="submit" class="col-span-1 md:col-span-2 lg:col-span-3 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
