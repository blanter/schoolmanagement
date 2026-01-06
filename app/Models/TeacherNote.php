<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherNote extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'note'];
}
