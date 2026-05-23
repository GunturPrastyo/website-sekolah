<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AgendaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $attachment = $this->attachment;
        if ($attachment && !Str::startsWith($attachment, ['http://', 'https://'])) {
            $attachment = url($attachment);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'time' => $this->time,
            'location' => $this->location,
            'color' => $this->color,
            'attachment' => $attachment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}