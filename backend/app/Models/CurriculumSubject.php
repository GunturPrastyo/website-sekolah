<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'program_id',
        'category',
        'name',
        'icon',
        'color',
        'desc',
        'topics',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}