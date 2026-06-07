<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = ['email', 'phone', 'location', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
}
