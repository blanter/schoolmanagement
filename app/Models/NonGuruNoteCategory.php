<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonGuruNoteCategory extends Model
{
    protected $fillable = ['user_id', 'title', 'color'];

    public function items()
    {
        return $this->hasMany(NonGuruNoteItem::class, 'category_id');
    }
}
