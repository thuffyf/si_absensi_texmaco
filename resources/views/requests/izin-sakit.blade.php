@extends('layouts.app')

@section('title', 'Izin & Sakit — SITEXA Absensi')
@section('page_title', 'Izin & Sakit')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">📋 Request Izin & Sakit</h1>
            <p class="text-gray-400">Kelola pengajuan izin dan sakit siswa</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="badge-warning">
                <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                {{ $pending->count() }} Tertunda
            </span>
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
    <h2 class="text-lg font-bold text-white mb-4">Buat Request Baru</h2>
    <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @csrf
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-300 ml-1">Siswa</label>
            <select name="student_id" class="input-field text-sm" required>
                <option value="">Pilih siswa</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->name }} ({{ $student->nis }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-300 ml-1">Jenis Pengajuan</label>
            <select name="type" class="input-field text-sm" required>
                <option value="izin" @selected(old('type') === 'izin')>Izin</option>
                <option value="sakit" @selected(old('type') === 'sakit')>Sakit</option>
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-300 ml-1">Tanggal Mulai</label>
            <input name="start_date" type="date" value="{{ old('start_date') }}" class="input-field" required />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-300 ml-1">Tanggal Selesai</label>
            <input name="end_date" type="date" value="{{ old('end_date') }}" class="input-field" />
        </div>
        <div class="flex flex-col gap-1 md:col-span-2 lg:col-span-4">
            <label class="text-xs font-semibold text-gray-300 ml-1">Alasan</label>
            <textarea name="reason" class="input-field" rows="3" placeholder="Alasan" required>{{ old('reason') }}</textarea>
        </div>
        <div class="md:col-span-2 lg:col-span-4">
            <label class="block text-sm font-medium text-gray-300 mb-2">Bukti gambar opsional</label>
            <input type="file" name="photo" accept="image/*" class="input-field" />
            <p class="mt-2 text-xs text-gray-500">Contoh: foto surat dokter atau surat izin.</p>
        </div>
        <button type="submit" class="btn-primary md:col-span-2 lg:col-span-4">Simpan Request</button>
    </form>
</div>

<div class="space-y-6 mb-8">
    <h3 class="text-xl font-bold text-white">Menunggu Verifikasi</h3>
    @forelse($pending as $request)
        <div class="glass-card p-6 rounded-2xl border border-yellow-500/20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-lg font-bold text-white">{{ $request->student?->name ?? '-' }}</h4>
                    <p class="text-sm text-gray-400">NIS: {{ $request->student?->nis ?? '-' }}</p>
                    <p class="text-sm text-gray-400">Kelas: {{ $request->student?->class_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Jenis</p>
                    <p class="text-sm font-semibold text-white">{{ ucfirst($request->type) }}</p>
                    <p class="text-xs text-gray-500 mt-3">Tanggal</p>
                    <p class="text-sm font-semibold text-white">{{ $request->start_date?->format('d M Y') }}{{ $request->end_date ? ' - ' . $request->end_date->format('d M Y') : '' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-2">Alasan</p>
                    <p class="text-sm text-gray-300 mb-4">{{ $request->reason }}</p>
                    @if($request->photo_url)
                        <div class="mb-4 overflow-hidden rounded-xl border border-white/10 bg-black/20">
                            <img src="{{ $request->photo_url }}" alt="Bukti {{ $request->student?->name ?? 'siswa' }}" class="h-48 w-full object-cover" />
                            <div class="px-3 py-2 text-xs text-gray-400">Lampiran bukti</div>
                        </div>
                    @endif
                    <div class="flex flex-col gap-2">
                        <form method="POST" action="{{ route('requests.approve', $request) }}">
                            @csrf
                            <button type="submit" class="btn-success text-sm w-full">✓ Terima</button>
                        </form>
                        <form method="POST" action="{{ route('requests.reject', $request) }}">
                            @csrf
                            <button type="submit" class="btn-danger text-sm w-full">✕ Tolak</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-sm text-gray-400">Tidak ada request menunggu.</div>
    @endforelse
</div>

<div class="mb-8">
    <h3 class="text-xl font-bold text-white mb-4">Disetujui</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($approved as $request)
            <div class="glass-card p-4 rounded-xl border border-emerald-500/20">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-white">{{ $request->student?->name ?? '-' }}</h4>
                        <p class="text-xs text-gray-400">NIS: {{ $request->student?->nis ?? '-' }} | {{ ucfirst($request->type) }}</p>
                    </div>
                    <span class="badge-success">Disetujui</span>
                </div>
                <p class="text-xs text-gray-400">Tanggal: {{ $request->start_date?->format('d M Y') }}{{ $request->end_date ? ' - ' . $request->end_date->format('d M Y') : '' }}</p>
                <p class="text-xs text-gray-300 mt-2">{{ $request->reason }}</p>
                @if($request->photo_url)
                    <div class="mt-3 overflow-hidden rounded-xl border border-white/10 bg-black/20">
                        <img src="{{ $request->photo_url }}" alt="Bukti {{ $request->student?->name ?? 'siswa' }}" class="h-36 w-full object-cover" />
                    </div>
                @endif
            </div>
        @empty
            <div class="text-sm text-gray-400">Belum ada request disetujui.</div>
        @endforelse
    </div>
</div>

<div class="mb-8">
    <h3 class="text-xl font-bold text-white mb-4">Ditolak</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($rejected as $request)
            <div class="glass-card p-4 rounded-xl border border-red-500/20">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-white">{{ $request->student?->name ?? '-' }}</h4>
                        <p class="text-xs text-gray-400">NIS: {{ $request->student?->nis ?? '-' }} | {{ ucfirst($request->type) }}</p>
                    </div>
                    <span class="badge-danger">Ditolak</span>
                </div>
                <p class="text-xs text-gray-400">Tanggal: {{ $request->start_date?->format('d M Y') }}{{ $request->end_date ? ' - ' . $request->end_date->format('d M Y') : '' }}</p>
                <p class="text-xs text-gray-300 mt-2">{{ $request->reason }}</p>
                @if($request->photo_url)
                    <div class="mt-3 overflow-hidden rounded-xl border border-white/10 bg-black/20">
                        <img src="{{ $request->photo_url }}" alt="Bukti {{ $request->student?->name ?? 'siswa' }}" class="h-36 w-full object-cover" />
                    </div>
                @endif
            </div>
        @empty
            <div class="text-sm text-gray-400">Belum ada request ditolak.</div>
        @endforelse
    </div>
</div>
@endsection
