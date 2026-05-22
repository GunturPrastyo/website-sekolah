<?php

namespace App\Http\Controllers;

use App\Models\PancasilaProfile;
use Illuminate\Http\Request;

class PancasilaProfileController extends Controller
{
    public function show()
    {
        $profile = PancasilaProfile::first();
        
        if (!$profile) {
            // Mengembalikan format default jika belum ada data agar frontend tidak bermasalah
            return response()->json(['data' => [
                'title' => 'Profil Pelajar Pancasila',
                'description' => 'Kurikulum kami berfokus pada pembentukan karakter siswa yang berlandaskan 6 dimensi Profil Pelajar Pancasila.',
                'dimensions' => []
            ]]);
        }
        
        return response()->json(['data' => $profile]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'dimensions' => 'required|array',
        ]);

        $profile = PancasilaProfile::first();
        
        if ($profile) {
            $profile->update($validated);
        } else {
            $profile = PancasilaProfile::create($validated);
        }

        return response()->json([
            'message' => 'Profil Pelajar Pancasila berhasil diperbarui',
            'data' => $profile
        ]);
    }
}