<?php

namespace App\Http\Controllers;

use App\Models\VisionMission;
use Illuminate\Http\Request;

class VisionMissionController extends Controller
{
    /**
     * Menampilkan data Visi, Misi, dan Sambutan ke Vue.
     */
    public function index()
    {
        $visionMission = VisionMission::first();
        
        return response()->json([
            'success' => true,
            'data' => $visionMission
        ]);
    }

    /**
     * Menyimpan atau mengupdate data Visi, Misi, dan Sambutan dari Vue.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'vision' => 'required|string',
            'missions' => 'required|array',
            'principal_speech' => 'nullable|string',
            'principal_id' => 'nullable|exists:staff,id' // memastikan ID staf valid
        ]);

        $visionMission = VisionMission::updateOrCreate(
            ['id' => 1], // Selalu simpan di row ID 1 (karena data tunggal profil sekolah)
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Data Visi, Misi, dan Sambutan berhasil disimpan.',
            'data' => $visionMission
        ]);
    }
}