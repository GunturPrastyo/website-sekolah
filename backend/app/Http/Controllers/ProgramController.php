<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Http\Resources\ProgramResource;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;

class ProgramController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        $programs = Program::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => ProgramResource::collection($programs)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'badge' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'subjects' => 'nullable|string',
            'careers' => 'nullable|string',
            'image' => 'nullable|string',
            'background_img' => 'nullable|string',
        ]);

        if (!empty($validated['image']) && str_starts_with($validated['image'], 'data:image')) {
            $path = $this->processAndSaveImage($validated['image'], 'programs');
            $validated['image'] = '/storage/' . $path;
        }

        if (!empty($validated['background_img']) && str_starts_with($validated['background_img'], 'data:image')) {
            $path = $this->processAndSaveImage($validated['background_img'], 'programs');
            $validated['background_img'] = '/storage/' . $path;
        }

        $program = Program::create($validated);

        return response()->json(['data' => new ProgramResource($program)], 201);
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'badge' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'subjects' => 'nullable|string',
            'careers' => 'nullable|string',
            'image' => 'nullable|string',
            'background_img' => 'nullable|string',
        ]);

        if (!empty($validated['image']) && $validated['image'] !== $program->image && str_starts_with($validated['image'], 'data:image')) {
            $oldPath = $program->image ? str_replace('/storage/', '', $program->image) : null;
            $path = $this->processAndSaveImage($validated['image'], 'programs', $oldPath);
            $validated['image'] = '/storage/' . $path;
        }

        if (!empty($validated['background_img']) && $validated['background_img'] !== $program->background_img && str_starts_with($validated['background_img'], 'data:image')) {
            $oldPathBg = $program->background_img ? str_replace('/storage/', '', $program->background_img) : null;
            $path = $this->processAndSaveImage($validated['background_img'], 'programs', $oldPathBg);
            $validated['background_img'] = '/storage/' . $path;
        }

        $program->update($validated);

        return response()->json(['data' => new ProgramResource($program)]);
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        
        $oldPath = $program->image ? str_replace('/storage/', '', $program->image) : null;
        $this->deleteOldImage($oldPath);

        $oldPathBg = $program->background_img ? str_replace('/storage/', '', $program->background_img) : null;
        $this->deleteOldImage($oldPathBg);

        $program->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}