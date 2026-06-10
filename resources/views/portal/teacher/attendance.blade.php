@extends('portal.layouts.app')

@section('title', 'Absensi Guru - SITEXA')
@section('page_title', 'Kehadiran Siswa')
@section('page_subtitle', $dayName . ', ' . $date)

@section('content')
    @php
        $statusLabel = fn ($status) => match ($status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha', 'alfa', 'alpa' => 'Alpa',
            'belum_absen' => 'Belum',
            default => $status ?: '-',
        };

        $statusColor = fn ($status) => match ($status) {
            'hadir' => 'bg-emerald-100 text-emerald-700',
            'izin' => 'bg-amber-100 text-amber-700',
            'sakit' => 'bg-rose-100 text-rose-700',
            'alpha', 'alfa', 'alpa' => 'bg-slate-200 text-slate-700',
            'belum_absen' => 'bg-sky-100 text-sky-700',
            default => 'bg-slate-100 text-slate-500',
        };

        $currentItems = match ($selectedView) {
            'tidak_hadir' => $absenceItems,
            'belum_absen' => $notRecordedItems,
            default => $presentItems,
        };

        $viewCount = match ($selectedView) {
            'tidak_hadir' => $summary['izin'] + $summary['sakit'] + $summary['alpa'],
            'belum_absen' => $summary['belum_absen'],
            default => $summary['hadir'],
        };

        $baseQuery = [
            'date' => $date,
            'class_name' => $selectedClass,
            'schedule_id' => $selectedScheduleId,
        ];
    @endphp

    <section class="rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('portal.teacher.attendance') }}" class="space-y-4">
            <div>
                <label for="date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
                <input
                    id="date"
                    name="date"
                    type="date"
                    value="{{ $date }}"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100"
                />
            </div>

            <div>
                <label for="class_name" class="mb-2 block text-sm font-semibold text-slate-700">Kelas</label>
                <select
                    id="class_name"
                    name="class_name"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100"
                >
                    <option value="">Sesuai jadwal</option>
                    @foreach ($classes as $className)
                        <option value="{{ $className }}" @selected($selectedClass === $className)>{{ $className }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="schedule_id" class="mb-2 block text-sm font-semibold text-slate-700">Jadwal</label>
                <select
                    id="schedule_id"
                    name="schedule_id"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100"
                >
                    <option value="">Semua jadwal hari ini</option>
                    @foreach ($schedules as $schedule)
                        <option value="{{ $schedule->id }}" @selected($selectedScheduleId === (string) $schedule->id)>
                            {{ $schedule->class_name }} | {{ $schedule->subject }} | {{ $schedule->start_time?->format('H:i') }}-{{ $schedule->end_time?->format('H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800"
            >
                Terapkan Filter
            </button>
        </form>
    </section>

    <section class="mt-4 grid grid-cols-2 gap-3">
        <div class="rounded-[1.5rem] border border-emerald-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hadir</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $summary['hadir'] }}</p>
        </div>
        <div class="rounded-[1.5rem] border border-amber-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Izin</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $summary['izin'] }}</p>
        </div>
        <div class="rounded-[1.5rem] border border-rose-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Sakit</p>
            <p class="mt-2 text-3xl font-bold text-rose-600">{{ $summary['sakit'] }}</p>
        </div>
        <div class="rounded-[1.5rem] border border-sky-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Belum</p>
            <p class="mt-2 text-3xl font-bold text-sky-600">{{ $summary['belum_absen'] }}</p>
        </div>
    </section>

    <section class="mt-4 rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="text-base font-bold text-slate-900">Jadwal Hari Ini</h2>
        <div class="mt-4 space-y-3">
            @forelse ($schedules as $schedule)
                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                    <p class="font-semibold text-slate-900">{{ $schedule->subject }}</p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $schedule->class_name }} · {{ $schedule->start_time?->format('H:i') }} - {{ $schedule->end_time?->format('H:i') }}
                    </p>
                </div>
            @empty
                <div class="rounded-2xl bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                    Tidak ada jadwal pada tanggal ini.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-4 rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid grid-cols-3 gap-2">
            <a
                href="{{ route('portal.teacher.attendance', array_merge($baseQuery, ['view' => 'hadir'])) }}"
                class="rounded-2xl px-3 py-3 text-center text-xs font-semibold {{ $selectedView === 'hadir' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-500' }}"
            >
                Hadir ({{ $summary['hadir'] }})
            </a>
            <a
                href="{{ route('portal.teacher.attendance', array_merge($baseQuery, ['view' => 'tidak_hadir'])) }}"
                class="rounded-2xl px-3 py-3 text-center text-xs font-semibold {{ $selectedView === 'tidak_hadir' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-500' }}"
            >
                Tidak ({{ $summary['izin'] + $summary['sakit'] + $summary['alpa'] }})
            </a>
            <a
                href="{{ route('portal.teacher.attendance', array_merge($baseQuery, ['view' => 'belum_absen'])) }}"
                class="rounded-2xl px-3 py-3 text-center text-xs font-semibold {{ $selectedView === 'belum_absen' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-500' }}"
            >
                Belum ({{ $summary['belum_absen'] }})
            </a>
        </div>

        <div class="mt-4 space-y-3">
            @forelse ($currentItems as $item)
                <article class="rounded-2xl bg-slate-50 px-4 py-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $item['student_name'] }}</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $item['classroom'] }} · NIS {{ $item['nis'] }}
                                @if (!empty($item['time']))
                                    · {{ $item['time'] }}
                                @endif
                            </p>
                            @if (!empty($item['note']))
                                <p class="mt-2 text-sm text-slate-600">{{ $item['note'] }}</p>
                            @endif
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor($item['status']) }}">
                            {{ $statusLabel($item['status']) }}
                        </span>
                    </div>

                    <details class="mt-3 rounded-2xl bg-white px-4 py-3">
                        <summary class="cursor-pointer text-sm font-semibold text-sky-700">Ubah status</summary>

                        <form action="{{ route('portal.teacher.attendance.update') }}" method="POST" class="mt-4 space-y-3">
                            @csrf
                            <input type="hidden" name="nis" value="{{ $item['nis'] }}" />
                            <input type="hidden" name="date" value="{{ $date }}" />
                            <input type="hidden" name="class_name" value="{{ $selectedClass }}" />
                            <input type="hidden" name="schedule_id" value="{{ $selectedScheduleId }}" />
                            <input type="hidden" name="view" value="{{ $selectedView }}" />

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                                <select
                                    name="status"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                >
                                    @foreach (['hadir', 'izin', 'sakit', 'alpa'] as $status)
                                        <option value="{{ $status }}" @selected(($item['status'] === 'belum_absen' ? 'hadir' : $item['status']) === $status)>
                                            {{ $statusLabel($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Keterangan</label>
                                <textarea
                                    name="note"
                                    rows="3"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                >{{ $item['note'] }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800"
                            >
                                Simpan Perubahan
                            </button>
                        </form>
                    </details>
                </article>
            @empty
                <div class="rounded-2xl bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                    Tidak ada data untuk tab ini.
                </div>
            @endforelse
        </div>

        <p class="mt-4 text-center text-xs text-slate-400">Menampilkan {{ $viewCount }} siswa pada tampilan aktif.</p>
    </section>
@endsection
