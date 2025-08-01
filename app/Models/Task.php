<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'jenis', // days, week, month
        'tipe',  // guru, nonguru
        'judul_task',
        'proyek',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
