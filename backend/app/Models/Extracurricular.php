<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'image', 'schedule', 'story', 'pembina', 'members', 'socials'
    ];

    protected $casts = [
        'socials' => 'array',
    ];
}