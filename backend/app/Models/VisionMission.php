<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisionMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'vision',
        'missions',
        'principal_speech',
        'principal_id'
    ];

    protected $casts = [
        // Mengubah format JSON di database menjadi tipe Array otomatis di Laravel
        'missions' => 'array', 
    ];

    /**
     * Relasi ke tabel staff (Guru & Staf) untuk mengambil data Kepala Sekolah
     */
    public function principal()
    {
        return $this->belongsTo(Staff::class, 'principal_id');
    }
}