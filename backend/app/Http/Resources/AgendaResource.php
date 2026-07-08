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
        
        if ($attachment && !Str::startsWith($attachment, ['http://', 'https://', 'data:'])) {
            
            $attachment = asset('storage/' . $attachment);
        }

        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'startDate'  => $this->start_date,
            'endDate'    => $this->end_date,
            'time'       => $this->time,
            'location'   => $this->location,
            'color'      => $this->color,
            'attachment' => $attachment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Mengubah data dari request (camelCase) menjadi format untuk model (snake_case).
     *
     * @param  array  $validatedData
     * @return array
     */
    public static function forModel(array $validatedData): array
    {
        $data = [
            'title' => $validatedData['title'],
            'start_date' => $validatedData['startDate'],
            'end_date' => $validatedData['endDate'] ?? null,
            'time' => $validatedData['time'] ?? null,
            'location' => $validatedData['location'] ?? null,
            'color' => $validatedData['color'],
        ];

        // Hanya tambahkan attachment jika ada di data yang divalidasi
        if (array_key_exists('attachment', $validatedData)) {
            $data['attachment'] = $validatedData['attachment'];
        }

        return $data;
    }
}