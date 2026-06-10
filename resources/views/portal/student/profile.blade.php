@extends('portal.layouts.app')

@section('title', 'Profil Siswa - SITEXA')
@section('page_title', 'Profil')
@section('page_subtitle', 'Data pribadi dan kartu absensi')

@section('content')
    @include('portal.partials.student-status')

    @php
        $initial = strtoupper(substr(trim($student->name ?: 'S'), 0, 1));
    @endphp

    {{-- Profile hero --}}
    <section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-sky-600 via-sky-700 to-slate-900 p-5 text-white shadow-lg shadow-sky-200/40">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/20 text-3xl font-bold backdrop-blur ring-2 ring-white/30">
                    {{ $initial }}
                </div>
                <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400 ring-2 ring-sky-700">
                    <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            <div class="min-w-0">
                <h2 class="truncate text-xl font-bold">{{ $student->name }}</h2>
                <p class="mt-1 text-sm text-sky-100">NIS {{ $student->nis }}</p>
                <p class="mt-1 inline-flex items-center gap-1 rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-medium backdrop-blur">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ $student->class_name }} {{ $student->major }}
                </p>
            </div>
        </div>
    </section>

    {{-- Info cards --}}
    <section class="mt-4 space-y-3">
        @foreach ([
            ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Email', 'value' => $student->email ?: '-'],
            ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => 'Telepon', 'value' => $student->phone ?: '-'],
            ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Tanggal Lahir', 'value' => $student->date_of_birth ? portalFormatDate($student->date_of_birth, 'd F Y') : '-'],
        ] as $info)
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/></svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $info['label'] }}</p>
                    <p class="mt-0.5 truncate text-sm font-semibold text-slate-900">{{ $info['value'] }}</p>
                </div>
            </div>
        @endforeach
    </section>

    {{-- UID card --}}
    <section class="mt-4 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-3">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-sky-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">UID Kartu/Stiker</h2>
                        <p class="text-xs text-slate-500">Identitas NFC absensi</p>
                    </div>
                </div>
                <button
                    type="button"
                    id="copy-uid"
                    data-uid="{{ $student->uid_kartu }}"
                    class="portal-copy-btn flex items-center gap-1.5 rounded-full bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700 transition hover:bg-sky-200 active:scale-95"
                    @disabled(empty($student->uid_kartu))
                >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span id="copy-uid-label">Salin</span>
                </button>
            </div>
        </div>

        <div class="p-4">
            @if ($student->uid_kartu)
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-4 py-5">
                    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.15),_transparent_50%)]"></div>
                    <p class="relative text-center font-mono text-sm font-semibold tracking-[0.25em] text-sky-100">{{ $student->uid_kartu }}</p>
                </div>
                <p class="mt-3 text-center text-xs text-slate-500">Berikan UID ini jika diminta admin sekolah</p>
            @else
                @include('portal.partials.student-empty-state', [
                    'icon' => 'inbox',
                    'title' => 'UID belum diatur',
                    'description' => 'Hubungi admin sekolah untuk mengatur kartu absensi Anda.',
                ])
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const button = document.getElementById('copy-uid');
            const label = document.getElementById('copy-uid-label');
            if (!button || !label) return;

            button.addEventListener('click', async function () {
                const uid = button.getAttribute('data-uid') || '';
                if (!uid) return;

                try {
                    await navigator.clipboard.writeText(uid);
                    label.textContent = 'Tersalin!';
                    button.classList.remove('bg-sky-100', 'text-sky-700');
                    button.classList.add('bg-emerald-100', 'text-emerald-700');
                    window.setTimeout(function () {
                        label.textContent = 'Salin';
                        button.classList.add('bg-sky-100', 'text-sky-700');
                        button.classList.remove('bg-emerald-100', 'text-emerald-700');
                    }, 2000);
                } catch (error) {
                    label.textContent = 'Gagal';
                    window.setTimeout(function () {
                        label.textContent = 'Salin';
                    }, 2000);
                }
            });
        })();
    </script>
@endpush
