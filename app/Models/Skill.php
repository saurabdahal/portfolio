<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'percentage', 'order', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
}
