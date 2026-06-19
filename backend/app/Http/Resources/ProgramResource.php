<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // 1. Ambil semua data bawaan asli dari database
        $data = parent::toArray($request);

        // 2. Tentukan nama kolom gambar di tabel program kamu (misal: 'image' atau 'cover_image')
        // Kita cek satu per satu, mana kolom yang aktif di tabelmu
        $imageFields = ['image', 'cover_image', 'thumbnail'];

        foreach ($imageFields as $field) {
            if (!empty($data[$field])) {
                // Cabut paksa domain localhost jika ada yang tersangkut di baris database
                $cleanPath = str_replace('http://localhost:8000/storage/', '', $data[$field]);
                $cleanPath = str_replace('storage/', '', $cleanPath);
                
                // Bersihkan sisa karakter bungkus jika datanya tidak sengaja tersimpan sebagai array/JSON string
                $cleanPath = trim($cleanPath, '[]" ');

                // Jahit ulang secara dinamis menggunakan domain aktif VPS saat ini
                $data[$field] = url('storage/' . $cleanPath);
            }
        }

        return $data;
    }
}