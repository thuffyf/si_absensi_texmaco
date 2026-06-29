@extends('layouts.app')

@section('title', 'Jadwal — SITEXA Absensi')
@section('page_title', 'Jadwal')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Masuk Hari Ini</h2>
                <p class="text-sm text-slate-500">Jadwal Minggu ke-{{ $currentWeek }} sesuai rotasi 4 minggu.</p>
            </div>
            <div class="text-sm text-slate-500">Minggu ke-{{ $currentWeek }}</div>
        </div>

        <div class="mt-6 space-y-6">
            @forelse($todaySchedules as $schedule)
                <div class="rounded-3xl border-2 {{ $schedule['is_running'] ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm {{ $schedule['is_running'] ? 'ring-2 ring-emerald-200' : '' }}">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $schedule['subject'] }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $schedule['teacher_name'] }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                            </span>
                            @if($schedule['is_running'])
                                <span class="rounded-full bg-emerald-200 px-3 py-1 font-semibold text-emerald-900">
                                    Sedang Berlangsung
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-600">
                    Tidak ada jadwal untuk hari ini.
                </div>
            @endforelse
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Kelola Jadwal</h2>
                <p class="text-sm text-slate-500">Tambah, edit, dan nonaktifkan jadwal kelas TEI.</p>
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-wrap gap-2">
                <form method="GET" action="{{ route('schedules.index') }}" class="flex gap-2">
                    <input type="text" name="subject" value="{{ request('subject') }}" class="w-64 rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Mata pelajaran" />
                    <button type="submit" class="flex items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        Cari
                    </button>
                    <a href="{{ route('schedules.index') }}" class="flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        Reset
                    </a>
                </form>
            </div>
            <button onclick="document.getElementById('add-schedule-modal').classList.remove('hidden')" class="flex items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 whitespace-nowrap">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jadwal
            </button>
        </div>

        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-3 py-3">Hari</th>
                        <th class="px-3 py-3">Jam</th>
                        <th class="px-3 py-3">Kelas</th>
                        <th class="px-3 py-3">Mapel</th>
                        <th class="px-3 py-3">Guru</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($managedSchedules as $schedule)
                        <tr class="text-slate-700">
                            <td class="px-3 py-3 font-medium text-slate-900">{{ $schedule->day_of_week }}</td>
                            <td class="px-3 py-3 tabular-nums">{{ $schedule->start_time?->format('H:i') }} - {{ $schedule->end_time?->format('H:i') }}</td>
                            <td class="px-3 py-3">{{ $schedule->class_name }}</td>
                            <td class="px-3 py-3">{{ $schedule->subject }}</td>
                            <td class="px-3 py-3">{{ $schedule->teacher?->name ?? '—' }}</td>
                            <td class="px-3 py-3">
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-emerald-100 text-emerald-800' => $schedule->status === 'aktif',
                                    'bg-slate-100 text-slate-700' => $schedule->status !== 'aktif',
                                ])>
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex justify-end gap-2">
                                    <button onclick='openEditModal(@json($schedule->id), @json($schedule->teacher_id ?? ""), @json($schedule->class_name), @json($schedule->subject), @json($schedule->day_of_week), @json($schedule->start_time?->format("H:i") ?? ""), @json($schedule->end_time?->format("H:i") ?? ""), @json($schedule->status))' class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 transition-colors hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-8 text-center text-sm text-slate-500">
                                Belum ada jadwal tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal Tambah Jadwal -->
    <div id="add-schedule-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center lg:pl-64">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('add-schedule-modal').classList.add('hidden')"></div>
        
        <!-- Modal Content -->
        <div class="relative mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Tambah Jadwal Baru</h3>
                <button onclick="document.getElementById('add-schedule-modal').classList.add('hidden')" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('schedules.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Guru</label>
                    <select name="teacher_id" id="add_teacher_id" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="">Pilih guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" data-subject="{{ $teacher->subject }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }} ({{ $teacher->subject ?? 'Tidak ada mapel' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Kelas</label>
                    <select name="class_name" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="">Pilih kelas</option>
                        @foreach($classOptions as $className)
                            <option value="{{ $className }}" @selected(old('class_name') === $className)>{{ $className }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="subject" id="add_subject" value="{{ old('subject') }}" />
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Hari</label>
                    <select name="day_of_week" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="">Pilih hari</option>
                        @foreach($dayOptions as $dayName)
                            <option value="{{ $dayName }}" @selected(old('day_of_week') === $dayName)>{{ $dayName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Jam Mulai</label>
                    <input name="start_time" type="time" value="{{ old('start_time') }}" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Jam Selesai</label>
                    <input name="end_time" type="time" value="{{ old('end_time') }}" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Status</label>
                    <select name="status" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                        <option value="idle" @selected(old('status') === 'idle')>Idle</option>
                    </select>
                </div>
                <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 mt-2">Simpan Jadwal</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit Jadwal -->
    <div id="edit-schedule-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center lg:pl-64">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Edit Jadwal</h3>
                <button onclick="closeEditModal()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" id="edit-schedule-form" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-schedule-id">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Guru</label>
                    <select name="teacher_id" id="edit-teacher_id" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="">Pilih guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" data-subject="{{ $teacher->subject }}">{{ $teacher->name }} ({{ $teacher->subject ?? 'Tidak ada mapel' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Kelas</label>
                    <select name="class_name" id="edit-class_name" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="">Pilih kelas</option>
                        @foreach($classOptions as $className)
                            <option value="{{ $className }}">{{ $className }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="subject" id="edit-subject" />
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Hari</label>
                    <select name="day_of_week" id="edit-day_of_week" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="">Pilih hari</option>
                        @foreach($dayOptions as $dayName)
                            <option value="{{ $dayName }}">{{ $dayName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Jam Mulai</label>
                    <input name="start_time" type="time" id="edit-start_time" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Jam Selesai</label>
                    <input name="end_time" type="time" id="edit-end_time" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Status</label>
                    <select name="status" id="edit-status" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="aktif">Aktif</option>
                        <option value="idle">Idle</option>
                    </select>
                </div>
                <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 mt-2">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto fill subject based on selected teacher in Add Modal
document.getElementById('add_teacher_id')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const subject = selectedOption.getAttribute('data-subject') || '';
    document.getElementById('add_subject').value = subject;
});

// Auto fill subject based on selected teacher in Edit Modal
document.getElementById('edit-teacher_id')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const subject = selectedOption.getAttribute('data-subject') || '';
    document.getElementById('edit-subject').value = subject;
});

function openEditModal(id, teacherId, className, subject, dayOfWeek, startTime, endTime, status) {
    document.getElementById('edit-schedule-id').value = id;
    document.getElementById('edit-teacher_id').value = teacherId;
    document.getElementById('edit-class_name').value = className;
    document.getElementById('edit-subject').value = subject;
    document.getElementById('edit-day_of_week').value = dayOfWeek;
    document.getElementById('edit-start_time').value = startTime;
    document.getElementById('edit-end_time').value = endTime;
    document.getElementById('edit-status').value = status;

    const form = document.getElementById('edit-schedule-form');
    form.action = '/jadwal/' + id;

    document.getElementById('edit-schedule-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('edit-schedule-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeEditModal();
        document.getElementById('add-schedule-modal').classList.add('hidden');
    }
});

// Close modal when clicking outside (using mousedown to prevent accidental dismissals)
document.getElementById('edit-schedule-modal')?.addEventListener('mousedown', function(e) {
    if (e.target === this || e.target.classList.contains('absolute')) {
        closeEditModal();
    }
});

document.getElementById('add-schedule-modal')?.addEventListener('mousedown', function(e) {
    if (e.target === this || e.target.classList.contains('absolute')) {
        this.classList.add('hidden');
    }
});

    (function () {
        const root = document.getElementById('day-of-week-dropdown');
        if (!root) return;

        const button = document.getElementById('day_of_week_button');
        const menu = document.getElementById('day_of_week_menu');
        const input = document.getElementById('day_of_week_input');
        const label = document.getElementById('day_of_week_label');

        const openMenu = () => {
            menu.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
        };

        const closeMenu = () => {
            menu.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        };

        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (menu.classList.contains('hidden')) {
                openMenu();
            } else {
                closeMenu();
            }
        });

        menu.addEventListener('click', function (e) {
            const target = e.target;
            if (!(target instanceof HTMLElement)) return;
            const value = target.getAttribute('data-value');
            if (value === null) return;

            input.value = value;
            label.textContent = value === '' ? 'Pilih hari' : (value === 'all' ? 'Semua hari' : value);
            closeMenu();
        });

        document.addEventListener('click', function (e) {
            if (!root.contains(e.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeMenu();
            }
        });
    })();
</script>
@endpush
