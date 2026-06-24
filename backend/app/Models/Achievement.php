<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'student_name', 'category', 'level', 'year', 'description', 'image',
        'internal_news_id', 
        'external_news_url', 
    ];
}