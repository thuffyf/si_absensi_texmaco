@extends('layouts.app')

@section('title', 'Absen — SITEXA Absensi')
@section('page_title', 'Absen')
@section('page_subtitle', 'Isi absensi harian sebagai siswa SITEXA')

@section('content')
    <div class="space-y-4">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-900">
                {{ session('error') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-4 lg:grid-cols-[1.5fr_1fr]">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Halo, {{ auth()->user()->name }}</h2>
                    <p class="mt-2 text-slate-600">Email: {{ auth()->user()->email }}</p>
                    <p class="mt-1 text-slate-600">Nama siswa: {{ $student->name }}</p>
                    <p class="mt-1 text-slate-600">Kelas: {{ $student->class_name }} / {{ $student->major }}</p>
                    <p class="mt-1 text-slate-600">NIS: {{ $student->nis }}</p>
                </div>
                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Status Absen Hari Ini</p>
                    @if($attendance)
                        <p class="mt-3 text-5xl font-bold text-slate-900 uppercase">{{ $attendance->status }}</p>
                        <p class="mt-2 text-sm text-slate-500">Waktu: {{ $attendance->attendance_time }}</p>
                        @if($attendance->note)
                            <p class="mt-2 text-sm text-slate-500">Catatan: {{ $attendance->note }}</p>
                        @endif
                    @else
                        <p class="mt-3 text-5xl font-bold text-slate-900">Belum</p>
                        <p class="mt-2 text-sm text-slate-500">Silakan isi absensi untuk hari ini.</p>
                    @endif
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Form Absen Siswa</h2>
            <p class="mt-2 text-sm text-slate-500">Pilih status kehadiran dan simpan untuk mencatat absensi hari ini.</p>

            <form action="{{ route('absensi.student.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700">Status Kehadiran</label>
                    <select id="status" name="status" required class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        <option value="" disabled selected>-- Pilih status --</option>
                        <option value="hadir" {{ old('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ old('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ old('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpha" {{ old('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="note" class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                    <textarea id="note" name="note" rows="3" class="mt-2 block w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Misal: Sakit demam, izin pulang cepat...">{{ old('note') }}</textarea>
                    @error('note')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Simpan Absen
                </button>
            </form>
        </section>
    </div>
@endsection
