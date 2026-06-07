<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = ['bio', 'cv_path', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
}
