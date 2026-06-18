<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'request_date',
        'rejection_reason',
        'photo',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
        'request_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function photoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (empty($this->photo)) {
                return null;
            }
            return PublicStorageUrl::get($this->photo);
        });
    }
}
