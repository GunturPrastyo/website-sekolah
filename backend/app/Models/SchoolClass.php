<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade',
        'major',
        'homeroom_id',
        'capacity',
    ];

    public function homeroom()
    {
        return $this->belongsTo(Staff::class, 'homeroom_id');
    }
}