<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherProcurement extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'semester',
        'tanggal',
        'tipe',
        'nominal',
        'nama_barang',
        'bukti_pembayaran',
        'url'
    ];
}
