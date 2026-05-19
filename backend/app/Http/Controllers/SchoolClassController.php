<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        // Mengambil data kelas beserta relasi detail wali kelasnya
        $classes = SchoolClass::with('homeroom')->get();
        
        // Untuk inisialisasi tambahan properti currentStudents (misalnya belum ada relasi ke tabel siswa)
        $classes->transform(function ($item) {
            $item->currentStudents = 0; // Anda dapat menggantinya dengan count() relasi students nanti
            return $item;
        });

        return response()->json(['data' => $classes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'homeroom_id' => 'nullable|integer|exists:staff,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $schoolClass = SchoolClass::create($validated);

        return response()->json(['message' => 'Data kelas berhasil ditambahkan', 'data' => $schoolClass], 201);
    }

    public function update(Request $request, $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'homeroom_id' => 'nullable|integer|exists:staff,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $schoolClass->update($validated);
        return response()->json(['message' => 'Data kelas berhasil diperbarui', 'data' => $schoolClass]);
    }

    public function destroy($id)
    {
        SchoolClass::findOrFail($id)->delete();
        return response()->json(['message' => 'Data kelas berhasil dihapus']);
    }
}