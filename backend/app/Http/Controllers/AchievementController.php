<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AchievementController extends Controller
{
    private function formatData($achievement)
    {
        $image = $achievement->image;
        if ($image && !Str::startsWith($image, ['http://', 'https://'])) {
            $image = url('storage/' . $image);
        }

        return [
            'id' => $achievement->id,
            'title' => $achievement->title,
            'studentName' => $achievement->student_name,
            'category' => $achievement->category,
            'level' => $achievement->level,
            'year' => $achievement->year,
            'description' => $achievement->description,
            'image' => $image,
        ];
    }

    public function index()
    {
        $achievements = Achievement::orderBy('created_at', 'desc')->get();
        
        $formatted = $achievements->map(function ($achievement) {
            return $this->formatData($achievement);
        });

        return response()->json([
            'success' => true,
            'data' => $formatted
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

        $imagePath = null;
        if ($request->filled('image') && !Str::startsWith($request->input('image'), ['http://', 'https://'])) {
            $imageData = $request->input('image');
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);
                $fileName = Str::random(10) . '.' . $type;
                $imagePath = 'achievements/' . $fileName;
                Storage::disk('public')->put($imagePath, $imageData);
            }
        } elseif ($request->filled('image')) {
            $imagePath = $request->input('image');
        }

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
            'data' => $this->formatData($achievement)
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

        $imagePath = $achievement->image;
        if ($request->filled('image') && $request->input('image') !== $achievement->image && !Str::startsWith($request->input('image'), ['http://', 'https://'])) {
            $imageData = $request->input('image');
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);
                $fileName = Str::random(10) . '.' . $type;
                $imagePath = 'achievements/' . $fileName;
                Storage::disk('public')->put($imagePath, $imageData);

                if ($achievement->image && !Str::startsWith($achievement->image, ['http://', 'https://'])) {
                    Storage::disk('public')->delete($achievement->image);
                }
            }
        } elseif ($request->filled('image') && Str::startsWith($request->input('image'), ['http://', 'https://'])) {
            $baseUrl = url('storage/');
            $incomingUrl = $request->input('image');
            if (Str::startsWith($incomingUrl, $baseUrl)) {
                $imagePath = str_replace($baseUrl . '/', '', $incomingUrl);
            } else {
                $imagePath = $incomingUrl;
            }
        } elseif (!$request->filled('image')) {
            if ($achievement->image && !Str::startsWith($achievement->image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($achievement->image);
            }
            $imagePath = null;
        }

        $achievement->update([
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
            'message' => 'Prestasi berhasil diperbarui',
            'data' => $this->formatData($achievement)
        ]);
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);

        if ($achievement->image && !Str::startsWith($achievement->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($achievement->image);
        }

        $achievement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil dihapus'
        ]);
    }
}