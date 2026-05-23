<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurriculumSubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'grade' => $this->grade,
            'program_id' => $this->program_id,
            'program' => $this->whenLoaded('program'),
            'category' => $this->category,
            'name' => $this->name,
            'icon' => $this->icon,
            'color' => $this->color,
            'desc' => $this->desc,
            'topics' => $this->topics,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}