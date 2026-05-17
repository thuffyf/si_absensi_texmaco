@extends('layouts.app')

@section('title', 'Edit Guru — SITEXA Absensi')
@section('page_title', 'Edit Guru')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    <div class="flex items-center gap-4">
        <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
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
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Perbarui Data Guru</h2>
        <form method="POST" action="{{ route('teachers.update', $teacher) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @csrf
            @method('PUT')
            <input name="nip" value="{{ old('nip', $teacher->nip) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="NIP" required />
            <input name="name" value="{{ old('name', $teacher->name) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Nama guru" required />
            <input name="email" value="{{ old('email', $teacher->email) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Email" />
            <input name="subject" value="{{ old('subject', $teacher->subject) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Mata pelajaran" />
            <input name="role" value="{{ old('role', $teacher->role) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Role" />
            <input name="phone" value="{{ old('phone', $teacher->phone) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="No telepon" />
            <input name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($teacher->date_of_birth)->format('Y-m-d')) }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
            <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                <option value="aktif" @selected(old('status', $teacher->status) === 'aktif')>Aktif</option>
                <option value="cuti" @selected(old('status', $teacher->status) === 'cuti')">Cuti</option>
                <option value="non_aktif" @selected(old('status', $teacher->status) === 'non_aktif')">Non aktif</option>
            </select>
            <input name="password" type="password" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Password baru (opsional)" />
            <button type="submit" class="col-span-1 md:col-span-2 lg:col-span-3 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
