@extends('layouts.app')

@section('title', 'Alat NFC — SITEXA Absensi')
@section('page_title', 'Alat NFC')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">📡 Monitoring Alat NFC</h1>
            <p class="text-gray-400">Kelola dan monitor status alat tap-in NFC di berbagai lokasi</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $totalDevices = $devices->count();
    $onlineCount = $devices->where('status', 'online')->count();
    $idleCount = $devices->where('status', 'idle')->count();
    $offlineCount = $devices->where('status', 'offline')->count();
@endphp

<div class="glass-card p-6 rounded-2xl mb-6">
    <h2 class="text-lg font-bold text-white mb-4">Tambah Alat NFC</h2>
    <form method="POST" action="{{ route('devices.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @csrf
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Nama Alat</label>
            <input name="name" value="{{ old('name') }}" class="input-field" placeholder="Nama alat" required />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Lokasi</label>
            <input name="location" value="{{ old('location') }}" class="input-field" placeholder="Lokasi" />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">IP Address</label>
            <input name="ip_address" value="{{ old('ip_address') }}" class="input-field" placeholder="IP Address" />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Status</label>
            <select name="status" class="input-field text-sm" required>
                <option value="online" @selected(old('status') === 'online')>Online</option>
                <option value="idle" @selected(old('status') === 'idle')>Idle</option>
                <option value="offline" @selected(old('status') === 'offline')>Offline</option>
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Scan Hari Ini</label>
            <input name="scan_today" type="number" min="0" value="{{ old('scan_today') }}" class="input-field" placeholder="Scan hari ini" />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Success Rate (%)</label>
            <input name="success_rate" type="number" min="0" max="100" step="0.01" value="{{ old('success_rate') }}" class="input-field" placeholder="Success rate (%)" />
        </div>
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-4">Simpan Alat</button>
    </form>
</div>

<div class="glass-card p-6 rounded-2xl mb-6">
    <form method="GET" action="{{ route('devices.nfc-tools') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select name="status" class="input-field text-sm">
            <option value="">Semua Status</option>
            <option value="online" @selected(request('status') === 'online')>Online</option>
            <option value="idle" @selected(request('status') === 'idle')>Idle</option>
            <option value="offline" @selected(request('status') === 'offline')>Offline</option>
        </select>
        <div class="flex gap-2">
            <button class="btn-secondary text-sm" type="submit">Terapkan Filter</button>
            <a href="{{ route('devices.nfc-tools') }}" class="btn-secondary text-sm">Reset</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in">
    <div class="stat-card">
        <p class="stat-label">Total Alat</p>
        <div class="stat-number">{{ $totalDevices }}</div>
        <p class="text-xs text-emerald-400 mt-2">Aktif</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Online</p>
        <div class="stat-number">{{ $onlineCount }}</div>
        <p class="text-xs text-emerald-400 mt-2">{{ $totalDevices ? round(($onlineCount / $totalDevices) * 100) : 0 }}% Aktif</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Idle</p>
        <div class="stat-number">{{ $idleCount }}</div>
        <p class="text-xs text-yellow-400 mt-2">Perlu perhatian</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Offline</p>
        <div class="stat-number">{{ $offlineCount }}</div>
        <p class="text-xs text-red-400 mt-2">Perlu maintenance</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @php
        $statusClasses = [
            'online' => 'text-emerald-400',
            'idle' => 'text-yellow-400',
            'offline' => 'text-red-400',
        ];
        $borderClasses = [
            'online' => 'border-emerald-500/30',
            'idle' => 'border-yellow-500/30',
            'offline' => 'border-red-500/30',
        ];
    @endphp

    @forelse($devices as $device)
        <div class="glass-card p-6 rounded-2xl border-2 {{ $borderClasses[$device->status] ?? 'border-neon-cyan/30' }} hover:border-neon-cyan/50 transition-all">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-white">{{ $device->name }}</h3>
                    <p class="text-xs text-gray-400">{{ $device->location ?? 'Lokasi belum diisi' }}</p>
                </div>
                <span class="flex items-center gap-1 {{ $statusClasses[$device->status] ?? 'text-gray-400' }} text-sm font-bold">
                    <span class="w-3 h-3 rounded-full bg-current"></span>
                    {{ ucfirst($device->status) }}
                </span>
            </div>

            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-2xl mb-4">
                📡
            </div>

            <div class="space-y-3 mb-4 pb-4 border-b border-neon-cyan/10">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">IP Address</span>
                    <span class="text-neon-cyan font-mono">{{ $device->ip_address ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Last Seen</span>
                    <span class="text-white font-semibold">{{ optional($device->last_seen_at)->diffForHumans() ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Last Scan</span>
                    <span class="text-white font-semibold">{{ optional($device->last_scan_at)->diffForHumans() ?? '-' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-4">
                <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-400">Scan Hari Ini</p>
                    <p class="text-lg font-bold text-neon-cyan">{{ $device->scan_today }}</p>
                </div>
                <div class="bg-glass-light/10 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-400">Success Rate</p>
                    <p class="text-lg font-bold text-emerald-400">{{ number_format($device->success_rate, 1) }}%</p>
                </div>
            </div>

            <div class="flex gap-2">
                <a class="btn-secondary text-xs flex-1" href="{{ route('devices.edit', $device) }}">✏️ Edit</a>
                <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('Hapus alat ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn-icon text-xs" type="submit">🗑️</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center text-sm text-gray-400">Belum ada alat NFC.</div>
    @endforelse
</div>
@endsection
