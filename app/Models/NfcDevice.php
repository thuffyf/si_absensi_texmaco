<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NfcDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'ip_address',
        'status',
        'last_seen_at',
        'last_scan_at',
        'scan_today',
        'success_rate',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'last_scan_at' => 'datetime',
        'success_rate' => 'decimal:2',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'device_id');
    }
}
