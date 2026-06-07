<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = ['name', 'url', 'order', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
}
