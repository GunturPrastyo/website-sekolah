<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Http\Resources\ExtracurricularResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class ExtracurricularController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        return response()->json([
            'data' => ExtracurricularResource::collection(Extracurricular::all())
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

        $validated['image'] = $this->processAndSaveImage($validated['image'] ?? null, 'extracurriculars', null, 800);

        $extracurricular = Extracurricular::create($validated);

        return response()->json([
            'message' => 'Data ekstrakurikuler berhasil ditambahkan.',
            'data' => new ExtracurricularResource($extracurricular)
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

        if ($request->has('image')) {
            $validated['image'] = $this->processAndSaveImage(
                $request->input('image'), 'extracurriculars', $extracurricular->image, 800
            );
        }

        $extracurricular->update($validated);

        return response()->json([
            'message' => 'Data ekstrakurikuler berhasil diperbarui.',
            'data' => new ExtracurricularResource($extracurricular)
        ]);
    }

    public function destroy($id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        $this->deleteOldImage($extracurricular->image);
        $extracurricular->delete();

        return response()->json([
            'message' => 'Data ekstrakurikuler berhasil dihapus.'
        ]);
    }
}