<?php

namespace Database\Seeders;

use App\Models\NfcDevice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class NfcDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $devices = [
            [
                'name' => 'Pintu Utama',
                'location' => 'Gate 1',
                'ip_address' => '192.168.1.100',
                'status' => 'online',
                'last_seen_at' => $now,
                'last_scan_at' => $now,
                'scan_today' => 245,
                'success_rate' => 100,
            ],
            [
                'name' => 'Pintu Belakang',
                'location' => 'Gate 2',
                'ip_address' => '192.168.1.101',
                'status' => 'online',
                'last_seen_at' => $now->copy()->subMinutes(1),
                'last_scan_at' => $now->copy()->subMinutes(2),
                'scan_today' => 189,
                'success_rate' => 98.9,
            ],
            [
                'name' => 'Kantor TU',
                'location' => 'Gate 3',
                'ip_address' => '192.168.1.102',
                'status' => 'idle',
                'last_seen_at' => $now->copy()->subMinutes(10),
                'last_scan_at' => $now->copy()->subMinutes(30),
                'scan_today' => 0,
                'success_rate' => 0,
            ],
            [
                'name' => 'Gudang',
                'location' => 'Gate 4',
                'ip_address' => '192.168.1.103',
                'status' => 'offline',
                'last_seen_at' => $now->copy()->subMinutes(12),
                'last_scan_at' => $now->copy()->subMinutes(12),
                'scan_today' => 0,
                'success_rate' => 0,
            ],
        ];

        foreach ($devices as $device) {
            NfcDevice::updateOrCreate(
                ['name' => $device['name']],
                $device
            );
        }
    }
}
