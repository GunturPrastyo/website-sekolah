<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    public function show()
    {
        $profile = SchoolProfile::first();
        
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json(['data' => $profile]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'npsn' => 'required|string|max:255',
            'accreditation' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'image' => 'nullable|string', // Validasi sebagai string karena menerima Base64
        ]);

        $profile = SchoolProfile::first();

        // Handle upload gambar jika dikirim dalam bentuk Base64 (dari cropImage vue)
        if ($request->filled('image') && preg_match('/^data:image\/(\w+);base64,/', $request->input('image'))) {
            $imageData = $request->input('image');
            $position = strpos($imageData, ',');
            $extension = explode(';', explode('/', $imageData)[1])[0];
            $imageData = base64_decode(substr($imageData, $position + 1));
            
            $filename = 'profile/school_profile_' . time() . '.' . $extension;
            Storage::disk('public')->put($filename, $imageData);
            
            // Hapus gambar lama agar tidak menumpuk di storage
            if ($profile && $profile->image && str_starts_with($profile->image, '/storage/profile/')) {
                $oldPath = str_replace('/storage/', '', $profile->image);
                Storage::disk('public')->delete($oldPath);
            }

            $validatedData['image'] = '/storage/' . $filename;
        }

        if (!$profile) {
            $profile = SchoolProfile::create($validatedData);
        } else {
            $profile->update($validatedData);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $profile
        ]);
    }
}
