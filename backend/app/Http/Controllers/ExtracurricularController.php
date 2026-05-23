<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::all()->map(function ($item) {
            if ($item->image && !str_starts_with($item->image, 'http') && !str_starts_with($item->image, 'data:')) {
                $item->image = asset($item->image);
            }
            return $item;
        });
        return response()->json([
            'data' => $extracurriculars
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|string',
            'schedule' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'story' => 'nullable|string',
            'pembina' => 'nullable|string|max:255',
            'members' => 'nullable|integer',
            'socials' => 'nullable|array',
        ]);

        if ($request->has('image') && preg_match('/^data:image\/(\w+);base64,/', $request->image)) {
            $validated['image'] = $this->saveBase64Image($request->image);
        }

        $extracurricular = Extracurricular::create($validated);

        if ($extracurricular->image && !str_starts_with($extracurricular->image, 'http') && !str_starts_with($extracurricular->image, 'data:')) {
            $extracurricular->image = asset($extracurricular->image);
        }

        return response()->json([
            'message' => 'Data ekstrakurikuler berhasil ditambahkan.',
            'data' => $extracurricular
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $extracurricular = Extracurricular::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|string',
            'schedule' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'story' => 'nullable|string',
            'pembina' => 'nullable|string|max:255',
            'members' => 'nullable|integer',
            'socials' => 'nullable|array',
        ]);

        if ($request->has('image') && preg_match('/^data:image\/(\w+);base64,/', $request->image)) {
            $validated['image'] = $this->saveBase64Image($request->image);
        }

        $extracurricular->update($validated);

        if ($extracurricular->image && !str_starts_with($extracurricular->image, 'http') && !str_starts_with($extracurricular->image, 'data:')) {
            $extracurricular->image = asset($extracurricular->image);
        }

        return response()->json([
            'message' => 'Data ekstrakurikuler berhasil diperbarui.',
            'data' => $extracurricular
        ]);
    }

    public function destroy($id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        $extracurricular->delete();

        return response()->json([
            'message' => 'Data ekstrakurikuler berhasil dihapus.'
        ]);
    }

    private function saveBase64Image($base64Image)
    {
        $imageName = time() . '-' . uniqid() . '.jpg';
        $image = substr($base64Image, strpos($base64Image, ',') + 1);
        $image = base64_decode($image);
        Storage::disk('public')->put('extracurriculars/' . $imageName, $image);
        return '/storage/extracurriculars/' . $imageName;
    }
}