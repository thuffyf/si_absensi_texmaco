<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'device_id',
        'schedule_id',
        'attendance_date',
        'attendance_time',
        'status',
        'note',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'attendance_time' => 'datetime:H:i',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function device()
    {
        return $this->belongsTo(NfcDevice::class, 'device_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
