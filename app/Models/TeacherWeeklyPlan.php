<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherWeeklyPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'subject',
        'note'
    ];
}
