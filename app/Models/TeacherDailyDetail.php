<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherDailyDetail extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'note'
    ];
}
