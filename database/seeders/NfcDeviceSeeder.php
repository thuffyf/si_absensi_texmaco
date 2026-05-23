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
                'name' => 'Ruang Lab TEI',
                'location' => 'Lab TEI',
                'ip_address' => '192.168.1.100',
                'status' => 'online',
                'last_seen_at' => $now,
                'last_scan_at' => $now,
                'scan_today' => 0,
                'success_rate' => 100,
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
