@extends('layouts.app')

@section('title', 'Kehadiran ' . $className . ' — SITEXA Absensi')
@section('page_title', 'Mata Pelajaran Hari Ini')
@section('page_subtitle', 'Minggu ke-' . $currentWeek . ' · ' . $todayLabel)

@section('content')
<div class="mx-auto max-w-4xl space-y-8 animate-fade-in">
    <div>
        <a href="{{ route('schedules.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors text-sm font-semibold shadow-sm">
            <span>←</span>
            <span>Kembali</span>
        </a>
    </div>

    @if($subjectsToday->count() > 0)
        <div class="space-y-4">
                @foreach($subjectsToday as $index => $subject)
                    <div class="rounded-3xl border-2 {{ $subject['is_running'] ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 bg-white' }} p-8 shadow-sm {{ $subject['is_running'] ? 'ring-2 ring-emerald-200' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-4 pb-3 border-b {{ $subject['is_running'] ? 'border-emerald-200' : 'border-slate-100' }}">
                                    <h3 class="text-2xl font-bold {{ $subject['is_running'] ? 'text-emerald-900' : 'text-slate-900' }}">{{ $subject['subject'] }}</h3>
                                    @if($subject['is_running'])
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-200 text-emerald-900 text-sm font-semibold animate-pulse">
                                            <span class="w-2 h-2 bg-emerald-600 rounded-full"></span>
                                            Sedang Berlangsung
                                        </span>
                                    @endif
                                </div>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wide {{ $subject['is_running'] ? 'text-emerald-700' : 'text-slate-500' }}">Waktu</dt>
                                        <dd class="mt-1 text-lg font-semibold {{ $subject['is_running'] ? 'text-emerald-900' : 'text-slate-900' }}">{{ $subject['start_time'] }} - {{ $subject['end_time'] }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wide {{ $subject['is_running'] ? 'text-emerald-700' : 'text-slate-500' }}">Guru Pengajar</dt>
                                        <dd class="mt-1 font-medium {{ $subject['is_running'] ? 'text-emerald-800' : 'text-slate-800' }}">{{ $subject['teacher_name'] }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 text-center">
                <p class="text-slate-600">Tidak ada jadwal untuk hari ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
