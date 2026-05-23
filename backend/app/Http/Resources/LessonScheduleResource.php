<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'class_id' => $this->school_class_id,
            'className' => $this->schoolClass->name ?? 'Tidak Diketahui',
            'day' => $this->day,
            'startTime' => substr($this->start_time, 0, 5),
            'endTime' => substr($this->end_time, 0, 5),
            'subject_id' => $this->curriculum_subject_id,
            'subject' => $this->curriculumSubject->name ?? 'Tidak Diketahui',
            'teacher_id' => $this->staff_id,
            'teacher' => $this->staff->name ?? 'Tidak Diketahui',
        ];
    }
}