@extends('portal.layouts.app')

@section('title', 'Profil Siswa - SITEXA')
@section('page_title', 'Profil')
@section('page_subtitle', 'Data pribadi')

@section('content')
    @include('portal.partials.student-status')

    @php
        $initial  = strtoupper(substr(trim($student->name ?: 'S'), 0, 1));
        $photoUrl = portalStorageUrl($student->photo_path);
    @endphp

    {{-- ===== Hero / Avatar ===== --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-500 to-sky-700 p-6 text-white">
        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/5"></div>
        
        <div class="relative flex flex-col items-center text-center">
            {{-- Avatar dengan tombol ganti foto --}}
            <div class="relative">
                @if($photoUrl)
                    <img id="avatar-preview" src="{{ $photoUrl }}" alt="{{ $student->name }}"
                        class="h-24 w-24 rounded-full object-cover shadow-lg ring-4 ring-white/30" />
                    <div id="avatar-preview-initial" class="hidden flex h-24 w-24 items-center justify-center rounded-full bg-white/20 text-3xl font-bold shadow-lg backdrop-blur ring-4 ring-white/20">
                        {{ $initial }}
                    </div>
                @else
                    <div id="avatar-preview-initial" class="flex h-24 w-24 items-center justify-center rounded-full bg-white/20 text-3xl font-bold shadow-lg backdrop-blur ring-4 ring-white/20">
                        {{ $initial }}
                    </div>
                    <img id="avatar-preview" src="" alt="" class="hidden h-24 w-24 rounded-full object-cover shadow-lg ring-4 ring-white/30" />
                @endif

                <div class="absolute bottom-0 right-0 flex gap-2">
                    <button type="button" id="change-photo-btn" class="rounded-full bg-sky-500 p-2 text-white shadow-sm transition hover:bg-sky-600" title="Ubah Foto">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                    @if($photoUrl)
                        <button type="button" id="delete-photo-btn" class="rounded-full bg-rose-500 p-2 text-white shadow-sm transition hover:bg-rose-600" title="Hapus Foto">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    @endif
                </div>

                {{-- Badge aktif --}}
                <span class="absolute bottom-0 left-0 flex h-7 w-7 items-center justify-center rounded-full bg-emerald-400 shadow-lg ring-4 ring-sky-600">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>

            <h2 class="mt-4 text-xl font-bold">{{ $student->name }}</h2>
            <p class="mt-1 flex items-center gap-2 text-sm text-sky-100">
                <span>NIS {{ $student->nis }}</span>
                <span class="h-1 w-1 rounded-full bg-sky-200"></span>
                <span>{{ $student->class_name }}</span>
            </p>
            @if($student->major)
                <p class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-medium backdrop-blur">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ $student->major }}
                </p>
            @endif
        </div>
    </div>

    {{-- Form upload foto (tersembunyi, submit otomatis saat file dipilih) --}}
    <form id="photo-form" action="{{ route('portal.student.profile.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input id="photo-input" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="sr-only" />
    </form>

    {{-- Preview bar (muncul saat foto dipilih, sebelum submit) --}}
    <div id="photo-preview-bar" class="hidden mt-4 flex items-center gap-3 rounded-2xl bg-sky-50 px-4 py-3 shadow-sm">
        <svg class="h-5 w-5 shrink-0 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
        </svg>
        <p class="flex-1 text-sm font-medium text-sky-800">Foto baru siap diunggah</p>
        <button type="button" id="photo-save-btn" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 active:scale-95">
            Simpan
        </button>
        <button type="button" id="photo-cancel-btn" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-95">
            Batal
        </button>
    </div>

    {{-- ===== Data Pribadi ===== --}}
    <div class="mt-4 space-y-2">
        @foreach ([
            ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Email', 'value' => $student->email ?: '-', 'break' => true],
            ['icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0', 'label' => 'NIS', 'value' => $student->nis ?: '-', 'break' => false],
            ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => 'Telepon', 'value' => $student->phone ?: '-', 'break' => false],
            ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Tanggal Lahir', 'value' => $student->date_of_birth ? portalFormatDate($student->date_of_birth, 'd F Y') : '-', 'break' => false],
        ] as $info)
            <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                    </svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-slate-500">{{ $info['label'] }}</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 {{ $info['break'] ? 'break-all' : '' }}">{{ $info['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== UID Kartu ===== --}}
    @if($student->uid_kartu)
        <div class="mt-4 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 p-5 shadow-lg">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                        <svg class="h-4 w-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-white">UID Kartu NFC</h3>
                        <p class="text-xs text-slate-400">Identitas absensi digital</p>
                    </div>
                </div>
                <button type="button" id="copy-uid" data-uid="{{ $student->uid_kartu }}"
                    class="flex items-center gap-1.5 rounded-lg bg-sky-500 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-sky-600 active:scale-95">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span id="copy-uid-label">Salin</span>
                </button>
            </div>
            <div class="relative overflow-hidden rounded-xl bg-white/5 px-4 py-4 backdrop-blur">
                <p class="break-all text-center font-mono text-base font-semibold tracking-wider text-sky-100">{{ $student->uid_kartu }}</p>
            </div>
            <p class="mt-3 text-center text-xs text-slate-400">Tunjukkan kode ini ke admin jika diperlukan</p>
        </div>
    @endif

    {{-- ===== Ganti Password ===== --}}
    <div class="mt-4 overflow-hidden rounded-2xl bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center gap-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
            <div>
                <h3 class="text-base font-bold text-slate-900">Ganti Password</h3>
                <p class="text-xs text-slate-500">Perbarui password login Anda</p>
            </div>
        </div>

        <form action="{{ route('portal.student.profile.password') }}" method="POST" class="space-y-4">
            @csrf

            @if($errors->has('new_password'))
                <div class="flex items-start gap-2 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first('new_password') }}</span>
                </div>
            @endif

            <div>
                <label for="new_password" class="mb-2 block text-sm font-medium text-slate-700">Password Baru</label>
                <div class="relative">
                    <input id="new_password" name="new_password" type="password" required
                        placeholder="Minimal 8 karakter"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 pr-12 text-slate-900 placeholder-slate-400 transition focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-100" 
                        autocomplete="new-password" />
                    <button type="button" data-toggle-pw="new_password"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded p-1 text-slate-400 transition hover:text-slate-600 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="new_password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                <div class="relative">
                    <input id="new_password_confirmation" name="new_password_confirmation" type="password" required
                        placeholder="Ulangi password baru"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 pr-12 text-slate-900 placeholder-slate-400 transition focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-100" 
                        autocomplete="new-password" />
                    <button type="button" data-toggle-pw="new_password_confirmation"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded p-1 text-slate-400 transition hover:text-slate-600 focus:outline-none">
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
                            <div class="h-1.5 flex-1 rounded-full bg-slate-200" data-strength-bar="{{ $i }}"></div>
                        @endfor
                    </div>
                    <span id="pw-strength-label" class="text-xs font-semibold text-slate-400"></span>
                </div>
            </div>

            <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-3 font-semibold text-white shadow-md transition hover:shadow-lg active:scale-[0.98]">
                Perbarui Password
            </button>
        </form>
    </div>

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
    const changeBtn    = document.getElementById('change-photo-btn');
    const saveBtn      = document.getElementById('photo-save-btn');
    const cancelBtn    = document.getElementById('photo-cancel-btn');
    const deleteBtn    = document.getElementById('photo-delete-btn');
    let originalSrc    = previewImg ? previewImg.src : '';

    changeBtn?.addEventListener('click', function () {
        photoInput?.click();
    });

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

    deleteBtn?.addEventListener('click', function () {
        if (confirm('Hapus foto profil sekarang?')) {
            fetch('{{ route('portal.student.profile.photo.delete') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(async function (response) {
                if (!response.ok) {
                    throw new Error('Request gagal');
                }

                return await response.json();
            })
            .then(function (data) {
                if (data && data.success) {
                    window.location.reload();
                    return;
                }

                alert('Gagal menghapus foto profil.');
            })
            .catch(function (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus foto.');
            });
        }
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
