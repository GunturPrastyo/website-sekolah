<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Http\Resources\SchoolClassResource;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        // Mengambil data kelas beserta relasi detail wali kelas dan jurusannya
        $classes = SchoolClass::with(['homeroom', 'program'])->get();

        return response()->json(['data' => SchoolClassResource::collection($classes)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'program_id' => 'required|integer|exists:programs,id',
            'homeroom_id' => 'nullable|integer|exists:staff,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $schoolClass = SchoolClass::create($validated);
        $schoolClass->load('program');

        return response()->json(['message' => 'Data kelas berhasil ditambahkan', 'data' => new SchoolClassResource($schoolClass)], 201);
    }

    public function update(Request $request, $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'program_id' => 'required|integer|exists:programs,id',
            'homeroom_id' => 'nullable|integer|exists:staff,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $schoolClass->update($validated);
        $schoolClass->load('program');

        return response()->json(['message' => 'Data kelas berhasil diperbarui', 'data' => new SchoolClassResource($schoolClass)]);
    }

    public function destroy($id)
    {
        SchoolClass::findOrFail($id)->delete();
        return response()->json(['message' => 'Data kelas berhasil dihapus']);
    }
}