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
            'image' => 'nullable|string',
        ]);

        $imagePath = $this->processAndSaveImage($validated['image'] ?? null, 'achievements', null, 800);

        $achievement = Achievement::create([
            'title' => $validated['title'],
            'student_name' => $validated['studentName'],
            'category' => $validated['category'],
            'level' => $validated['level'],
            'year' => $validated['year'],
            'description' => $validated['description'],
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
            'image' => 'nullable|string',
        ]);

        if ($request->has('image')) {
            $validated['image'] = $this->processAndSaveImage(
                $request->input('image'), 'achievements', $achievement->image, 800
            );
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