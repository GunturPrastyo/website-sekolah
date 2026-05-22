<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'staff_id',
        'curriculum_subject_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function curriculumSubject()
    {
        return $this->belongsTo(CurriculumSubject::class);
    }
}