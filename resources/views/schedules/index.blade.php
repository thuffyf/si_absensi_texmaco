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
                <form method="GET" action="{{ route('schedules.index') }}" class="flex flex-wrap gap-2">
                    <input type="text" name="subject" value="{{ request('subject') }}" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Mata pelajaran" />
                    @php
                        $selectedDay = request('day_of_week');
                        $selectedDayLabel = 'Pilih hari';
                        if ($selectedDay === 'all') {
                            $selectedDayLabel = 'Semua hari';
                        } elseif (!empty($selectedDay)) {
                            $selectedDayLabel = $selectedDay;
                        }
                    @endphp
                    <!-- Custom dropdown agar opsi selalu membuka ke bawah -->
                    <div class="relative" id="day-of-week-dropdown">
                        <input type="hidden" name="day_of_week" id="day_of_week_input" value="{{ $selectedDay }}" />
                        <button
                            type="button"
                            id="day_of_week_button"
                            class="flex items-center justify-between gap-3 rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                        >
                            <span id="day_of_week_label" class="whitespace-nowrap">{{ $selectedDayLabel }}</span>
                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            id="day_of_week_menu"
                            class="absolute left-0 top-full z-50 mt-2 hidden w-full min-w-[12rem] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg"
                            role="listbox"
                        >
                            <button type="button" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50" data-value="">Pilih hari</button>
                            <button type="button" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50" data-value="all">Semua hari</button>
                            <div class="max-h-60 overflow-y-auto py-1">
                                @foreach($dayOptions as $dayName)
                                    <button type="button" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50" data-value="{{ $dayName }}">{{ $dayName }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <select name="class_name" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        <option value="" disabled @selected(!request()->filled('class_name'))>Pilih kelas</option>
                        <option value="all" @selected(request('class_name') === 'all')>Semua kelas</option>
                        @foreach($classOptions as $className)
                            <option value="{{ $className }}" @selected(request('class_name') === $className)>{{ $className }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="flex items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        Cari
                    </button>
                    <a href="{{ route('schedules.index') }}" class="flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        Reset
                    </a>
                </form>
            </div>
            <button onclick="document.getElementById('add-schedule-modal').classList.remove('hidden')" class="flex items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
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
                                    <a href="{{ route('schedules.edit', $schedule) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                        Edit
                                    </a>
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
    <div id="add-schedule-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
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
                <select name="teacher_id" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="">Pilih guru</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                    @endforeach
                </select>
                <select name="class_name" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="">Pilih kelas</option>
                    @foreach($classOptions as $className)
                        <option value="{{ $className }}" @selected(old('class_name') === $className)>{{ $className }}</option>
                    @endforeach
                </select>
                <input name="subject" value="{{ old('subject') }}" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Mata pelajaran" required />
                <select name="day_of_week" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="">Pilih hari</option>
                    @foreach($dayOptions as $dayName)
                        <option value="{{ $dayName }}" @selected(old('day_of_week') === $dayName)>{{ $dayName }}</option>
                    @endforeach
                </select>
                <input name="start_time" type="time" value="{{ old('start_time') }}" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required />
                <input name="end_time" type="time" value="{{ old('end_time') }}" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required />
                <select name="status" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="idle" @selected(old('status') === 'idle')>Idle</option>
                </select>
                <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Jadwal</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
