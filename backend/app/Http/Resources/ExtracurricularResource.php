<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtracurricularResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        
        if ($this->image && !str_starts_with($this->image, 'http') && !str_starts_with($this->image, 'data:')) {
            $data['image'] = asset('storage/' . $this->image);
        }
        
        return $data;
    }
}