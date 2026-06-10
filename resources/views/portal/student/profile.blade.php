@extends('portal.layouts.app')

@section('title', 'Profil Siswa - SITEXA')
@section('page_title', 'Profil')
@section('page_subtitle', 'Data pribadi dan kartu absensi')

@section('content')
    @include('portal.partials.student-status')

    @php
        $initial  = strtoupper(substr(trim($student->name ?: 'S'), 0, 1));
        $photoUrl = $student->photo_path ? asset('storage/' . $student->photo_path) : null;
    @endphp

    {{-- ===== Hero / Avatar ===== --}}
    <section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-sky-600 via-sky-700 to-slate-900 p-5 text-white shadow-lg shadow-sky-200/40">
        <div class="flex items-center gap-4">

            {{-- Avatar dengan tombol ganti foto --}}
            <div class="relative shrink-0">
                <label for="photo-input" class="group cursor-pointer">
                    @if($photoUrl)
                        <img id="avatar-preview" src="{{ $photoUrl }}" alt="{{ $student->name }}"
                            class="h-20 w-20 rounded-3xl object-cover ring-2 ring-white/40" />
                    @else
                        <div id="avatar-preview-initial" class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/20 text-3xl font-bold backdrop-blur ring-2 ring-white/30">
                            {{ $initial }}
                        </div>
                        <img id="avatar-preview" src="" alt="" class="hidden h-20 w-20 rounded-3xl object-cover ring-2 ring-white/40" />
                    @endif
                    {{-- Overlay edit --}}
                    <div class="absolute inset-0 flex items-center justify-center rounded-3xl bg-black/0 transition group-hover:bg-black/40">
                        <svg class="h-6 w-6 text-white opacity-0 transition group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </label>

                {{-- Badge aktif --}}
                <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400 ring-2 ring-sky-700">
                    <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>

            <div class="min-w-0 flex-1">
                <h2 class="break-words text-xl font-bold leading-snug">{{ $student->name }}</h2>
                <p class="mt-1 text-sm text-sky-100">NIS {{ $student->nis }}</p>
                <p class="mt-1.5 inline-flex max-w-full items-center gap-1 rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-medium backdrop-blur">
                    <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="truncate">{{ $student->class_name }} {{ $student->major }}</span>
                </p>
                <p class="mt-2 text-xs text-sky-100/70">Ketuk foto untuk menggantinya</p>
            </div>
        </div>
    </section>

    {{-- Form upload foto (tersembunyi, submit otomatis saat file dipilih) --}}
    <form id="photo-form" action="{{ route('portal.student.profile.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input id="photo-input" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="sr-only" />
    </form>

    {{-- Preview bar (muncul saat foto dipilih, sebelum submit) --}}
    <div id="photo-preview-bar" class="hidden mt-3 flex items-center gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3">
        <svg class="h-5 w-5 shrink-0 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
        </svg>
        <p class="flex-1 text-sm font-medium text-sky-700">Foto baru siap diunggah</p>
        <button type="button" id="photo-save-btn" class="rounded-xl bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-sky-700 active:scale-95">
            Simpan
        </button>
        <button type="button" id="photo-cancel-btn" class="rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-200 active:scale-95">
            Batal
        </button>
    </div>

    {{-- ===== Data Pribadi ===== --}}
    <section class="mt-4 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-3">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <h2 class="text-sm font-bold text-slate-900">Data Pribadi</h2>
            </div>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach ([
                ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Email', 'value' => $student->email ?: '-', 'break' => true],
                ['icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0', 'label' => 'NIS', 'value' => $student->nis ?: '-', 'break' => false],
                ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => 'Telepon', 'value' => $student->phone ?: '-', 'break' => false],
                ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Tanggal Lahir', 'value' => $student->date_of_birth ? portalFormatDate($student->date_of_birth, 'd F Y') : '-', 'break' => false],
                ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Kelas', 'value' => ($student->class_name ?: '-') . ($student->major ? ' · ' . $student->major : ''), 'break' => false],
            ] as $info)
                <div class="flex items-center gap-3 px-4 py-3.5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">{{ $info['label'] }}</p>
                        <p class="mt-0.5 text-sm font-semibold text-slate-900 {{ $info['break'] ? 'break-all' : 'truncate' }}">{{ $info['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== UID Kartu ===== --}}
    <section class="mt-4 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-3">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-sky-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">UID Kartu / Stiker NFC</h2>
                        <p class="text-xs text-slate-500">Identitas absensi digital</p>
                    </div>
                </div>
                @if($student->uid_kartu)
                    <button type="button" id="copy-uid" data-uid="{{ $student->uid_kartu }}"
                        class="flex items-center gap-1.5 rounded-full bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700 transition hover:bg-sky-200 active:scale-95">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span id="copy-uid-label">Salin</span>
                    </button>
                @endif
            </div>
        </div>
        <div class="p-4">
            @if($student->uid_kartu)
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-4 py-5">
                    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.15),_transparent_50%)]"></div>
                    <p class="relative break-all text-center font-mono text-sm font-semibold tracking-[0.15em] text-sky-100">{{ $student->uid_kartu }}</p>
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

    {{-- ===== Ganti Password ===== --}}
    <section class="mt-4 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-3">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Ganti Password</h2>
                    <p class="text-xs text-slate-500">Buat password login yang kuat</p>
                </div>
            </div>
        </div>

        <form action="{{ route('portal.student.profile.password') }}" method="POST" class="space-y-4 p-4">
            @csrf

            @if($errors->has('new_password'))
                <div class="flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first('new_password') }}</span>
                </div>
            @endif

            <div>
                <label for="new_password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password Baru</label>
                <div class="relative">
                    <input id="new_password" name="new_password" type="password" required
                        placeholder="Minimal 8 karakter"
                        class="input-field pr-12" autocomplete="new-password" />
                    <button type="button" data-toggle-pw="new_password"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="new_password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                <div class="relative">
                    <input id="new_password_confirmation" name="new_password_confirmation" type="password" required
                        placeholder="Ulangi password baru"
                        class="input-field pr-12" autocomplete="new-password" />
                    <button type="button" data-toggle-pw="new_password_confirmation"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Indikator kekuatan password --}}
            <div id="pw-strength-wrap" class="hidden">
                <div class="flex items-center gap-2">
                    <div class="flex flex-1 gap-1">
                        @for($i = 0; $i < 4; $i++)
                            <div class="h-1 flex-1 rounded-full bg-slate-200" data-strength-bar="{{ $i }}"></div>
                        @endfor
                    </div>
                    <span id="pw-strength-label" class="text-xs font-semibold text-slate-400"></span>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3 text-sm">
                Perbarui Password
            </button>
        </form>
    </section>

