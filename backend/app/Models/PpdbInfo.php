<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbInfo extends Model
{
    protected $fillable = [
        'opening_date',
        'syarat',
        'alur',
        'jalur',
        'faqs',
        'brosur_path'
    ];

    protected $casts = [
        'opening_date' => 'datetime',
        'syarat' => 'array',
        'alur' => 'array',
        'jalur' => 'array',
        'faqs' => 'array',
    ];
}