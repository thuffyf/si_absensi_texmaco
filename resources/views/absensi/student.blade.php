@extends('layouts.app')

@section('title', 'Absen — SITEXA Absensi')
@section('page_title', 'Absen')
@section('page_subtitle', 'Absensi harian siswa SITEXA')

@section('content')
<div class="mx-auto max-w-6xl space-y-8">
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3 md:gap-6">
        @foreach($dayCards as $day)
            <article class="flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <h2 class="border-b border-slate-100 pb-3 text-xl font-bold text-slate-900">{{ $day['name'] }}</h2>
                <dl class="mt-4 flex flex-1 flex-col gap-3 text-sm">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $day['date'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</dt>
                        @if($day['attendance'])
                            <dd class="mt-0.5 text-lg font-bold tabular-nums {{ 
                                $day['attendance']->status === 'hadir' ? 'text-emerald-900' : 
                                ($day['attendance']->status === 'izin' ? 'text-amber-900' : 
                                ($day['attendance']->status === 'sakit' ? 'text-red-900' : 'text-red-900')) 
                            }} uppercase">{{ $day['attendance']->status }}</dd>
                            <p class="mt-1 text-xs font-medium text-slate-500">{{ $day['attendance']->attendance_time }}</p>
                        @else
                            <dd class="mt-0.5 text-lg font-bold tabular-nums text-slate-900">Belum</dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ruang Kelas</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $day['room'] }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    @if($day['is_today'])
                        <div class="flex gap-2">
                            <button onclick="showTapInMessage()" class="flex-1 rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700">
                                Masuk
                            </button>
                            <button onclick="openModal('sakit')" class="rounded-2xl bg-red-100 px-3 py-3 text-red-700 shadow-sm transition-colors hover:bg-red-200" title="Sakit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            <button onclick="openModal('izin')" class="rounded-2xl bg-amber-100 px-3 py-3 text-amber-700 shadow-sm transition-colors hover:bg-amber-200" title="Izin">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <span class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-center text-sm font-medium text-slate-500">
                            {{ $day['is_past'] ? 'Selesai' : 'Akan datang' }}
                        </span>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</div>

<!-- Tap-in Message Modal -->
<div id="tapin-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTapInModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl p-6">
            <button onclick="closeTapInModal()" class="absolute right-4 top-4 text-slate-400 hover:text-slate-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-sky-100">
                    <svg class="h-8 w-8 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Tap-in NFC</h3>
                <p class="mt-2 text-sm text-slate-600">Silahkan lakukan tap in pada SITEXA SCAN</p>
            </div>
        </div>
    </div>
</div>

<!-- Izin/Sakit Modal -->
<div id="izin-sakit-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900" id="modal-title">Request Izin/Sakit</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="start_date" value="{{ $now->toDateString() }}">
                    <input type="hidden" name="end_date" value="{{ $now->toDateString() }}">
                    <input type="hidden" name="type" id="modal-type" value="">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Alasan</label>
                        <textarea name="reason" rows="3" required class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Jelaskan alasan izin atau sakit Anda..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Bukti (Surat Dokter/Surat Izin)</label>
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        <p class="mt-1 text-xs text-slate-500">Format: JPEG, PNG, JPG. Maksimal 2MB.</p>
                    </div>
                    
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeModal()" class="flex-1 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showTapInMessage() {
    document.getElementById('tapin-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTapInModal() {
    document.getElementById('tapin-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openModal(type) {
    document.getElementById('modal-type').value = type;
    document.getElementById('modal-title').textContent = type === 'sakit' ? 'Request Sakit' : 'Request Izin';
    document.getElementById('izin-sakit-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('izin-sakit-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
