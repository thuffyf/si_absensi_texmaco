@extends('layouts.app')

@section('title', 'Edit Alat NFC â€” SITEXA Absensi')
@section('page_title', 'Edit Alat NFC')

@section('content')
<div class="mb-6">
    <a href="{{ route('devices.nfc-tools') }}" class="btn-secondary text-sm">Kembali</a>
</div>

<div class="glass-card p-6 rounded-2xl">
    <h2 class="text-lg font-bold text-white mb-4">Perbarui Alat NFC</h2>

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('devices.update', $device) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @csrf
        @method('PUT')
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Nama Alat</label>
            <input name="name" value="{{ old('name', $device->name) }}" class="input-field" placeholder="Nama alat" required />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Lokasi</label>
            <input name="location" value="{{ old('location', $device->location) }}" class="input-field" placeholder="Lokasi" />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">IP Address</label>
            <input name="ip_address" value="{{ old('ip_address', $device->ip_address) }}" class="input-field" placeholder="IP Address" />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Status</label>
            <select name="status" class="input-field text-sm" required>
                <option value="online" @selected(old('status', $device->status) === 'online')>Online</option>
                <option value="idle" @selected(old('status', $device->status) === 'idle')>Idle</option>
                <option value="offline" @selected(old('status', $device->status) === 'offline')>Offline</option>
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Scan Hari Ini</label>
            <input name="scan_today" type="number" min="0" value="{{ old('scan_today', $device->scan_today) }}" class="input-field" placeholder="Scan hari ini" />
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-white ml-1">Success Rate (%)</label>
            <input name="success_rate" type="number" min="0" max="100" step="0.01" value="{{ old('success_rate', $device->success_rate) }}" class="input-field" placeholder="Success rate (%)" />
        </div>
        <button type="submit" class="btn-primary col-span-1 md:col-span-2 lg:col-span-4">Simpan Perubahan</button>
    </form>
</div>
@endsection
