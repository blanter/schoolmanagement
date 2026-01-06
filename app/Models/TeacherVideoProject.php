<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherVideoProject extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'semester',
        'name',
        'link',
    ];
}
