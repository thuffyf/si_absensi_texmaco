@extends('layouts.app')

@section('title', 'Dashboard — SITEXA Absensi')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'SITEXA Absensi Texmaco Purwasari - {{ $student->class_name }}')

@section('content')
<div class="mx-auto flex w-full max-w-none flex-col gap-3 max-lg:space-y-1 lg:h-full lg:min-h-0 lg:gap-3 lg:overflow-hidden">
    <div class="grid min-h-0 flex-1 grid-cols-1 gap-3 lg:grid-cols-12 lg:gap-4 lg:overflow-hidden">
        <!-- Main Content -->
        <section class="flex min-h-0 flex-col lg:col-span-9 lg:overflow-hidden">
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 lg:text-xl">Statistik Kehadiran - {{ $student->name }}</h2>
                        <p class="mt-1 text-xs text-slate-500 lg:text-sm">NIS: {{ $student->nis }} | {{ $student->class_name }}</p>
                    </div>
                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                            {{ $now->format('d M Y') }}
                        </span>
                        <span class="rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-800">Hari ini</span>
                    </div>
                </div>

                <div class="mt-3 grid shrink-0 grid-cols-2 gap-2 sm:grid-cols-4 lg:mt-4 lg:gap-3">
                    <div class="rounded-xl border border-slate-100 bg-gradient-to-br from-slate-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Total Hadir</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900 lg:text-3xl">{{ $totalPresent }}</p>
                    </div>
                    <div class="rounded-xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-sky-800 lg:text-xs">Kehadiran</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-sky-900 lg:text-3xl">{{ $attendanceRate }}%</p>
                        <p class="mt-0.5 text-xs text-sky-800/90 lg:text-sm">Rate kehadiran</p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800 lg:text-xs">Absen</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-900 lg:text-3xl">{{ $totalAbsent }}</p>
                        <p class="mt-0.5 text-xs text-emerald-800/90 lg:text-sm">Izin/Sakit/Alpha</p>
                    </div>
                    <div class="rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-3 lg:p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-800 lg:text-xs">Status Hari Ini</p>
                        @if($attendance && $attendance->attendance_time !== '00:00:00')
                            <p class="mt-1 text-2xl font-bold tabular-nums text-amber-900 lg:text-3xl uppercase">{{ $attendance->status }}</p>
                            <p class="mt-0.5 text-xs text-amber-800/90 lg:text-sm">{{ $attendance->attendance_time }}</p>
                        @else
                            <p class="mt-1 text-2xl font-bold tabular-nums text-amber-900 lg:text-3xl">Belum</p>
                            <p class="mt-0.5 text-xs text-amber-800/90 lg:text-sm">Silakan tap-in</p>
                        @endif
                    </div>
                </div>

                <div class="mt-3 min-h-0 flex-1 overflow-hidden rounded-xl border border-slate-100 bg-slate-50/40 p-2.5 lg:mt-4 lg:flex lg:items-stretch lg:justify-center lg:p-3">
                    <div class="h-full w-full overflow-y-auto space-y-2">
                        @foreach($weeklyAttendance as $day)
                            <div class="flex items-center justify-between rounded-xl border {{ $day['status'] ? 'border-slate-200 bg-white' : 'border-slate-100 bg-slate-50' }} p-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ 
                                        $day['status'] === 'hadir' ? 'bg-emerald-100 text-emerald-700' : 
                                        ($day['status'] === 'izin' ? 'bg-amber-100 text-amber-700' : 
                                        ($day['status'] === 'sakit' ? 'bg-red-100 text-red-700' : 
                                        ($day['status'] === 'alpha' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-500'))) 
                                    }} font-bold text-sm">
                                        {{ $day['status'] === 'hadir' ? '✓' : ($day['status'] ? '⚠' : '—') }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $day['day'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $day['date'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($day['status'])
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ 
                                            $day['status'] === 'hadir' ? 'bg-emerald-100 text-emerald-800' : 
                                            ($day['status'] === 'izin' ? 'bg-amber-100 text-amber-800' : 
                                            ($day['status'] === 'sakit' ? 'bg-red-100 text-red-800' : 'bg-red-100 text-red-800')) 
                                        }}">
                                            {{ ucfirst($day['status']) }}
                                        </span>
                                        <p class="mt-1 text-xs text-slate-500">{{ $day['time'] }}</p>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                                            Belum
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Sidebar -->
        <div class="flex min-h-0 flex-col gap-4 lg:col-span-3 lg:gap-4 lg:overflow-hidden">
            <!-- Today's Status -->
            <section class="shrink-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <h2 class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Status Hari Ini</h2>
                @if($attendance && $attendance->attendance_time !== '00:00:00')
                    <div class="mt-3 rounded-xl border {{ $attendance->status === 'hadir' ? 'border-emerald-200 bg-gradient-to-br from-emerald-50 to-white' : 'border-amber-200 bg-gradient-to-br from-amber-50 to-white' }} p-4">
                        <p class="text-3xl font-bold {{ $attendance->status === 'hadir' ? 'text-emerald-900' : 'text-amber-900' }} uppercase">{{ $attendance->status }}</p>
                        <p class="mt-1 text-sm {{ $attendance->status === 'hadir' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $attendance->attendance_time }}</p>
                    </div>
                @else
                    <div class="mt-3 rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-4">
                        <p class="text-3xl font-bold text-amber-900">Belum</p>
                        <p class="mt-1 text-sm text-amber-700">Silakan tap kartu NFC</p>
                    </div>
                @endif
            </section>

            <!-- Quick Actions -->
            <section class="shrink-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <h2 class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Aksi Cepat</h2>
                <div class="mt-3 space-y-2">
                    <a href="{{ route('absensi.student') }}" class="flex items-center gap-3 rounded-xl border border-sky-200 bg-gradient-to-br from-sky-50 to-white p-3 transition-colors hover:bg-sky-100">
                        <span class="text-sky-700">Absensi</span>
                    </a>
                    <button onclick="openModal()" class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-3 transition-colors hover:bg-emerald-100 w-full text-left">
                        <span class="text-emerald-700">Request Izin/Sakit</span>
                    </button>
                </div>
            </section>

            <!-- Notifications -->
            <section class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <h2 class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 lg:text-xs">Notifikasi</h2>
                <div class="mt-3 flex-1 overflow-y-auto space-y-2">
                    @if($rejectedRequests->count() > 0)
                        @foreach($rejectedRequests as $request)
                            <div class="rounded-xl border border-red-200 bg-gradient-to-br from-red-50 to-white p-3">
                                <p class="text-sm font-semibold text-red-900">{{ ucfirst($request->type) }} Ditolak</p>
                                <p class="mt-1 text-xs text-red-700">{{ $request->rejection_reason ?? 'Alasan tidak disebutkan' }}</p>
                            </div>
                        @endforeach
                    @endif

                    @if($pendingRequests > 0)
                        <div class="rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-3">
                            <p class="text-sm font-semibold text-amber-900">{{ $pendingRequests }} Menunggu</p>
                            <p class="mt-1 text-xs text-amber-700">Permintaan sedang diproses</p>
                        </div>
                    @endif

                    @if($rejectedRequests->count() === 0 && $pendingRequests === 0)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center text-sm text-slate-500">
                            Tidak ada notifikasi
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Modal for Izin/Sakit Request -->
<div id="izin-sakit-modal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop with blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Request Izin/Sakit</h3>
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
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Jenis</label>
                        <select name="type" required class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                            <option value="">Pilih jenis</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                    
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
function openModal() {
    document.getElementById('izin-sakit-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('izin-sakit-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
