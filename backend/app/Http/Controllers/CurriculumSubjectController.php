<?php

namespace App\Http\Controllers;

use App\Models\CurriculumSubject;
use Illuminate\Http\Request;
use App\Http\Resources\CurriculumSubjectResource;

class CurriculumSubjectController extends Controller
{
    public function index()
    {
        $subjects = CurriculumSubject::with('program')->get();
        return response()->json([
            'data' => CurriculumSubjectResource::collection($subjects)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade' => 'required|string|max:255',
            'program_id' => 'nullable|exists:programs,id',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'topics' => 'nullable|string',
        ]);

        $subject = CurriculumSubject::create($validated);
        $subject->load('program');

        return response()->json([
            'message' => 'Data mata pelajaran berhasil ditambahkan',
            'data' => new CurriculumSubjectResource($subject)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $subject = CurriculumSubject::findOrFail($id);
        
        $validated = $request->validate([
            'grade' => 'required|string|max:255',
            'program_id' => 'nullable|exists:programs,id',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'topics' => 'nullable|string',
        ]);

        $subject->update($validated);
        $subject->load('program');

        return response()->json([
            'message' => 'Data mata pelajaran berhasil diperbarui',
            'data' => new CurriculumSubjectResource($subject)
        ]);
    }

    public function destroy($id)
    {
        $subject = CurriculumSubject::findOrFail($id);
        $subject->delete();
        return response()->json(['message' => 'Data mata pelajaran berhasil dihapus']);
    }
}