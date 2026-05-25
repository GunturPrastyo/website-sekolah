<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'images',
        'content',
        'tags',
        'views',
        'user_id',
        'status',
        'rejection_note'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}