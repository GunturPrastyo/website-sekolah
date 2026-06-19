<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if ($this->image && !str_starts_with($this->image, 'http')) {
            // Hilangkan kata '/storage/' atau 'storage/' jika ada di awal, lalu jahit pakai url()
            $cleanPath = str_replace(['/storage/', 'storage/'], '', $this->image);
            $data['image'] = url('storage/' . ltrim($cleanPath, '/'));
        }

        return $data;
    }
}