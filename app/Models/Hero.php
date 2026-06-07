<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    protected $table = 'hero';
    protected $fillable = ['name', 'title', 'tagline', 'photo', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
}
