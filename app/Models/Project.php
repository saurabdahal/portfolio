<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'category', 'description', 'image', 'url', 'order', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
}
