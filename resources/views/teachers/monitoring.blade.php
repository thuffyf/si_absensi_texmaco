@extends('layouts.app')

@section('title', 'Monitoring Absensi Guru — SITEXA Absensi')
@section('page_title', 'Monitoring Absensi Guru')
@section('page_subtitle', 'Rekapan permintaan Izin/Sakit siswa')

@section('content')
<div class="mx-auto max-w-6xl space-y-5 pb-8">
    <p class="text-sm text-slate-600">
        Berikut rekapan semua permintaan izin/sakit siswa dengan foto dan keterangan lengkap.
    </p>

    <div class="space-y-5">
        @forelse ($requests as $request)
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <span @class([
                            'inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide',
                            'bg-amber-100 text-amber-900' => $request->type === 'izin',
                            'bg-rose-100 text-rose-900' => $request->type === 'sakit',
                        ])>{{ $request->type === 'izin' ? 'Izin' : 'Sakit' }}</span>
                        <h2 class="mt-3 text-xl font-bold text-slate-900">{{ $request->student->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">NIS {{ $request->student->nis }} · {{ $request->student->class_name }}</p>
                    </div>
                    <div class="text-right text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">{{ $request->requested_at?->format('d M Y') }}</p>
                        <p class="text-slate-500">{{ $request->start_date?->format('d/m/Y') }} sd {{ $request->end_date?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alasan</p>
                    <blockquote class="mt-2 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-base leading-relaxed text-slate-800">
                        "{{ $request->reason }}"
                    </blockquote>
                </div>

                @if($request->photo)
                    <div class="mt-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Bukti</p>
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $request->photo) }}" alt="Bukti {{ $request->type }}" class="max-h-64 rounded-2xl border border-slate-200 object-cover">
                        </div>
                    </div>
                @endif

                <div class="mt-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                    <span @class([
                        'mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide',
                        'bg-emerald-100 text-emerald-900' => $request->status === 'approved',
                        'bg-red-100 text-red-900' => $request->status === 'rejected',
                        'bg-amber-100 text-amber-900' => $request->status === 'pending_teacher',
                        'bg-slate-100 text-slate-900' => $request->status === 'pending_admin',
                    ])>{{ match($request->status) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'pending_teacher' => 'Menunggu Guru',
                        'pending_admin' => 'Menunggu TU',
                        default => strtoupper($request->status),
                    } }}</span>
                </div>

                @if($request->response_note)
                    <div class="mt-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Catatan Respon</p>
                        <p class="mt-2 text-sm text-slate-700">{{ $request->response_note }}</p>
                    </div>
                @endif

                @if($request->rejection_reason)
                    <div class="mt-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alasan Penolakan</p>
                        <p class="mt-2 text-sm text-slate-700">{{ $request->rejection_reason }}</p>
                    </div>
                @endif
            </article>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                Belum ada permintaan izin/sakit.
            </p>
        @endforelse
    </div>
</div>
@endsection
