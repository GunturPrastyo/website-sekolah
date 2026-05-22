<?php

namespace App\Http\Controllers;

use App\Models\CurriculumSubject;
use Illuminate\Http\Request;

class CurriculumSubjectController extends Controller
{
    public function index()
    {
        $subjects = CurriculumSubject::all();
        return response()->json(['data' => $subjects]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'topics' => 'nullable|string',
        ]);

        $subject = CurriculumSubject::create($validated);

        return response()->json([
            'message' => 'Data mata pelajaran berhasil ditambahkan',
            'data' => $subject
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $subject = CurriculumSubject::findOrFail($id);
        
        $validated = $request->validate([
            'grade' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'topics' => 'nullable|string',
        ]);

        $subject->update($validated);

        return response()->json(['message' => 'Data mata pelajaran berhasil diperbarui', 'data' => $subject]);
    }

    public function destroy($id)
    {
        $subject = CurriculumSubject::findOrFail($id);
        $subject->delete();
        return response()->json(['message' => 'Data mata pelajaran berhasil dihapus']);
    }
}