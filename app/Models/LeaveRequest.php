<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'requested_at',
        'responded_at',
        'response_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
