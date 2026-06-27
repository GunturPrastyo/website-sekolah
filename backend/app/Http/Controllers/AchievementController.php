<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Http\Resources\AchievementResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AchievementController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        $achievements = Achievement::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => AchievementResource::collection($achievements)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'studentName' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'year' => 'required|integer',
            'rank' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'internalNewsId' => 'nullable|exists:news,id',
            'externalNewsUrl' => 'nullable|url|max:2048',
            'image' => 'nullable|string',
        ]);

        $imagePath = $this->processAndSaveImage($validated['image'] ?? null, 'achievements', null, 800);

        $achievement = Achievement::create([
            'title' => $validated['title'],
            'student_name' => $validated['studentName'],
            'category' => $validated['category'],
            'level' => $validated['level'],
            'year' => $validated['year'],
            'rank' => $validated['rank'] ?? null,
            'description' => $validated['description'] ?? null,
            'internal_news_id' => $validated['internalNewsId'] ?? null, // Add new field
            'external_news_url' => $validated['externalNewsUrl'] ?? null, // Add new field
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil ditambahkan',
            'data' => new AchievementResource($achievement)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'studentName' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'year' => 'required|integer',
            'rank' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'internalNewsId' => 'nullable|exists:news,id',
            'externalNewsUrl' => 'nullable|url|max:2048',
            'image' => 'nullable|string',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'student_name' => $validated['studentName'],
            'category' => $validated['category'],
            'level' => $validated['level'],
            'year' => $validated['year'],
            'rank' => $validated['rank'] ?? null,
            'description' => $validated['description'] ?? null,
            'internal_news_id' => $validated['internalNewsId'] ?? null,
            'external_news_url' => $validated['externalNewsUrl'] ?? null,
        ];

        // Cek jika ada gambar baru (base64) yang diunggah
        if (!empty($validated['image']) && str_starts_with($validated['image'], 'data:image')) {
            $updateData['image'] = $this->processAndSaveImage(
                $validated['image'], 'achievements', $achievement->image, 800
            );
        } // Cek jika gambar dihapus (dikirim sebagai string kosong atau null)
        else if (empty($validated['image']) && $achievement->image) {
            $this->deleteOldImage($achievement->image);
            $updateData['image'] = null;
        }

        $achievement->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil diperbarui',
            'data' => new AchievementResource($achievement)
        ]);
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);

        $this->deleteOldImage($achievement->image);

        $achievement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil dihapus'
        ]);
    }
}