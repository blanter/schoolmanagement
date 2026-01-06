<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLifebook extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'goals_monthly',
        'life_aspects',
        'vision_yearly',
        'vision_progress',
        'gratitude'
    ];
}
