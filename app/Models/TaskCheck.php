<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCheck extends Model
{
    protected $fillable = [
        'user_id', 'jenis', 'tipe', 'judul_task', 'tahun', 'bulan', 'proyek', 'link', 'tanggal'
    ];
}
