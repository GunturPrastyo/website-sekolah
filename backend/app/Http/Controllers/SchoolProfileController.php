<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use App\Http\Resources\SchoolProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageUploadTrait;

class SchoolProfileController extends Controller
{
    use ImageUploadTrait;

    public function show()
    {
        $profile = SchoolProfile::first();
        
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json(['data' => new SchoolProfileResource($profile)]);
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
            $oldPath = $profile && $profile->image && str_starts_with($profile->image, '/storage/profile/') ? str_replace('/storage/', '', $profile->image) : null;
            $filename = $this->processAndSaveImage($request->input('image'), 'profile', $oldPath);

            $validatedData['image'] = '/storage/' . $filename;
        }

        if (!$profile) {
            $profile = SchoolProfile::create($validatedData);
        } else {
            $profile->update($validatedData);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new SchoolProfileResource($profile)
        ]);
    }
}
