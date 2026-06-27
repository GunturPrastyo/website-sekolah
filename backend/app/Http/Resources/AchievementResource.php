<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $image = $this->image;
        if ($image && !Str::startsWith($image, ['http://', 'https://'])) {
            $image = url('storage/' . $image);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'studentName' => $this->student_name,
            'category' => $this->category,
            'level' => $this->level,
            'rank' => $this->rank,
            'year' => $this->year,
            'description' => $this->description,
            'internalNewsId' => $this->internal_news_id,
            'externalNewsUrl' => $this->external_news_url,
            'image' => $image,
        ];
    }
}