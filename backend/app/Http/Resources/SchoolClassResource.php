<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['currentStudents'] = 0;
        return $data;
    }
}