<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'role',
        'subject',
        'phone',
        'date_of_birth',
        'status',
        'photo_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
