<?php

namespace App\Http\Controllers;

use App\Models\NfcDevice;
use Illuminate\Http\Request;

class NfcDeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = NfcDevice::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $devices = $query->orderBy('name')->get();

        return view('devices.nfc-tools', compact('devices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'location' => 'nullable|string|max:120',
            'ip_address' => 'nullable|string|max:45',
            'status' => 'required|in:online,idle,offline',
            'scan_today' => 'nullable|integer|min:0',
            'success_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        NfcDevice::create($data);

        return back()->with('success', 'Alat NFC berhasil ditambahkan.');
    }

    public function edit(NfcDevice $device)
    {
        return view('devices.edit', compact('device'));
    }

    public function update(Request $request, NfcDevice $device)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'location' => 'nullable|string|max:120',
            'ip_address' => 'nullable|string|max:45',
            'status' => 'required|in:online,idle,offline',
            'scan_today' => 'nullable|integer|min:0',
            'success_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $device->update($data);

        return redirect()->route('devices.nfc-tools')->with('success', 'Alat NFC berhasil diperbarui.');
    }

    public function destroy(NfcDevice $device)
    {
        $device->delete();

        return back()->with('success', 'Alat NFC berhasil dihapus.');
    }
}
