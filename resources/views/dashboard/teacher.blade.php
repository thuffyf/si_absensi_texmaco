@extends('layouts.app')

@section('title', 'Dashboard Guru — SITEXA Absensi')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Monitoring permintaan Izin/Sakit siswa')

@section('content')
<div class="mx-auto flex w-full max-w-none flex-col gap-3 max-lg:space-y-1 lg:h-full lg:min-h-0 lg:gap-3 lg:overflow-hidden">
    <div class="grid min-h-0 flex-1 grid-cols-1 gap-3 lg:grid-cols-12 lg:gap-4 lg:overflow-hidden">
        <!-- Main Content -->
        <section class="flex min-h-0 flex-col lg:col-span-9 lg:overflow-hidden">
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <div class="mt-3 grid shrink-0 grid-cols-3 gap-2 sm:grid-cols-3 lg:mt-4 lg:gap-3">
                    <button onclick="filterRequests('pending')" class="filter-btn status-pending rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-3 lg:p-4 hover:border-amber-200 hover:shadow-md transition-all cursor-pointer">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-800 lg:text-xs">Menunggu</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-amber-900 lg:text-3xl">{{ $pendingCount }}</p>
                    </button>
                    <button onclick="filterRequests('approved')" class="filter-btn status-approved rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-3 lg:p-4 hover:border-emerald-200 hover:shadow-md transition-all cursor-pointer">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800 lg:text-xs">Disetujui</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-900 lg:text-3xl">{{ $approvedCount }}</p>
                    </button>
                    <button onclick="filterRequests('rejected')" class="filter-btn status-rejected rounded-xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-3 lg:p-4 hover:border-red-200 hover:shadow-md transition-all cursor-pointer">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-red-800 lg:text-xs">Ditolak</p>
                        <p class="mt-1 text-2xl font-bold tabular-nums text-red-900 lg:text-3xl">{{ $rejectedCount }}</p>
                    </button>
                </div>

                <div class="mt-4 min-h-0 flex-1 overflow-hidden rounded-xl border border-slate-100 bg-slate-50/40 p-2.5 lg:mt-4 lg:flex lg:items-stretch lg:justify-center lg:p-3">
                    @if($allRequests->count() > 0)
                        <div class="w-full space-y-3 overflow-hidden lg:flex-1 lg:overflow-y-auto">
                            @foreach($allRequests as $request)
                                <div class="request-card flex flex-col gap-3 rounded-lg bg-white p-4 border border-slate-200 cursor-pointer hover:border-sky-300 hover:shadow-md hover:bg-blue-50/30 transition-all" 
                                     data-status="{{ $request->status }}"
                                     onclick="openRequestDetail({{ $request->id }})">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $request->type === 'izin' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }} text-sm font-bold shrink-0">
                                            {{ substr($request->student->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-slate-900">{{ $request->student->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $request->student->class_name }} · {{ $request->type === 'izin' ? 'Izin' : 'Sakit' }}</p>
                                            <p class="text-xs text-slate-400">{{ $request->requested_at?->format('d/m/Y H:i') }}</p>
                                            <p class="text-xs text-slate-400 mt-1">{{ $request->start_date?->format('d/m/Y') }} sd {{ $request->end_date?->format('d/m/Y') ?? '—' }}</p>
                                        </div>
                                        <span class="status-badge @class([
                                            'rounded-full px-2 py-1 text-xs font-semibold shrink-0 cursor-pointer hover:shadow-md transition-shadow',
                                            'bg-emerald-100 text-emerald-800' => $request->status === 'approved',
                                            'bg-red-100 text-red-800' => $request->status === 'rejected',
                                            'bg-amber-100 text-amber-800' => $request->status === 'pending_teacher',
                                            'bg-slate-100 text-slate-800' => $request->status === 'pending_admin',
                                        ])"
                                        onclick="event.stopPropagation(); filterRequests('{{ $request->status === 'approved' ? 'approved' : ($request->status === 'rejected' ? 'rejected' : 'pending') }}')"
                                        >{{ match($request->status) {
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'pending_teacher' => 'Menunggu',
                                            'pending_admin' => 'Menunggu TU',
                                            default => strtoupper($request->status),
                                        } }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600">{{ $request->reason }}</p>
                                    @if($request->photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $request->photo) }}" alt="Bukti {{ $request->type }}" class="max-h-32 rounded-lg border border-slate-200 object-cover">
                                        </div>
                                    @endif
                                    @if($request->response_note)
                                        <div class="mt-2 text-xs text-slate-500">
                                            <span class="font-semibold">Catatan:</span> {{ $request->response_note }}
                                        </div>
                                    @endif
                                    @if($request->rejection_reason)
                                        <div class="mt-2 text-xs text-red-600">
                                            <span class="font-semibold">Alasan Penolakan:</span> {{ str_replace('Admin konfirmasi: ', '', $request->rejection_reason) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex h-full items-center justify-center text-slate-500">
                            <p class="text-sm">Belum ada permintaan izin/sakit.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Right Sidebar -->
        <div class="flex min-h-0 flex-col gap-4 lg:col-span-3 lg:gap-4 lg:overflow-hidden">
            <section class="shrink-0 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500 lg:text-sm">Total Permintaan</h2>
                <p class="mt-2 text-5xl font-bold tabular-nums text-slate-900 lg:text-6xl xl:text-7xl">{{ $totalCount }}</p>
                <p class="mt-2 text-sm text-slate-500 lg:text-base">Semua waktu</p>
                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100 lg:h-3">
                    <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-blue-600" style="width: {{ $approvedRate }}%"></div>
                </div>
                <p class="mt-2 text-xs leading-snug text-slate-500 lg:text-sm">{{ $approvedRate }}% disetujui.</p>
            </section>

            <!-- Recent Activity -->
            <section class="min-h-0 flex-1 overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:flex lg:flex-col lg:p-4">
                <h2 class="shrink-0 text-sm font-bold text-slate-900 lg:text-base">Aktivitas Guru</h2>
                @if($teacherActivities->count() > 0)
                    <div class="mt-2 flex-1 space-y-2 overflow-hidden lg:mt-2 lg:flex-1 lg:overflow-y-auto">
                        @foreach($teacherActivities as $request)
                            <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-2">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full {{ $request->type === 'izin' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }} text-[10px] font-bold">
                                    {{ substr($request->student->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-xs font-medium text-slate-900 lg:text-sm">{{ $request->student->name }}</p>
                                    <p class="text-[10px] text-slate-500 lg:text-xs">{{ $request->responded_at?->format('d/m') }}</p>
                                </div>
                                <span @class([
                                    'rounded-full px-1.5 py-0.5 text-[10px] font-semibold lg:text-xs',
                                    'bg-emerald-100 text-emerald-800' => $request->status === 'approved',
                                    'bg-red-100 text-red-800' => $request->status === 'rejected',
                                ])>{{ $request->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-2 text-xs text-slate-500 lg:text-sm">Belum ada aktivitas guru.</p>
                @endif
            </section>
        </div>
    </div>
</div>

<!-- Modal Detail Izin/Sakit -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeDetailModal(event)">
    <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-xl" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-200 p-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900" id="modal-student-name">-</h2>
                <p class="mt-1 text-sm text-slate-500" id="modal-student-info">-</p>
            </div>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6 space-y-4">
            <!-- Type & Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis Permintaan</label>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide" id="modal-type-badge">-</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <div class="mt-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide" id="modal-status-badge">-</span>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Permintaan</label>
                <p class="mt-2 text-sm font-semibold text-slate-900" id="modal-dates">-</p>
            </div>

            <!-- Reason -->
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alasan</label>
                <div class="mt-2 rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <p class="text-sm text-slate-700" id="modal-reason">-</p>
                </div>
            </div>

            <!-- Photo -->
            <div id="photo-section" class="hidden">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Bukti</label>
                <div class="mt-2">
                    <img id="modal-photo" src="" alt="Bukti" class="max-h-80 rounded-xl border border-slate-200 object-cover w-full">
                </div>
            </div>

            <!-- Response Note -->
            <div id="response-note-section" class="hidden">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Catatan Guru</label>
                <div class="mt-2 rounded-xl bg-emerald-50 p-4 border border-emerald-200">
                    <p class="text-sm text-emerald-700" id="modal-response-note">-</p>
                </div>
            </div>

            <!-- Rejection Reason -->
            <div id="rejection-section" class="hidden">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alasan Penolakan</label>
                <div class="mt-2 rounded-xl bg-red-50 p-4 border border-red-200">
                    <p class="text-sm text-red-700" id="modal-rejection-reason">-</p>
                </div>
            </div>

            <!-- Timeline -->
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Waktu Pembuatan</label>
                <p class="mt-2 text-sm text-slate-700" id="modal-created-at">-</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-200 p-6 flex justify-end">
            <button onclick="closeDetailModal()" class="px-4 py-2 rounded-lg bg-slate-200 text-slate-800 hover:bg-slate-300 font-semibold transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Data untuk semua requests -->
<script>
const requestsData = @json($allRequests->mapWithKeys(function ($request) {
    return [
        $request->id => [
            'id' => $request->id,
            'student_name' => $request->student->name,
            'student_info' => 'NIS ' . $request->student->nis . ' · ' . $request->student->class_name,
            'type' => $request->type,
            'type_label' => $request->type === 'izin' ? 'Izin' : 'Sakit',
            'status' => $request->status,
            'status_label' => match ($request->status) {
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'pending_teacher' => 'Menunggu',
                'pending_admin' => 'Menunggu TU',
                default => strtoupper($request->status),
            },
            'requested_at' => $request->requested_at?->format('d M Y H:i') ?? '-',
            'start_date' => $request->start_date?->format('d/m/Y') ?? '-',
            'end_date' => $request->end_date?->format('d/m/Y') ?? '—',
            'reason' => $request->reason,
            'photo' => $request->photo ? asset('storage/' . $request->photo) : '',
            'response_note' => $request->response_note ?? '',
            'rejection_reason' => str_replace('Admin konfirmasi: ', '', $request->rejection_reason ?? ''),
        ],
    ];
}));

// Filter function
function filterRequests(status) {
    const cards = document.querySelectorAll('.request-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const cardStatus = card.getAttribute('data-status');
        let shouldShow = true;

        if (status === 'pending') {
            shouldShow = cardStatus === 'pending_teacher' || cardStatus === 'pending_admin';
        } else if (status === 'approved') {
            shouldShow = cardStatus === 'approved';
        } else if (status === 'rejected') {
            shouldShow = cardStatus === 'rejected';
        }

        if (shouldShow) {
            card.classList.remove('hidden');
            visibleCount++;
        } else {
            card.classList.add('hidden');
        }
    });

    // Update filter button styling
    updateFilterButtons(status);

    // Show empty message if no cards visible
    const container = document.querySelector('.request-card')?.parentElement;
    if (!container) return;
    
    const emptyMsg = container.querySelector('.empty-message');
    if (visibleCount === 0) {
        if (!emptyMsg) {
            const msg = document.createElement('div');
            msg.className = 'empty-message flex h-full items-center justify-center text-slate-500';
            msg.innerHTML = '<p class="text-sm">Tidak ada permintaan dengan filter ini.</p>';
            container.appendChild(msg);
        }
    } else if (emptyMsg) {
        emptyMsg.remove();
    }
}

// Update filter button styling
function updateFilterButtons(status) {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-sky-400', 'border-sky-300', 'shadow-md');
    });

    let activeBtn = null;
    if (status === 'pending') {
        activeBtn = document.querySelector('.status-pending');
    } else if (status === 'approved') {
        activeBtn = document.querySelector('.status-approved');
    } else if (status === 'rejected') {
        activeBtn = document.querySelector('.status-rejected');
    }

    if (activeBtn) {
        activeBtn.classList.add('ring-2', 'ring-sky-400', 'border-sky-300', 'shadow-md');
    }
}

// Open detail modal
function openRequestDetail(requestId) {
    const data = requestsData[requestId];
    if (!data) return;

    // Set basic info
    document.getElementById('modal-student-name').textContent = data.student_name;
    document.getElementById('modal-student-info').textContent = data.student_info;
    document.getElementById('modal-reason').textContent = data.reason;
    document.getElementById('modal-created-at').textContent = data.requested_at;

    // Set type badge
    const typeBadge = document.getElementById('modal-type-badge');
    typeBadge.textContent = data.type_label;
    typeBadge.className = 'inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide ' + 
        (data.type === 'izin' ? 'bg-amber-100 text-amber-900' : 'bg-rose-100 text-rose-900');

    // Set status badge
    const statusBadge = document.getElementById('modal-status-badge');
    statusBadge.textContent = data.status_label;
    let statusClass = '';
    if (data.status === 'approved') statusClass = 'bg-emerald-100 text-emerald-800';
    else if (data.status === 'rejected') statusClass = 'bg-red-100 text-red-800';
    else if (data.status === 'pending_teacher') statusClass = 'bg-amber-100 text-amber-800';
    else statusClass = 'bg-slate-100 text-slate-800';
    statusBadge.className = 'inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide ' + statusClass;

    // Set dates
    document.getElementById('modal-dates').textContent = `${data.start_date} sd ${data.end_date}`;

    // Handle photo
    if (data.photo) {
        document.getElementById('modal-photo').src = data.photo;
        document.getElementById('photo-section').classList.remove('hidden');
    } else {
        document.getElementById('photo-section').classList.add('hidden');
    }

    // Handle response note
    if (data.response_note) {
        document.getElementById('modal-response-note').textContent = data.response_note;
        document.getElementById('response-note-section').classList.remove('hidden');
    } else {
        document.getElementById('response-note-section').classList.add('hidden');
    }

    // Handle rejection reason
    if (data.rejection_reason) {
        document.getElementById('modal-rejection-reason').textContent = data.rejection_reason;
        document.getElementById('rejection-section').classList.remove('hidden');
    } else {
        document.getElementById('rejection-section').classList.add('hidden');
    }

    // Show modal
    document.getElementById('detail-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Close detail modal
function closeDetailModal(e) {
    if (e && e.target !== document.getElementById('detail-modal')) return;
    
    document.getElementById('detail-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close on ESC key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !document.getElementById('detail-modal').classList.contains('hidden')) {
        closeDetailModal();
    }
});
</script>

@endsection
