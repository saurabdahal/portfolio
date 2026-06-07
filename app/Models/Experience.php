<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['title', 'company', 'year', 'description', 'order', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
}
