@extends('portal.layouts.app')

@section('title', 'Izin dan Sakit - SITEXA')
@section('page_title', 'Izin & Sakit')
@section('page_subtitle', 'Pengajuan dikirim langsung ke TU')

@section('content')
    @include('portal.partials.student-status')

    @php
        $selectedType = old('type', 'izin');
    @endphp

    <section class="rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
        <div class="mb-4 flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </span>
            <div>
                <h2 class="text-base font-bold text-slate-900">Buat Pengajuan Baru</h2>
                <p class="text-xs text-slate-500">Isi formulir di bawah ini</p>
            </div>
        </div>

        <form action="{{ route('portal.student.leave.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="leave-form">
            @csrf

            {{-- Type pills --}}
            <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Jenis Pengajuan</p>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="izin" class="peer sr-only" @checked($selectedType === 'izin') />
                        <span class="portal-type-pill flex flex-col items-center gap-1 rounded-2xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-center transition peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-800">
                            <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-sm font-semibold">Izin</span>
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="sakit" class="peer sr-only" @checked($selectedType === 'sakit') />
                        <span class="portal-type-pill flex flex-col items-center gap-1 rounded-2xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-center transition peer-checked:border-rose-400 peer-checked:bg-rose-50 peer-checked:text-rose-800">
                            <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span class="text-sm font-semibold">Sakit</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <label for="start_date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Mulai</label>
                    <input
                        id="start_date"
                        name="start_date"
                        type="date"
                        required
                        value="{{ old('start_date', $today) }}"
                        class="input-field"
                    />
                </div>
                <div>
                    <label for="end_date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Selesai</label>
                    <input
                        id="end_date"
                        name="end_date"
                        type="date"
                        value="{{ old('end_date', $today) }}"
                        class="input-field"
                    />
                </div>
            </div>

            <div>
                <label for="reason" class="mb-2 block text-sm font-semibold text-slate-700">Alasan</label>
                <textarea
                    id="reason"
                    name="reason"
                    rows="4"
                    required
                    maxlength="500"
                    placeholder="Jelaskan alasan izin atau sakit..."
                    class="input-field resize-none"
                >{{ old('reason') }}</textarea>
                <p class="mt-1 text-right text-xs text-slate-400"><span id="reason-count">0</span>/500</p>
            </div>

            {{-- File upload with preview --}}
            <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Bukti <span class="font-normal text-slate-400">(opsional)</span></p>
                <label
                    for="photo"
                    id="photo-dropzone"
                    class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-6 transition hover:border-sky-300 hover:bg-sky-50/50"
                >
                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="mt-2 text-sm font-medium text-slate-600">Ketuk untuk unggah foto</p>
                    <p class="mt-1 text-xs text-slate-400">JPG, PNG, WEBP · maks. 5MB</p>
                </label>
                <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="sr-only" />
                <div id="photo-preview-wrap" class="mt-3 hidden">
                    <div class="relative overflow-hidden rounded-2xl">
                        <img id="photo-preview" src="" alt="Pratinjau bukti" class="h-40 w-full object-cover" />
                        <button type="button" id="photo-remove" class="absolute right-2 top-2 rounded-full bg-slate-900/70 p-1.5 text-white backdrop-blur transition hover:bg-slate-900">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-sm">
                Kirim ke TU
            </button>
        </form>
    </section>

    {{-- Request history --}}
    <section class="mt-4 rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </span>
            <div>
                <h2 class="text-base font-bold text-slate-900">Riwayat Pengajuan</h2>
                <p class="text-xs text-slate-500">{{ $requests->count() }} pengajuan terbaru</p>
            </div>
        </div>

        <div class="mt-4 space-y-3">
            @forelse ($requests as $leaveRequest)
                <article class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                    <div class="flex items-start gap-3 p-3">
                        <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $leaveRequest->type === 'sakit' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600' }}">
                            @if ($leaveRequest->type === 'sakit')
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-start justify-between gap-x-2 gap-y-1">
                                <h3 class="font-semibold capitalize text-slate-900">{{ $leaveRequest->type }}</h3>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-semibold ring-1 {{ portalRequestBadge($leaveRequest->status) }}">
                                    {{ portalRequestLabel($leaveRequest->status) }}
                                </span>
                            </div>
                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ portalFormatDate($leaveRequest->start_date, 'd M Y') }}
                                @if ($leaveRequest->end_date && $leaveRequest->end_date->ne($leaveRequest->start_date))
                                    – {{ portalFormatDate($leaveRequest->end_date, 'd M Y') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-slate-50 px-3 pb-3">
                        <p class="pt-2 text-sm text-slate-700 leading-relaxed">{{ $leaveRequest->reason }}</p>

                        @if ($leaveRequest->photo)
                            <img
                                src="{{ asset('storage/' . $leaveRequest->photo) }}"
                                alt="Bukti {{ $leaveRequest->type }}"
                                class="mt-3 h-36 w-full rounded-2xl object-cover"
                            />
                        @endif

                        @if ($leaveRequest->response_note)
                            <div class="mt-3 flex items-start gap-2 rounded-2xl bg-emerald-50 px-3 py-2.5 text-sm text-emerald-700">
                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                <span>{{ $leaveRequest->response_note }}</span>
                            </div>
                        @endif

                        @if ($leaveRequest->rejection_reason)
                            <div class="mt-2 flex items-start gap-2 rounded-2xl bg-rose-50 px-3 py-2.5 text-sm text-rose-700">
                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>{{ preg_replace('/^admin konfirmasi:\s*/i', '', $leaveRequest->rejection_reason) }}</span>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                @include('portal.partials.student-empty-state', [
                    'icon' => 'document',
                    'title' => 'Belum ada pengajuan',
                    'description' => 'Pengajuan izin atau sakit akan tampil di sini.',
                ])
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const reason = document.getElementById('reason');
            const reasonCount = document.getElementById('reason-count');
            const photoInput = document.getElementById('photo');
            const photoDropzone = document.getElementById('photo-dropzone');
            const photoPreviewWrap = document.getElementById('photo-preview-wrap');
            const photoPreview = document.getElementById('photo-preview');
            const photoRemove = document.getElementById('photo-remove');

            function updateReasonCount() {
                if (reason && reasonCount) {
                    reasonCount.textContent = (reason.value || '').length;
                }
            }

            reason?.addEventListener('input', updateReasonCount);
            updateReasonCount();

            photoInput?.addEventListener('change', function () {
                const file = photoInput.files?.[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (event) {
                    photoPreview.src = event.target.result;
                    photoPreviewWrap.classList.remove('hidden');
                    photoDropzone.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });

            photoRemove?.addEventListener('click', function () {
                photoInput.value = '';
                photoPreview.src = '';
                photoPreviewWrap.classList.add('hidden');
                photoDropzone.classList.remove('hidden');
            });
        })();
    </script>
@endpush
