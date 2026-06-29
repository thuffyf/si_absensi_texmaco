@extends('layouts.app')

@section('title', 'Data Siswa â€” SITEXA Absensi')
@section('page_title', 'Data Siswa')
@section('page_subtitle', 'Kelola data siswa dan status NFC')

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

    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap gap-3">
            <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" class="w-64 rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Cari nama atau NIS..." />
                
                <select name="class" class="rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">Semua Kelas</option>
                    <option value="X" @selected(request('class') === 'X')>X</option>
                    <option value="XI" @selected(request('class') === 'XI')>XI</option>
                    <option value="XII" @selected(request('class') === 'XII')>XII</option>
                </select>

                <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="tidak_aktif" @selected(request('status') === 'tidak_aktif')>Tidak Aktif</option>
                    <option value="lulus" @selected(request('status') === 'lulus')>Lulus</option>
                </select>

                <select name="nfc" class="rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    <option value="">Semua NFC</option>
                    <option value="kartu" @selected(request('nfc') === 'kartu')>Kartu</option>
                    <option value="handphone" @selected(request('nfc') === 'handphone')>Handphone</option>
                    <option value="belum_terdaftar" @selected(request('nfc') === 'belum_terdaftar')>Belum terdaftar</option>
                </select>

                <button type="submit" class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Filter & Cari
                </button>
                <a href="{{ route('students.index') }}" class="flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Reset</a>
            </form>
        </div>
        <button onclick="document.getElementById('add-student-modal').classList.remove('hidden')" class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 whitespace-nowrap">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Siswa
        </button>
    </div>
</div>

<div class="mt-8">

