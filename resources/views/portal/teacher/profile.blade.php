@extends('portal.layouts.app')

@section('title', 'Profil Guru - SITEXA')
@section('page_title', 'Profil')
@section('page_subtitle', 'Data pribadi guru')

@section('content')
    @php
        $initial = strtoupper(substr(trim($teacher->name ?: 'G'), 0, 1));
    @endphp

    {{-- Profile hero --}}
    <section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-emerald-600 via-emerald-700 to-slate-900 p-5 text-white shadow-lg shadow-emerald-200/40">
        <div class="flex items-center gap-4">
            <div class="relative">
                @if($teacher->photo_path)
                    <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="{{ $teacher->name }}"
                        class="h-20 w-20 rounded-3xl object-cover ring-2 ring-white/30" />
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/20 text-3xl font-bold backdrop-blur ring-2 ring-white/30">
                        {{ $initial }}
                    </div>
                @endif
                <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400 ring-2 ring-emerald-700">
                    <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <div class="min-w-0">
                <h2 class="truncate text-xl font-bold">{{ $teacher->name }}</h2>
                @if($teacher->nip)
                    <p class="mt-1 text-sm text-emerald-100">NIP {{ $teacher->nip }}</p>
                @endif
                @if($teacher->subject)
                    <p class="mt-1 inline-flex items-center gap-1 rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-medium backdrop-blur">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        {{ $teacher->subject }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    {{-- Info cards --}}
    <section class="mt-4 space-y-3">
        @foreach ([
            [
                'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'label' => 'Email',
                'value' => $teacher->email ?: '-',
            ],
            [
                'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
                'label' => 'Telepon',
                'value' => $teacher->phone ?: '-',
            ],
            [
                'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'label' => 'Tanggal Lahir',
                'value' => $teacher->date_of_birth ? $teacher->date_of_birth->translatedFormat('d F Y') : '-',
            ],
            [
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'label' => 'Status',
                'value' => $teacher->status ? ucfirst($teacher->status) : '-',
            ],
        ] as $info)
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $info['label'] }}</p>
                    <p class="mt-0.5 truncate text-sm font-semibold text-slate-900">{{ $info['value'] }}</p>
                </div>
            </div>
        @endforeach
    </section>
@endsection
