@extends('layouts.app')

@section('title', 'Jadwal Pelajaran — SITEXA Absensi')
@section('page_title', 'Jadwal Pelajaran')
@section('page_subtitle', 'Jadwal pelajaran kelas {{ $student->class_name }}')

@section('content')
<div class="mx-auto max-w-6xl space-y-8">
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($schedulesByDay as $day => $items)
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between bg-slate-50 px-4 py-3">
                    <h3 class="text-sm font-semibold text-slate-700">{{ $day }}</h3>
                    <span class="text-xs font-medium text-slate-500">{{ $items->count() }} pelajaran</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($items as $schedule)
                        <div class="flex flex-col gap-3 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $schedule->subject }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $schedule->teacher?->name ?? '—' }}
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-600">
                                    {{ $schedule->start_time?->format('H:i') ?? '-' }} - {{ $schedule->end_time?->format('H:i') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                Belum ada jadwal pelajaran untuk kelas ini.
            </div>
        @endforelse
    </div>
</div>
@endsection
