<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        // Cek jika field images ada isinya
        if (!empty($this->images)) {
            // Ubah string/array menjadi array murni agar aman diproses loop
            $imageArray = is_string($this->images) ? json_decode($this->images, true) : $this->images;
            
            // Pastikan hasil decode berhasil atau fallback ke array data asli jika sudah ter-cast
            if (!is_array($imageArray)) {
                $imageArray = (array) $this->images;
            }

            $data['images'] = array_map(function($image) {
                // Bersihkan teks localhost atau jalur storage lama jika ada yang nyangkut
                $cleanPath = str_replace('http://localhost:8000/storage/', '', $image);
                $cleanPath = str_replace('storage/', '', $cleanPath);
                
                // Bersihkan sisa karakter bungkus JSON string jika ada
                $cleanPath = trim($cleanPath, '[]" ');

                // Kembalikan URL dinamis menggunakan helper url() bawaan domain VPS saat ini
                return url('storage/' . $cleanPath);
            }, $imageArray);
        }

        return $data;
    }
}