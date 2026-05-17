@extends('layouts.app')

@section('title', 'Persetujuan Guru — SITEXA Absensi')
@section('page_title', 'Persetujuan dari Guru')
@section('page_subtitle', 'Izin & alpha — menunggu Terima atau Tolak')

@section('content')
@php
@endphp

<div class="mx-auto max-w-4xl space-y-5 pb-8">
    <p class="text-sm text-slate-600">
        Berikut permintaan izin/sakit yang menunggu persetujuan Anda. Pilih <strong class="text-slate-900">Terima</strong> atau <strong class="text-slate-900">Tolak</strong> untuk setiap pengajuan.
    </p>

    <div class="space-y-5" id="approval-list">
        @forelse ($pending as $request)
            <article
                class="approval-card rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
                data-id="{{ $request->id }}"
            >
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

                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('notifications.approve', $request) }}" class="flex-1 min-w-[8rem] sm:flex-none">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-approve w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-emerald-700">
                            Terima
                        </button>
                    </form>
                    <form method="POST" action="{{ route('notifications.reject', $request) }}" class="flex-1 min-w-[8rem] sm:flex-none">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-reject w-full inline-flex items-center justify-center rounded-2xl border-2 border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-800 hover:border-rose-300 hover:bg-rose-50">
                            Tolak
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                Tidak ada notifikasi yang menunggu persetujuan.
            </p>
        @endforelse
    </div>

</div>
@endsection
