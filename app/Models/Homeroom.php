<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homeroom extends Model
{
    protected $fillable = [
        'class_name',
        'homeroom_teacher_name',
    ];
}
