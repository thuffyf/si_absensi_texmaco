<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid_kartu',
        'device_id',
        'status',
        'response_message',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(NfcDevice::class);
    }
}
