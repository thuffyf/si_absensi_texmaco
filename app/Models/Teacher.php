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
        'api_token',
        'role',
        'subject',
        'phone',
        'date_of_birth',
        'status',
        'photo_path',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
