<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonGuruNoteItem extends Model
{
    protected $fillable = ['category_id', 'content', 'is_checked'];

    protected $casts = [
        'is_checked' => 'boolean',
    ];
}
