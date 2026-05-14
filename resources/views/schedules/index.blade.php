@extends('layouts.app')

@section('title', 'Jadwal — SITEXA Absensi')
@section('page_title', 'Jadwal')
@section('page_subtitle', 'Kelas TEI · waktu ditampilkan WIB (Asia/Jakarta, UTC+7)')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 animate-fade-in">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Ringkasan per kelas</h1>
        <p class="mt-1 text-sm text-slate-600 sm:text-base">Pilih kelas untuk melihat kehadiran hari ini.</p>
    </div>

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

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3 md:gap-6">
        @foreach($classCards as $card)
            <article class="flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <h2 class="border-b border-slate-100 pb-3 text-xl font-bold text-slate-900">{{ $card['class_name'] }}</h2>
                <dl class="mt-4 flex flex-1 flex-col gap-3 text-sm">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Wali kelas</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $card['homeroom_teacher_name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah siswa</dt>
                        <dd class="mt-0.5 text-lg font-bold tabular-nums text-slate-900">{{ $card['student_count'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hari ini</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $todayLabel }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <a
                        href="{{ route('schedules.presence', ['slug' => $card['slug']]) }}"
                        class="flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                    >
                        Masuk
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <details class="group rounded-3xl border border-slate-200 bg-white shadow-sm">
        <summary class="cursor-pointer list-none rounded-3xl px-5 py-4 font-semibold text-slate-800 marker:content-none [&::-webkit-details-marker]:hidden">
            <span class="flex items-center justify-between gap-2">
                <span>Kelola jadwal pelajaran (detail)</span>
                <span class="text-slate-400 transition-transform group-open:rotate-180" aria-hidden="true">▼</span>
            </span>
        </summary>
        <div class="border-t border-slate-100 px-5 pb-6 pt-2">
            <div class="mb-6 rounded-2xl border border-slate-100 bg-slate-50/80 p-5">
                <h2 class="mb-4 text-base font-bold text-slate-900">Tambah jadwal</h2>
                <form method="POST" action="{{ route('schedules.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    @csrf
                    <select name="teacher_id" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required>
                        <option value="">Pilih guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    <input name="class_name" value="{{ old('class_name') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Kelas" required />
                    <input name="subject" value="{{ old('subject') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Mata pelajaran" required />
                    <input name="day_of_week" value="{{ old('day_of_week') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Hari (Senin, …)" required />
                    <input name="start_time" type="time" value="{{ old('start_time') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required />
                    <input name="end_time" type="time" value="{{ old('end_time') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required />
                    <input name="total_students" type="number" min="0" value="{{ old('total_students') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Jumlah siswa" />
                    <select name="status" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" required>
                        <option value="aktif" @selected(old('status') === 'aktif')>Aktif</option>
                        <option value="idle" @selected(old('status') === 'idle')>Idle</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 md:col-span-2 lg:col-span-4">Simpan jadwal</button>
                </form>
            </div>

            <div class="mb-6 rounded-2xl border border-slate-100 bg-slate-50/80 p-5">
                <h2 class="mb-4 text-base font-bold text-slate-900">Filter daftar</h2>
                <form method="GET" action="{{ route('schedules.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <input type="text" name="class" value="{{ request('class') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Kelas" />
                    <select name="teacher" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        <option value="">Semua guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(request('teacher') == $teacher->id)>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="subject" value="{{ request('subject') }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" placeholder="Mata pelajaran" />
                    <div class="flex flex-wrap gap-2">
                        <button class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 hover:bg-slate-50" type="submit">Terapkan</button>
                        <a href="{{ route('schedules.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Reset</a>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                @forelse($schedulesByDay as $day => $items)
                    <div>
                        <h3 class="mb-3 flex items-center gap-2 text-base font-bold text-slate-900">
                            <span class="h-2 w-2 rounded-full bg-sky-500" aria-hidden="true"></span>
                            {{ $day }}
                        </h3>
                        <div class="space-y-3">
                            @foreach($items as $schedule)
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="grid grid-cols-1 items-center gap-4 md:grid-cols-2 lg:grid-cols-6">
                                        <div>
                                            <p class="text-xs font-medium text-slate-500">Jam</p>
                                            <p class="font-semibold text-slate-900">{{ $schedule->start_time }} – {{ $schedule->end_time }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-slate-500">Kelas</p>
                                            <p class="font-semibold text-slate-900">{{ $schedule->class_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-slate-500">Mapel</p>
                                            <p class="font-semibold text-slate-900">{{ $schedule->subject }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-slate-500">Guru</p>
                                            <p class="font-semibold text-slate-900">{{ $schedule->teacher?->name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-slate-500">Jumlah siswa</p>
                                            <p class="font-semibold text-slate-900">{{ $schedule->total_students ?? '—' }}</p>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $schedule->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">{{ ucfirst($schedule->status) }}</span>
                                            <a class="rounded-lg border border-slate-200 px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50" href="{{ route('schedules.edit', $schedule) }}">Ubah</a>
                                            <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-lg border border-red-200 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-50" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-slate-500">Belum ada jadwal pelajaran di database.</p>
                @endforelse
            </div>
        </div>
    </details>
</div>
@endsection
