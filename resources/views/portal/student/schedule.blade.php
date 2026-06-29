@extends('portal.layouts.app')

@section('title', 'Jadwal Siswa - SITEXA')
@section('page_title', 'Jadwal')
@section('page_subtitle', 'Kelas ' . $student->class_name)

@section('content')
    @include('portal.partials.student-status')

    @php
        $weeks = [
            'Minggu Produktif 1',
            'Minggu Normatif 1',
            'Minggu Produktif 2',
            'Minggu Normatif 2',
        ];
        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    @endphp

    <section class="mt-2 space-y-8">
        @foreach($weeks as $weekName)
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-100 px-6 py-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $weekName }}
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
                        @foreach ($daysOfWeek as $day)
                            @php
                                $items = $schedulesByDay->get($day) ?? collect();
                            @endphp
                            <div class="bg-slate-50/80 rounded-2xl border border-slate-200/60 p-4 flex flex-col h-[28rem]">
                                <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-3 shrink-0">
                                    <h4 class="font-bold text-slate-800 uppercase tracking-wide">{{ $day }}</h4>
                                    <span class="text-[10px] font-bold bg-white text-slate-500 px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">{{ $items->count() }} Mapel</span>
                                </div>
                                
                                <div class="space-y-3 overflow-y-auto pr-2 -mr-2 pb-1 custom-scrollbar">
                                    @forelse ($items as $index => $schedule)
                                        <div class="bg-white p-3.5 rounded-xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:border-sky-200 transition-colors group">
                                            <div class="flex justify-between items-start gap-2 mb-2.5">
                                                <p class="font-semibold text-slate-800 text-sm leading-tight group-hover:text-sky-700 transition-colors">{{ $schedule->subject }}</p>
                                                <span class="shrink-0 bg-sky-50 text-sky-600 text-[10px] font-bold px-2 py-1 rounded-md border border-sky-100">
                                                    {{ $schedule->start_time?->format('H:i') ?? '-' }} - {{ $schedule->end_time?->format('H:i') ?? '-' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-auto">
                                                <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-[11px] font-medium text-slate-500 truncate">{{ $schedule->teacher?->name ?? 'Belum Diatur' }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="flex h-full min-h-[10rem] flex-col items-center justify-center text-center">
                                            <svg class="mb-2 h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-[11px] font-medium text-slate-400">Kosong</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    @push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 10px;
        }
    </style>
    @endpush
@endsection
