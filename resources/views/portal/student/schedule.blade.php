@extends('portal.layouts.app')

@section('title', 'Jadwal Siswa - SITEXA')
@section('page_title', 'Jadwal')
@section('page_subtitle', 'Kelas ' . $student->class_name)

@section('content')
    @include('portal.partials.student-status')

    @php
        $totalLessons = $schedulesByDay->flatten()->count();
        $todayCount = $schedulesByDay->get($todayName)?->count() ?? 0;
    @endphp

    <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-sky-500 to-sky-700 px-5 py-5 text-white shadow-lg">
        <p class="text-sm text-sky-100">Hari ini · {{ $todayName }}</p>
        <h2 class="mt-1 text-xl font-bold">{{ $student->class_name }}</h2>
        <div class="mt-3 flex gap-4">
            <div>
                <p class="text-2xl font-bold">{{ $todayCount }}</p>
                <p class="text-xs text-sky-100/70">Pelajaran hari ini</p>
            </div>
            <div class="h-10 w-px bg-white/20"></div>
            <div>
                <p class="text-2xl font-bold">{{ $totalLessons }}</p>
                <p class="text-xs text-sky-100/70">Total minggu ini</p>
            </div>
        </div>
    </section>

    <section class="mt-4 space-y-4">
        @forelse ($schedulesByDay as $day => $items)
            @php $isToday = $day === $todayName; @endphp
            <article class="overflow-hidden rounded-2xl {{ $isToday ? 'bg-sky-50' : 'bg-white' }} shadow-sm">
                <div class="flex items-center justify-between border-b {{ $isToday ? 'border-sky-100 bg-sky-100/50' : 'border-slate-100 bg-slate-50' }} px-4 py-3">
                    <div class="flex items-center gap-2">
                        @if ($isToday)
                            <span class="flex h-2 w-2 animate-pulse rounded-full bg-sky-500"></span>
                        @endif
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $day }}</h3>
                            <p class="text-xs text-slate-500">{{ $items->count() }} pelajaran</p>
                        </div>
                    </div>
                    @if ($isToday)
                        <span class="rounded-full bg-sky-600 px-3 py-1 text-xs font-semibold text-white shadow-sm">Hari ini</span>
                    @endif
                </div>

                {{-- Timeline --}}
                <div class="relative p-4">
                    @if ($items->count() > 1)
                        <div class="portal-timeline-line absolute bottom-6 left-[2.125rem] top-6 w-0.5 bg-slate-200"></div>
                    @endif

                    <div class="space-y-4">
                        @foreach ($items as $index => $schedule)
                            <div class="portal-timeline-item relative flex gap-3">
                                <div class="relative z-10 flex shrink-0 flex-col items-center">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $isToday ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600' }} text-xs font-bold shadow-sm">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="flex flex-1 items-start gap-3 rounded-xl bg-white px-3 py-3 {{ $isToday ? 'ring-1 ring-sky-200' : '' }}">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-start justify-between gap-x-2 gap-y-1">
                                            <p class="font-semibold text-slate-900">{{ $schedule->subject }}</p>
                                            <span class="shrink-0 rounded-lg {{ $isToday ? 'bg-sky-600 text-white' : 'bg-slate-200 text-slate-700' }} px-2.5 py-1 text-[11px] font-semibold">
                                                {{ $schedule->start_time?->format('H:i') ?? '-' }}–{{ $schedule->end_time?->format('H:i') ?? '-' }}
                                            </span>
                                        </div>
                                        <p class="mt-1 flex items-center gap-1 text-xs text-slate-500">
                                            <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $schedule->teacher?->name ?? 'Guru belum diatur' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>
        @empty
            @include('portal.partials.student-empty-state', [
                'icon' => 'calendar',
                'title' => 'Belum ada jadwal',
                'description' => 'Jadwal pelajaran untuk kelas ini belum tersedia.',
            ])
        @endforelse
    </section>
@endsection
