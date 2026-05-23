<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlumniResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'nisn' => $this->student->nisn ?? '',
            'name' => $this->student->name ?? '',
            'year' => $this->graduation_year,
            'status' => $this->career_status,
            'instansi' => $this->institution,
        ];
    }
}