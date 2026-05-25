@extends('layouts.app')

@section('title', 'Dashboard Guru — SITEXA Absensi')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Monitoring permintaan Izin/Sakit siswa')

@section('content')
<div class="mx-auto flex w-full max-w-none flex-col gap-3 max-lg:space-y-1 lg:h-full lg:min-h-0 lg:gap-3 lg:overflow-hidden">
    <div class="grid min-h-0 flex-1 grid-cols-1 gap-3 lg:grid-cols-12 lg:gap-4 lg:overflow-hidden">
        <!-- Main Content -->
        <section class="flex min-h-0 flex-col lg:col-span-9 lg:overflow-hidden">
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <div class="mt-3 grid shrink-0 grid-cols-3 gap-2 sm:grid-cols-3 lg:mt-4 lg:gap-3">
                    <div class="rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-800 lg:text-xs">Menunggu</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-amber-900 lg:text-3xl">{{ $pendingCount }}</p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800 lg:text-xs">Disetujui</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-900 lg:text-3xl">{{ $approvedCount }}</p>
                    </div>
                    <div class="rounded-xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-red-800 lg:text-xs">Ditolak</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-red-900 lg:text-3xl">{{ $rejectedCount }}</p>
                    </div>
                </div>

                <div class="mt-4 min-h-0 flex-1 overflow-hidden rounded-xl border border-slate-100 bg-slate-50/40 p-2.5 lg:mt-4 lg:flex lg:items-stretch lg:justify-center lg:p-3">
                    @if($allRequests->count() > 0)
                        <div class="w-full space-y-3 overflow-hidden lg:flex-1 lg:overflow-y-auto">
                            @foreach($allRequests as $request)
                                <div class="flex flex-col gap-3 rounded-lg bg-white p-4 border border-slate-200">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $request->type === 'izin' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }} text-sm font-bold shrink-0">
                                            {{ substr($request->student->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-slate-900">{{ $request->student->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $request->student->class_name }} · {{ $request->type === 'izin' ? 'Izin' : 'Sakit' }}</p>
                                            <p class="text-xs text-slate-400">{{ $request->requested_at?->format('d/m/Y H:i') }}</p>
                                            <p class="text-xs text-slate-400 mt-1">{{ $request->start_date?->format('d/m/Y') }} sd {{ $request->end_date?->format('d/m/Y') ?? '—' }}</p>
                                        </div>
                                        <span @class([
                                            'rounded-full px-2 py-1 text-xs font-semibold shrink-0',
                                            'bg-emerald-100 text-emerald-800' => $request->status === 'approved',
                                            'bg-red-100 text-red-800' => $request->status === 'rejected',
                                            'bg-amber-100 text-amber-800' => $request->status === 'pending_teacher',
                                            'bg-slate-100 text-slate-800' => $request->status === 'pending_admin',
                                        ])>{{ match($request->status) {
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'pending_teacher' => 'Menunggu',
                                            'pending_admin' => 'Menunggu TU',
                                            default => strtoupper($request->status),
                                        } }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600">{{ $request->reason }}</p>
                                    @if($request->photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $request->photo) }}" alt="Bukti {{ $request->type }}" class="max-h-32 rounded-lg border border-slate-200 object-cover">
                                        </div>
                                    @endif
                                    @if($request->response_note)
                                        <div class="mt-2 text-xs text-slate-500">
                                            <span class="font-semibold">Catatan:</span> {{ $request->response_note }}
                                        </div>
                                    @endif
                                    @if($request->rejection_reason)
                                        <div class="mt-2 text-xs text-red-600">
                                            <span class="font-semibold">Alasan Penolakan:</span> {{ str_replace('Admin konfirmasi: ', '', $request->rejection_reason) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex h-full items-center justify-center text-slate-500">
                            <p class="text-sm">Belum ada permintaan izin/sakit.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Right Sidebar -->
        <div class="flex min-h-0 flex-col gap-4 lg:col-span-3 lg:gap-4 lg:overflow-hidden">
            <section class="shrink-0 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500 lg:text-sm">Total Permintaan</h2>
                <p class="mt-2 text-5xl font-bold tabular-nums text-slate-900 lg:text-6xl xl:text-7xl">{{ $totalCount }}</p>
                <p class="mt-2 text-sm text-slate-500 lg:text-base">Semua waktu</p>
                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100 lg:h-3">
                    <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-blue-600" style="width: {{ $approvedRate }}%"></div>
                </div>
                <p class="mt-2 text-xs leading-snug text-slate-500 lg:text-sm">{{ $approvedRate }}% disetujui.</p>
            </section>

            <!-- Recent Activity -->
            <section class="min-h-0 flex-1 overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:flex lg:flex-col lg:p-4">
                <h2 class="shrink-0 text-sm font-bold text-slate-900 lg:text-base">Aktivitas Guru</h2>
                @if($teacherActivities->count() > 0)
                    <div class="mt-2 flex-1 space-y-2 overflow-hidden lg:mt-2 lg:flex-1 lg:overflow-y-auto">
                        @foreach($teacherActivities as $request)
                            <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-2">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full {{ $request->type === 'izin' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }} text-[10px] font-bold">
                                    {{ substr($request->student->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-xs font-medium text-slate-900 lg:text-sm">{{ $request->student->name }}</p>
                                    <p class="text-[10px] text-slate-500 lg:text-xs">{{ $request->responded_at?->format('d/m') }}</p>
                                </div>
                                <span @class([
                                    'rounded-full px-1.5 py-0.5 text-[10px] font-semibold lg:text-xs',
                                    'bg-emerald-100 text-emerald-800' => $request->status === 'approved',
                                    'bg-red-100 text-red-800' => $request->status === 'rejected',
                                ])>{{ $request->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-2 text-xs text-slate-500 lg:text-sm">Belum ada aktivitas guru.</p>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
