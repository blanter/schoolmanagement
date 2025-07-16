<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPs extends Model
{
    use HasFactory;

    protected $fillable = [
        'zona_id',
        'jenis_ps',
        'tarif',
    ];
}
