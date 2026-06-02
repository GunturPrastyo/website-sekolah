<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\AlumniResource;

class AlumniController extends Controller
{
    public function index()
    {
        $alumnis = Alumni::with('student')->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'data' => AlumniResource::collection($alumnis)
        ]);
    }

    public function publicIndex()
    {
        $alumnis = Alumni::with('student:id,name')->orderBy('graduation_year', 'desc')->get();
        
        $mapped = $alumnis->map(function($alumni) {
            return [
                'id' => $alumni->id,
                'name' => $alumni->student->name ?? 'Alumni',
                'year' => $alumni->graduation_year,
                'status' => $alumni->career_status,
                'instansi' => $alumni->institution,
            ];
        });

        return response()->json([
            'data' => $mapped
        ]);
    }

    public function unassignedStudents()
    {
        $trackedStudentIds = Alumni::pluck('student_id')->toArray();
        
        // Mengambil siswa dengan status "alumni" yang belum ada di tabel alumnis
        $unassigned = Student::where('status', 'alumni')
            ->whereNotIn('id', $trackedStudentIds)
            ->get(['id', 'nisn', 'name', 'grade', 'major']);

        return response()->json(['data' => $unassigned]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id|unique:alumnis,student_id',
            'year' => 'required|string|max:10',
            'status' => 'required|string|max:50',
            'instansi' => 'nullable|string|max:255',
        ]);

        $alumni = Alumni::create([
            'student_id' => $validated['student_id'],
            'graduation_year' => $validated['year'],
            'career_status' => $validated['status'],
            'institution' => $validated['instansi'],
        ]);

        $alumni->load('student');

        return response()->json([
            'message' => 'Data alumni berhasil ditambahkan',
            'data' => new AlumniResource($alumni)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $alumni = Alumni::findOrFail($id);
        $validated = $request->validate([
            'year' => 'required|string|max:10',
            'status' => 'required|string|max:50',
            'instansi' => 'nullable|string|max:255',
        ]);

        $alumni->update([
            'graduation_year' => $validated['year'],
            'career_status' => $validated['status'],
            'institution' => $validated['instansi'],
        ]);

        $alumni->load('student');

        return response()->json([
            'message' => 'Data alumni berhasil diperbarui',
            'data' => new AlumniResource($alumni)
        ], 200);
    }

    public function destroy($id)
    {
        Alumni::findOrFail($id)->delete();
        return response()->json(['message' => 'Data alumni berhasil dihapus']);
    }
}