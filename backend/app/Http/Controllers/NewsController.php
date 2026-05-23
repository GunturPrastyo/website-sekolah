<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => NewsResource::collection($news)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string'
        ]);

        $validated['images'] = $this->handleBase64Images($validated['images'] ?? []);
        $validated['views'] = 0;

        $news = News::create($validated);

        return response()->json(['message' => 'Berita berhasil ditambahkan', 'data' => new NewsResource($news)], 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string'
        ]);

        $validated['images'] = $this->handleBase64Images($validated['images'] ?? []);
        $news->update($validated);

        return response()->json(['message' => 'Berita berhasil diperbarui', 'data' => new NewsResource($news)]);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return response()->json(['message' => 'Berita berhasil dihapus']);
    }

    private function handleBase64Images(array $images)
    {
        $processedImages = [];
        foreach ($images as $image) {
            if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
                $imageData = substr($image, strpos($image, ',') + 1);
                $imageData = base64_decode($imageData);
                $extension = strtolower($type[1]);
                $filename = 'news/' . time() . '_' . uniqid() . '.' . $extension;
                Storage::disk('public')->put($filename, $imageData);
                
                $processedImages[] = asset('storage/' . $filename);
            } else {
                $processedImages[] = $image;
            }
        }
        return $processedImages;
    }
}