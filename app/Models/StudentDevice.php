<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'device_token',
        'device_label',
        'platform',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
