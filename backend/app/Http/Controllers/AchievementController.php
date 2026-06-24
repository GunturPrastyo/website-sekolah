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
            'description' => 'nullable|string',
            'internalNewsId' => 'nullable|exists:news,id',
            'externalNewsUrl' => 'nullable|url|max:2048',
            'image' => 'nullable|string',
        ]);

        $imagePath = $this->processAndSaveImage($validated['image'] ?? null, 'achievements', null, 800);

        $achievement = Achievement::create([
            'title' => $validated['title'], // Keep existing fields
            'student_name' => $validated['studentName'], // Keep existing fields
            'category' => $validated['category'], // Keep existing fields
            'level' => $validated['level'], // Keep existing fields
            'year' => $validated['year'], // Keep existing fields
            'description' => $validated['description'], // Keep existing fields
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
            'description' => 'nullable|string',
            'internalNewsId' => 'nullable|exists:news,id',
            'externalNewsUrl' => 'nullable|url|max:2048',
            'image' => 'nullable|string',
        ]);

        if ($request->has('image')) {
            $validated['image'] = $this->processAndSaveImage(
                $request->input('image'), 'achievements', $achievement->image, 800
            );
        } else if ($achievement->image && !isset($validated['image'])) {
            $validated['image'] = $achievement->image; // Retain existing image if not explicitly removed
        }

        $achievement->update($validated);

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