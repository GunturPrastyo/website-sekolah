<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        
        foreach($galleries as $gallery) {
            if ($gallery->image && !str_starts_with($gallery->image, 'http') && !str_starts_with($gallery->image, 'data:image')) {
                $gallery->image = asset('storage/' . $gallery->image);
            }
        }
        
        return response()->json(['data' => $galleries]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'images' => 'required|array|min:1',
            'images.*' => 'required|string',
        ]);

        $createdGalleries = [];

        foreach ($validatedData['images'] as $imgData) {
            $imagePath = $imgData;
            
            if (str_starts_with($imgData, 'data:image')) {
                preg_match('/data:image\/(\w+);base64,/', $imgData, $type);
                $imageData = base64_decode(substr($imgData, strpos($imgData, ',') + 1));
                $filename = 'gallery/' . time() . '_' . uniqid() . '.' . ($type[1] ?? 'jpg');
                Storage::disk('public')->put($filename, $imageData);
                $imagePath = $filename;
            }

            $gallery = Gallery::create([
                'title' => $validatedData['title'],
                'category' => $validatedData['category'],
                'image' => $imagePath,
                'likes' => 0
            ]);

            if (!str_starts_with($gallery->image, 'http') && !str_starts_with($gallery->image, 'data:image')) {
                $gallery->image = asset('storage/' . $gallery->image);
            }

            $createdGalleries[] = $gallery;
        }

        return response()->json(['data' => $createdGalleries], 201);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'images' => 'required|array|min:1',
            'images.*' => 'required|string',
        ]);

        $createdGalleries = [];

        foreach ($validatedData['images'] as $index => $imgData) {
            $imagePath = $imgData;
            $isNewFile = false;

            if (str_starts_with($imgData, 'data:image')) {
                preg_match('/data:image\/(\w+);base64,/', $imgData, $type);
                $imageData = base64_decode(substr($imgData, strpos($imgData, ',') + 1));
                $filename = 'gallery/' . time() . '_' . uniqid() . '.' . ($type[1] ?? 'jpg');
                Storage::disk('public')->put($filename, $imageData);
                $imagePath = $filename;
                $isNewFile = true;
            } elseif (str_starts_with($imgData, 'http')) {
                $baseUrl = asset('storage/') . '/';
                if (str_starts_with($imgData, $baseUrl)) {
                    $imagePath = str_replace($baseUrl, '', $imgData);
                }
            }

            // Update the existing row with the first image, create new rows for additional images
            if ($index === 0) {
                if ($isNewFile && $gallery->image && !str_starts_with($gallery->image, 'http')) {
                    Storage::disk('public')->delete($gallery->image);
                }

                $gallery->update([
                    'title' => $validatedData['title'],
                    'category' => $validatedData['category'],
                    'image' => $imagePath
                ]);

                if (!str_starts_with($gallery->image, 'http') && !str_starts_with($gallery->image, 'data:image')) {
                    $gallery->image = asset('storage/' . $gallery->image);
                }
                
                $createdGalleries[] = $gallery;
            } else {
                $newGallery = Gallery::create([
                    'title' => $validatedData['title'],
                    'category' => $validatedData['category'],
                    'image' => $imagePath,
                    'likes' => 0
                ]);
                
                if (!str_starts_with($newGallery->image, 'http') && !str_starts_with($newGallery->image, 'data:image')) {
                    $newGallery->image = asset('storage/' . $newGallery->image);
                }
                
                $createdGalleries[] = $newGallery;
            }
        }

        return response()->json(['data' => $createdGalleries]);
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
            Storage::disk('public')->delete($gallery->image);
        }
        $gallery->delete();

        return response()->json(['message' => 'Gallery deleted successfully']);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id'
        ]);

        $galleries = Gallery::whereIn('id', $validated['ids'])->get();

        foreach ($galleries as $gallery) {
            if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
                Storage::disk('public')->delete($gallery->image);
            }
            $gallery->delete();
        }

        return response()->json(['message' => 'Galleries deleted successfully']);
    }
}