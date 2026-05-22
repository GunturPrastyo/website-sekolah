<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PancasilaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'dimensions',
    ];

    protected $casts = [
        'dimensions' => 'array',
    ];
}