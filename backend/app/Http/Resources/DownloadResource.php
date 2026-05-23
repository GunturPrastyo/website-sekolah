<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['date'] = $this->created_at ? $this->created_at->format('d M Y') : null;
        $data['file_url'] = asset('storage/' . $this->file_path);
        return $data;
    }
}