<!-- Modal Tambah Siswa -->
<div id="add-student-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" style="background-color: rgba(15, 23, 42, 0.5); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" onclick="document.getElementById('add-student-modal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Tambah Siswa Baru</h3>
                <button onclick="document.getElementById('add-student-modal').classList.add('hidden')" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('students.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">NIS</label>
                    <input name="nis" value="{{ old('nis') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Masukkan NIS" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Nama Siswa</label>
                    <input name="name" value="{{ old('name') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Masukkan nama siswa" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Masukkan email" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Kelas</label>
                    <select name="class_name" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="X" @selected(old('class_name') === 'X')>X</option>
                        <option value="XI" @selected(old('class_name') === 'XI')>XI</option>
                        <option value="XII" @selected(old('class_name') === 'XII')>XII</option>
                    </select>
                </div>
                <!-- Jurusan hidden karena semua siswa TEI -->
                <input type="hidden" name="major" value="Teknik Elektronika Industri" />
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Tanggal Lahir</label>
                    <input name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Tipe NFC</label>
                    <select name="nfc_type" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="belum_terdaftar" @selected(old('nfc_type') === 'belum_terdaftar' || !old('nfc_type'))>Belum terdaftar</option>
                        <option value="kartu" @selected(old('nfc_type') === 'kartu')>Kartu</option>
                        <option value="handphone" @selected(old('nfc_type') === 'handphone')>Handphone</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">UID Kartu</label>
                    <input name="uid_kartu" value="{{ old('uid_kartu') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="UID kartu (opsional)" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">No Telepon</label>
                    <input name="phone" value="{{ old('phone') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="No telepon" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Username</label>
                    <input name="username" value="{{ old('username') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Username (opsional)" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Password</label>
                    <div class="relative w-full">
                        <input name="password" id="add-student-password" type="password" class="w-full rounded-xl border border-slate-300 pl-3 pr-10 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Password (opsional)" />
                        <button type="button" onclick="togglePasswordVisibility('add-student-password', 'eye-icon-add')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700 focus:outline-none">
                            <svg id="eye-icon-add" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Siswa</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit Siswa -->
    <div id="edit-student-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" style="background-color: rgba(15, 23, 42, 0.5); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" onclick="closeEditModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Edit Siswa</h3>
                <button onclick="closeEditModal()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" id="edit-student-form" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-student-id">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">NIS</label>
                    <input name="nis" id="edit-nis" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="NIS" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Nama Siswa</label>
                    <input name="name" id="edit-name" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Nama siswa" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Email</label>
                    <input name="email" type="email" id="edit-email" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Email" required />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Kelas</label>
                    <select name="class_name" id="edit-class_name" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Jurusan</label>
                    <select name="major" id="edit-major" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="Teknik Elektronika Industri">Teknik Elektronika Industri</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Tanggal Lahir</label>
                    <input name="date_of_birth" type="date" id="edit-date_of_birth" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Status</label>
                    <select name="status" id="edit-status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak aktif</option>
                        <option value="lulus">Lulus</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Tipe NFC</label>
                    <select name="nfc_type" id="edit-nfc_type" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                        <option value="belum_terdaftar">Belum terdaftar</option>
                        <option value="kartu">Kartu</option>
                        <option value="handphone">Handphone</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">UID Kartu</label>
                    <input name="uid_kartu" id="edit-uid_kartu" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="UID kartu" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">No Telepon</label>
                    <input name="phone" id="edit-phone" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="No telepon" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Username</label>
                    <input name="username" id="edit-username" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Username (opsional)" />
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-slate-700 ml-1">Password Baru</label>
                    <div class="relative w-full">
                        <input name="password" id="edit-student-password" type="password" class="w-full rounded-xl border border-slate-300 pl-3 pr-10 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Password baru (opsional)" />
                        <button type="button" onclick="togglePasswordVisibility('edit-student-password', 'eye-icon-edit')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700 focus:outline-none">
                            <svg id="eye-icon-edit" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

    <div class="flex flex-col rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden min-w-0">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-normal break-words">Foto & Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-normal break-words">NIS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-normal break-words">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-normal break-words">Jurusan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-normal break-words">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-normal break-words">NFC</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-normal break-words">UID</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $statusClasses = [
                            'aktif' => 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800',
                            'tidak_aktif' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                            'lulus' => 'inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800',
                        ];
                        $nfcClasses = [
                            'kartu' => 'inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-medium text-sky-800',
                            'handphone' => 'inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800',
                            'belum_terdaftar' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                        ];
                    @endphp

                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 whitespace-normal break-words">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 font-semibold text-sky-600">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $student->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $student->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-sm text-slate-600 whitespace-normal break-words">{{ $student->nis }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-normal break-words">{{ $student->class_name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700 whitespace-normal break-words">{{ $student->major ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-normal break-words">
                                <span class="{{ $statusClasses[$student->status] ?? 'inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $student->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-normal break-words">
                                <span class="{{ $nfcClasses[$student->nfc_type] ?? 'inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $student->nfc_type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-sm text-slate-600 whitespace-normal break-words">{{ $student->uid_kartu ?? '-' }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <button onclick='openEditModal(@json($student->id), @json($student->nis), @json($student->name), @json($student->email ?? ""), @json($student->class_name), @json($student->major ?? ""), @json($student->date_of_birth?->format("Y-m-d") ?? ""), @json($student->status), @json($student->nfc_type), @json($student->uid_kartu ?? ""), @json($student->phone ?? ""), @json($student->username ?? ""))' class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Edit</button>
                                    <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-white px-3 py-1.5 text-xs font-medium text-red-700 shadow-sm transition-colors hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-center">
        {{ $students->links() }}
    </div>
</div>
<script>
function openEditModal(id, nis, name, email, className, major, dateOfBirth, status, nfcType, uidKartu, phone, username) {
    document.getElementById('edit-student-id').value = id;
    document.getElementById('edit-nis').value = nis;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-class_name').value = className;
    document.getElementById('edit-major').value = major;
    document.getElementById('edit-date_of_birth').value = dateOfBirth;
    document.getElementById('edit-status').value = status;
    document.getElementById('edit-nfc_type').value = nfcType;
    document.getElementById('edit-uid_kartu').value = uidKartu;
    document.getElementById('edit-phone').value = phone;
    document.getElementById('edit-username').value = username;
    
    const form = document.getElementById('edit-student-form');
    form.action = '/siswa/' + id;

    document.getElementById('edit-student-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('edit-student-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function togglePasswordVisibility(inputId, eyeIconId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById(eyeIconId);
    if (input && eyeIcon) {
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
            `;
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeEditModal();
        document.getElementById('add-student-modal').classList.add('hidden');
    }
});

// Close modal when clicking outside
document.getElementById('edit-student-modal')?.addEventListener('mousedown', function(e) {
    // Only close if click is exactly on the backdrop/outer overlay, not the content container
    if (e.target === this || e.target.classList.contains('absolute')) {
        closeEditModal();
    }
});

document.getElementById('add-student-modal')?.addEventListener('mousedown', function(e) {
    if (e.target === this || e.target.classList.contains('absolute')) {
        this.classList.add('hidden');
    }
});
</script>
@endsection
