<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherResearchProject extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'semester',
        'judul_check',
        'rumusan_check',
        'penelitian_check',
        'kesimpulan_check',
        'research_link',
    ];

    protected $casts = [
        'judul_check' => 'boolean',
        'rumusan_check' => 'boolean',
        'penelitian_check' => 'boolean',
        'kesimpulan_check' => 'boolean',
    ];
}
