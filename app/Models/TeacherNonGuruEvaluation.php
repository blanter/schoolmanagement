<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherNonGuruEvaluation extends Model
{
    use HasFactory;

    protected $table = 'teacher_nonguru_evaluations';

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'title',
        'description',
    ];
}
