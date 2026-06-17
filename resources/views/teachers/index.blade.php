@extends('layouts.app')

@section('title', 'Data Guru — SITEXA Absensi')
@section('page_title', 'Data Guru')
@section('page_subtitle', 'Kelola data guru dan monitoring kehadiran')

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

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap gap-2">
            <form method="GET" action="{{ route('teachers.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" class="w-64 rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Cari guru..." />
                <button type="submit" class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Cari
                </button>
                <a href="{{ route('teachers.index') }}" class="flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Reset</a>
            </form>
        </div>
        <button onclick="document.getElementById('add-teacher-modal').classList.remove('hidden')" class="flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 whitespace-nowrap">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Guru
        </button>
    </div>
</div>

<!-- Modal Tambah Guru -->
<!-- Modal Tambah Guru -->
<div id="add-teacher-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/50" onclick="document.getElementById('add-teacher-modal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Tambah Guru Baru</h3>
                <button onclick="document.getElementById('add-teacher-modal').classList.add('hidden')" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('teachers.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <input name="nip" value="{{ old('nip') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="NIP" required />
                <input name="name" value="{{ old('name') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Nama guru" required />
                <input name="email" value="{{ old('email') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Email" />
                <input name="subject" value="{{ old('subject') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Mata pelajaran" />
                <input name="role" value="{{ old('role') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Role" />
                <input name="phone" value="{{ old('phone') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="No telepon" />
                <input name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
                <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="cuti" @selected(old('status') === 'cuti')>Cuti</option>
                    <option value="non_aktif" @selected(old('status') === 'non_aktif')>Non Aktif</option>
                </select>
                <input name="password" type="password" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Password (opsional)" />
                <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Guru</button>
            </form>
        </div>
    </div>

    <div class="mt-6 flex flex-col rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden min-w-0">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Foto & Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">NIP</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $statusClasses = [
                            'aktif' => 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800',
                            'cuti' => 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800',
                            'non_aktif' => 'inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800',
                        ];
                    @endphp

                    @forelse($teachers as $teacher)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 font-semibold text-sky-600">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $teacher->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $teacher->role ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-sm text-slate-600">{{ $teacher->nip }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $teacher->subject ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $teacher->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $teacher->phone ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $statusClasses[$teacher->status] ?? 'inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $teacher->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="openEditModal({{ $teacher->id }}, '{{ $teacher->nip }}', '{{ $teacher->name }}', '{{ $teacher->email ?? '' }}', '{{ $teacher->subject ?? '' }}', '{{ $teacher->role ?? '' }}', '{{ $teacher->phone ?? '' }}', '{{ $teacher->date_of_birth?->toDateString() ?? '' }}', '{{ $teacher->status }}')" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">Edit</button>
                                    <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('Hapus guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-white px-3 py-1.5 text-xs font-medium text-red-700 shadow-sm transition-colors hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-center">
        {{ $teachers->links() }}
    </div>
</div>

<!-- Modal Edit Guru -->
<div id="edit-teacher-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/50" onclick="closeEditModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative mx-4 w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Edit Guru</h3>
            <button onclick="closeEditModal()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" id="edit-teacher-form" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-teacher-id">
            <input name="nip" id="edit-nip" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="NIP" required />
            <input name="name" id="edit-name" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Nama guru" required />
            <input name="email" id="edit-email" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Email" />
            <input name="subject" id="edit-subject" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Mata pelajaran" />
            <input name="role" id="edit-role" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Role" />
            <input name="phone" id="edit-phone" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="No telepon" />
            <input name="date_of_birth" type="date" id="edit-date_of_birth" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
            <select name="status" id="edit-status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                <option value="aktif">Aktif</option>
                <option value="cuti">Cuti</option>
                <option value="non_aktif">Non Aktif</option>
            </select>
            <input name="password" type="password" class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Password (opsional)" />
            <button type="submit" class="col-span-1 md:col-span-2 flex w-full items-center justify-center rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
function openEditModal(id, nip, name, email, subject, role, phone, dateOfBirth, status) {
    document.getElementById('edit-teacher-id').value = id;
    document.getElementById('edit-nip').value = nip;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-subject').value = subject;
    document.getElementById('edit-role').value = role;
    document.getElementById('edit-phone').value = phone;
    document.getElementById('edit-date_of_birth').value = dateOfBirth;
    document.getElementById('edit-status').value = status;
    
    const form = document.getElementById('edit-teacher-form');
    form.action = '/teachers/' + id;
    
    document.getElementById('edit-teacher-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('edit-teacher-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
