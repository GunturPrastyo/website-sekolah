<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MapLocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        
        $data = parent::toArray($request);

        if (!empty($data['institutions'])) {
            $institutions = is_string($data['institutions']) 
                ? json_decode($data['institutions'], true) 
                : $data['institutions'];

            foreach ($institutions as &$inst) {
                if (!empty($inst['logo'])) {
        
                    if (!str_starts_with($inst['logo'], 'data:image') && !str_starts_with($inst['logo'], 'http')) {
                        
                        $inst['logo'] = asset('storage/' . $inst['logo']);
                        
                    }
                }
            }

            $data['institutions'] = $institutions;
        }

        return $data;
    }
}