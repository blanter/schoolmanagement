<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSkip extends Model
{
    protected $fillable = [
        'user_id', 'jenis', 'tipe', 'judul_task', 'proyek', 'tanggal'
    ];
}
