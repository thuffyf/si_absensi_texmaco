@extends('portal.layouts.app')

@section('title', 'Absensi - SITEXA')
@section('page_title', 'Absensi')
@section('page_subtitle', 'Kelas ' . $student->class_name)

@section('content')
    @include('portal.partials.student-status')

    {{-- Day Cards Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5">
        @foreach($dayCards as $day)
            @php
                $att         = $day['attendance'];
                $leaveReq    = $day['leave_request'];
                $isToday     = $day['is_today'];
                $isPast      = $day['is_past'];
                $isFuture    = $day['is_future'];
                $hasAttended = $att && $att->attendance_time && $att->attendance_time !== '00:00:00' && $att->status === 'hadir';
                $hasAbsence  = $att && in_array($att->status ?? '', ['izin','sakit','alpa','alpha']);
                $hasLeave    = $leaveReq !== null;

                $cardBorder = $isToday
                    ? 'border-sky-500 bg-sky-50/10 shadow-md ring-1 ring-sky-500 scale-[1.01] z-10'
                    : ($hasAttended
                        ? 'border-emerald-200 bg-emerald-50/30'
                        : (($hasAbsence || $hasLeave) ? 'border-amber-200 bg-amber-50/30' : 'border-slate-100 shadow-sm'));

                $statusBadgeCls = match(true) {
                    $hasAttended                                             => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    $hasAbsence && ($att->status ?? '') === 'izin'          => 'bg-amber-100 text-amber-700 border-amber-200',
                    $hasAbsence && ($att->status ?? '') === 'sakit'         => 'bg-rose-100 text-rose-700 border-rose-200',
                    $hasAbsence                                              => 'bg-red-100 text-red-700 border-red-200',
                    $hasLeave                                                => 'bg-amber-100 text-amber-700 border-amber-200',
                    $isToday                                                 => 'bg-sky-500 text-white border-sky-500 shadow-sm',
                    $isPast                                                  => 'bg-slate-100 text-slate-500 border-slate-200',
                    default                                                  => 'bg-slate-50 text-slate-400 border-slate-100',
                };

                $statusLabel = match(true) {
                    $hasAttended                                             => 'Hadir',
                    $hasAbsence && ($att->status ?? '') === 'izin'          => 'Izin',
                    $hasAbsence && ($att->status ?? '') === 'sakit'         => 'Sakit',
                    $hasAbsence                                              => 'Alpa',
                    $hasLeave && $leaveReq->status === 'approved'           => ucfirst($leaveReq->type) . ' ✓',
                    $hasLeave                                                => ucfirst($leaveReq->type) . '…',
                    $isToday                                                 => 'Hari ini',
                    $isPast                                                  => 'Tidak ada',
                    default                                                  => 'Akan datang',
                };
            @endphp

            <article class="relative flex flex-col rounded-2xl border bg-white p-4 transition-all duration-300 {{ $cardBorder }}">
                
                {{-- Header --}}
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 leading-none">{{ $day['name'] }}</h3>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $day['date'] }}</p>
                    </div>
                    <span class="shrink-0 rounded-full border px-3 py-1 text-[10px] font-bold tracking-wide uppercase {{ $statusBadgeCls }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Schedule Info --}}
                <div class="mt-4 flex flex-1 flex-col gap-2">
                    @if($day['schedules']->isEmpty())
                        <div class="flex flex-1 items-center justify-center rounded-xl bg-slate-50/50 p-6 text-center border border-slate-100 border-dashed">
                            <p class="text-xs font-medium text-slate-400">Tidak ada jadwal</p>
                        </div>
                    @else
                        @foreach($day['schedules'] as $schedule)
                            <div class="rounded-xl bg-slate-50/80 p-3 border border-slate-100/60 transition hover:bg-slate-50">
                                <div class="mb-1.5 flex items-start justify-between gap-2">
                                    <p class="text-sm font-semibold text-slate-800 leading-tight">{{ $schedule->subject }}</p>
                                    <p class="shrink-0 text-xs font-bold text-slate-600 bg-white px-2 py-0.5 rounded shadow-sm border border-slate-100">
                                        {{ $schedule->start_time?->format('H:i') ?? '-' }}
                                        @if($schedule->end_time)
                                            – {{ $schedule->end_time->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <p class="text-xs font-medium text-slate-500">{{ $schedule->teacher?->name ?? 'Belum diatur' }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 pt-1">
                    @if($hasAttended)
                        <div class="flex items-center justify-center gap-2 rounded-xl bg-emerald-50 py-2.5 text-sm font-semibold text-emerald-700 border border-emerald-100">
                            Sudah hadir · {{ substr($att->attendance_time, 0, 5) }}
                        </div>

                    @elseif($hasAbsence || $hasLeave)
                        <div class="flex items-center justify-center gap-2 rounded-xl bg-amber-50 py-2.5 text-sm font-semibold text-amber-700 border border-amber-100">
                            {{ $hasLeave ? ucfirst($leaveReq->type) . ' diproses' : ucfirst($att->status ?? 'Tidak hadir') }}
                        </div>

                    @elseif($isFuture)
                        <div class="flex items-center justify-center rounded-xl bg-slate-50 py-2.5 text-sm font-semibold text-slate-400 border border-slate-100">
                            Belum tersedia
                        </div>

                    @else
                        <div class="flex gap-2">
                            {{-- Button Hadir (lebar) --}}
                            <button
                                type="button"
                                onclick="handleHadir('{{ $day['date_raw'] }}', {{ $hasAttended ? 'true' : 'false' }})"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-sky-700 active:scale-[0.98]"
                            >
                                Hadir
                            </button>

                            {{-- Button S/I (kecil) --}}
                            <button
                                type="button"
                                onclick="openSiModal('{{ $day['date_raw'] }}')"
                                class="flex shrink-0 items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 border border-slate-200 shadow-sm transition-all hover:bg-slate-50 hover:border-slate-300 active:scale-[0.98]"
                                title="Ajukan Izin atau Sakit"
                            >
                                S/I
                            </button>
                        </div>
                    @endif
                </div>

            </article>
        @endforeach
    </div>

    {{-- MODAL: Hadir (NFC) --}}
    <div id="hadir-modal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-[9998] bg-black/40 backdrop-blur-sm" onclick="closeHadirModal()"></div>
        <div class="fixed inset-0 z-[10000] flex items-center justify-center p-4">
            <div class="relative w-full max-w-sm rounded-[2rem] bg-white shadow-2xl">

                {{-- Belum tap-in --}}
                <div id="nfc-prompt-panel" class="p-6 text-center sm:p-8">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-sky-100 shadow-sm">
                        <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Tap Kartu NFC</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Silakan lakukan <strong class="font-semibold text-slate-700">tap-in</strong> pada alat
                        <strong class="font-semibold text-sky-600">SITEXA SCAN</strong> terlebih dahulu.
                    </p>
                    <button onclick="closeHadirModal()" class="mt-6 w-full rounded-xl bg-slate-100 py-3 text-sm font-bold text-slate-700 transition-all hover:bg-slate-200 active:scale-[0.98]">
                        Mengerti
                    </button>
                </div>

                {{-- Sudah tap-in --}}
                <div id="already-attended-panel" class="hidden p-6 text-center sm:p-8">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 shadow-sm">
                        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Sudah Hadir!</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Anda sudah tercatat hadir hari ini. Tidak perlu tap-in lagi.
                    </p>
                    <button onclick="closeHadirModal()" class="mt-6 w-full rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white transition-all hover:bg-emerald-600 active:scale-[0.98]">
                        Oke, Terima Kasih!
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL: S/I — Izin / Sakit --}}
    <div id="si-modal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-[9998] bg-black/50 backdrop-blur-sm" onclick="closeSiModal()"></div>
        <div class="fixed inset-0 z-[10000] flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-3xl bg-white shadow-2xl">
                <div class="p-6">
                    <div class="mb-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Ajukan Izin / Sakit</h3>
                                <p class="text-xs text-slate-500" id="si-modal-date-label">—</p>
                            </div>
                        </div>
                        <button onclick="closeSiModal()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('portal.student.absensi.leave.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="si-form">
                        @csrf
                        <input type="hidden" name="start_date" id="si-start-date" value="">
                        <input type="hidden" name="end_date"   id="si-end-date"   value="">

                        {{-- Jenis --}}
                        <div>
                            <p class="mb-2 text-sm font-semibold text-slate-700">Jenis</p>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="izin" class="peer sr-only" checked />
                                    <span class="flex flex-col items-center gap-1 rounded-2xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-center transition peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-800">
                                        <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-semibold">Izin</span>
                                    </span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="sakit" class="peer sr-only" />
                                    <span class="flex flex-col items-center gap-1 rounded-2xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-center transition peer-checked:border-rose-400 peer-checked:bg-rose-50 peer-checked:text-rose-800">
                                        <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span class="text-sm font-semibold">Sakit</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Alasan --}}
                        <div>
                            <label for="si-reason" class="mb-1.5 block text-sm font-semibold text-slate-700">Alasan</label>
                            <textarea
                                id="si-reason"
                                name="reason"
                                rows="3"
                                required
                                maxlength="500"
                                placeholder="Jelaskan alasan Anda..."
                                class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 transition focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-100"
                            ></textarea>
                        </div>

                        {{-- Bukti --}}
                        <div>
                            <p class="mb-1.5 text-sm font-semibold text-slate-700">Bukti <span class="font-normal text-slate-400">(opsional)</span></p>
                            <label for="si-photo" id="si-dropzone"
                                class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-5 transition hover:border-sky-300 hover:bg-sky-50/50">
                                <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-1.5 text-xs font-medium text-slate-500">Ketuk untuk unggah bukti</p>
                                <p class="text-[10px] text-slate-400">JPG, PNG · maks 5MB</p>
                            </label>
                            <input id="si-photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="sr-only" />
                            <div id="si-photo-preview-wrap" class="mt-2 hidden">
                                <div class="relative overflow-hidden rounded-2xl">
                                    <img id="si-photo-preview" src="" alt="Pratinjau" class="h-32 w-full object-cover" />
                                    <button type="button" id="si-photo-remove"
                                        class="absolute right-2 top-2 rounded-full bg-slate-900/70 p-1 text-white backdrop-blur hover:bg-slate-900">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-1">
                            <button type="button" onclick="closeSiModal()"
                                class="flex-1 rounded-2xl border border-slate-200 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 rounded-2xl bg-sky-600 py-3 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
                                Kirim ke TU
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    /* ── Hadir Modal ─────────────────────────────── */
    window.handleHadir = function (date, alreadyAttended) {
        document.getElementById('nfc-prompt-panel').classList.toggle('hidden', alreadyAttended);
        document.getElementById('already-attended-panel').classList.toggle('hidden', !alreadyAttended);
        document.getElementById('hadir-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeHadirModal = function () {
        document.getElementById('hadir-modal').classList.add('hidden');
        document.body.style.overflow = '';
    };

    /* ── S/I Modal ───────────────────────────────── */
    window.openSiModal = function (dateRaw) {
        document.getElementById('si-start-date').value = dateRaw;
        document.getElementById('si-end-date').value   = dateRaw;

        var d = new Date(dateRaw + 'T00:00:00');
        document.getElementById('si-modal-date-label').textContent =
            d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

        document.getElementById('si-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeSiModal = function () {
        document.getElementById('si-modal').classList.add('hidden');
        document.body.style.overflow = '';
    };

    /* ── File Preview ────────────────────────────── */
    var photoInput       = document.getElementById('si-photo');
    var photoDropzone    = document.getElementById('si-dropzone');
    var photoPreviewWrap = document.getElementById('si-photo-preview-wrap');
    var photoPreview     = document.getElementById('si-photo-preview');
    var photoRemove      = document.getElementById('si-photo-remove');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            var file = photoInput.files && photoInput.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
                photoPreviewWrap.classList.remove('hidden');
                photoDropzone.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    }

    if (photoRemove) {
        photoRemove.addEventListener('click', function () {
            photoInput.value = '';
            photoPreview.src = '';
            photoPreviewWrap.classList.add('hidden');
            photoDropzone.classList.remove('hidden');
        });
    }
})();
</script>
@endpush
