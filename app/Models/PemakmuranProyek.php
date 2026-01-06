<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemakmuranProyek extends Model
{
    protected $fillable = ['user_id', 'year', 'month', 'content'];
}
