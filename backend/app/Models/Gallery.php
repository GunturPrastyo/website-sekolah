<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'image',
        'likes',
        'status',
        'user_id',
        'rejection_note',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}