@endsection

@push('scripts')
<script>
(function () {
    /* ---- Upload foto ---- */
    const photoInput   = document.getElementById('photo-input');
    const photoForm    = document.getElementById('photo-form');
    const previewBar   = document.getElementById('photo-preview-bar');
    const previewImg   = document.getElementById('avatar-preview');
    const previewInit  = document.getElementById('avatar-preview-initial');
    const saveBtn      = document.getElementById('photo-save-btn');
    const cancelBtn    = document.getElementById('photo-cancel-btn');
    let originalSrc    = previewImg ? previewImg.src : '';

    photoInput?.addEventListener('change', function () {
        const file = photoInput.files?.[0];
        if (!file) return;

        // Validasi ukuran di sisi client
        if (file.size > 3 * 1024 * 1024) {
            alert('Ukuran foto maksimal 3 MB.');
            photoInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
            }
            if (previewInit) previewInit.classList.add('hidden');
            previewBar?.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    saveBtn?.addEventListener('click', function () {
        photoForm?.submit();
    });

    cancelBtn?.addEventListener('click', function () {
        photoInput.value = '';
        if (previewImg) {
            if (originalSrc) {
                previewImg.src = originalSrc;
            } else {
                previewImg.classList.add('hidden');
                if (previewInit) previewInit.classList.remove('hidden');
            }
        }
        previewBar?.classList.add('hidden');
    });

    /* ---- Salin UID ---- */
    const copyBtn = document.getElementById('copy-uid');
    const copyLbl = document.getElementById('copy-uid-label');
    copyBtn?.addEventListener('click', async function () {
        const uid = copyBtn.getAttribute('data-uid') || '';
        if (!uid) return;
        try {
            await navigator.clipboard.writeText(uid);
            copyLbl.textContent = 'Tersalin!';
            copyBtn.classList.replace('bg-sky-100', 'bg-emerald-100');
            copyBtn.classList.replace('text-sky-700', 'text-emerald-700');
            setTimeout(function () {
                copyLbl.textContent = 'Salin';
                copyBtn.classList.replace('bg-emerald-100', 'bg-sky-100');
                copyBtn.classList.replace('text-emerald-700', 'text-sky-700');
            }, 2000);
        } catch {
            copyLbl.textContent = 'Gagal';
            setTimeout(() => { copyLbl.textContent = 'Salin'; }, 2000);
        }
    });

    /* ---- Toggle show/hide password ---- */
    document.querySelectorAll('[data-toggle-pw]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.getElementById(btn.getAttribute('data-toggle-pw'));
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    /* ---- Indikator kekuatan password ---- */
    const pwInput     = document.getElementById('new_password');
    const strengthWrap = document.getElementById('pw-strength-wrap');
    const strengthLbl  = document.getElementById('pw-strength-label');
    const bars         = document.querySelectorAll('[data-strength-bar]');

    const levels = [
        { min: 0,  label: '',        color: 'bg-slate-200' },
        { min: 1,  label: 'Lemah',   color: 'bg-rose-400'  },
        { min: 2,  label: 'Cukup',   color: 'bg-amber-400' },
        { min: 3,  label: 'Kuat',    color: 'bg-sky-500'   },
        { min: 4,  label: 'Sangat Kuat', color: 'bg-emerald-500' },
    ];

    function getStrength(pw) {
        let score = 0;
        if (pw.length >= 8)  score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return score;
    }

    pwInput?.addEventListener('input', function () {
        const val = pwInput.value;
        if (!val) { strengthWrap?.classList.add('hidden'); return; }
        strengthWrap?.classList.remove('hidden');

        const score  = getStrength(val);
        const level  = levels[score] || levels[0];
        bars.forEach(function (bar, i) {
            bar.className = 'h-1 flex-1 rounded-full ' + (i < score ? level.color : 'bg-slate-200');
        });
        if (strengthLbl) {
            strengthLbl.textContent = level.label;
            strengthLbl.className = 'text-xs font-semibold ' + (score <= 1 ? 'text-rose-500' : score === 2 ? 'text-amber-500' : score === 3 ? 'text-sky-600' : 'text-emerald-600');
        }
    });
})();
</script>
@endpush
