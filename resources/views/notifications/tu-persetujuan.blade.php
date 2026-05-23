@extends('layouts.app')

@section('title', 'Persetujuan TU — SITEXA Absensi')
@section('page_title', 'Persetujuan TU')
@section('page_subtitle', 'Keputusan final untuk permintaan Izin/Sakit siswa')

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
        @if($pending->count() > 0)
            <div class="space-y-4">
                @foreach($pending as $request)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $request->type === 'izin' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }} font-bold text-lg">
                                        {{ $request->type === 'izin' ? '⚠' : '✕' }}
                                    </div>
                                    <div>
                                        <p class="text-lg font-semibold text-slate-900">{{ $request->student->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $request->student->class_name }} / {{ $request->student->major }}</p>
                                        <p class="text-sm text-slate-500">NIS: {{ $request->student->nis }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 space-y-2">
                                    <div class="rounded-xl bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis Permintaan</p>
                                        <p class="mt-1 text-sm font-semibold {{ $request->type === 'izin' ? 'text-amber-900' : 'text-red-900' }}">
                                            {{ ucfirst($request->type) }}
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alasan</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $request->reason }}</p>
                                    </div>
                                    @if($request->response_note)
                                        <div class="rounded-xl bg-emerald-50 p-3">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Catatan Guru</p>
                                            <p class="mt-1 text-sm text-emerald-900">{{ $request->response_note }}</p>
                                        </div>
                                    @endif
                                    <div class="rounded-xl bg-white p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Request</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $request->requested_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex shrink-0 flex-col gap-2 sm:w-64">
                                <form action="{{ route('notifications.tu-approve', $request) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                                        <textarea name="response_note" rows="2" class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Catatan persetujuan..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-emerald-700">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('notifications.tu-reject', $request) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Alasan Penolakan</label>
                                        <textarea name="rejection_reason" rows="2" required class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Jelaskan alasan penolakan..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                Tidak ada permintaan Izin/Sakit yang menunggu persetujuan TU.
            </div>
        @endif
    </section>
</div>
@endsection
