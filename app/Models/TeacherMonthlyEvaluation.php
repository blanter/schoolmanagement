<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherMonthlyEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'evaluasi',
        'student_progress',
        'review',
        'berhasil',
        'belum_berhasil',
        'tauladan',
    ];
}
