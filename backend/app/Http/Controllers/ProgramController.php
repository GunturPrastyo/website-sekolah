<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Http\Resources\ProgramResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
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
            'description' => 'nullable|string',
            'subjects' => 'nullable|string',
            'careers' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if (!empty($validated['image'])) {
            $validated['image'] = $this->handleImage($validated['image']);
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
            'description' => 'nullable|string',
            'subjects' => 'nullable|string',
            'careers' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if (!empty($validated['image']) && $validated['image'] !== $program->image) {
            $validated['image'] = $this->handleImage($validated['image']);
            
            if ($program->image && Str::startsWith($program->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $program->image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        $program->update($validated);

        return response()->json(['data' => new ProgramResource($program)]);
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        
        if ($program->image && Str::startsWith($program->image, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $program->image);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $program->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    private function handleImage($base64Image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $base64Image = str_replace(' ', '+', $base64Image);
            $imageName = Str::random(10) . '.' . strtolower($type[1]);
            Storage::disk('public')->put('programs/' . $imageName, base64_decode($base64Image));
            return '/storage/programs/' . $imageName;
        }
        return $base64Image;
    }
}