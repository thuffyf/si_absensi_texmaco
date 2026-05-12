<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'name',
        'username',
        'email',
        'password',
        'date_of_birth',
        'class_name',
        'major',
        'status',
        'nfc_type',
        'uid_kartu',
        'phone',
        'photo_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
