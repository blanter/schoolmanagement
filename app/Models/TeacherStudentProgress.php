<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherStudentProgress extends Model
{
    use HasFactory;

    protected $table = 'teacher_student_progress';

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'student_ids',
        'subject',
        'score',
        'description'
    ];

    protected $casts = [
        'student_ids' => 'array'
    ];
}
