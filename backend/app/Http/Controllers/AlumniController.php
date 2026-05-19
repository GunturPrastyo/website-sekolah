<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Student;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index()
    {
        $alumnis = Alumni::with('student')->orderBy('created_at', 'desc')->get();
        
        $alumnis->transform(function ($item) {
            return [
                'id' => $item->id,
                'student_id' => $item->student_id,
                'nisn' => $item->student->nisn ?? '',
                'name' => $item->student->name ?? '',
                'year' => $item->graduation_year,
                'status' => $item->career_status,
                'instansi' => $item->institution,
            ];
        });

        return response()->json(['data' => $alumnis]);
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

        return $this->formatResponse($alumni, 'Data alumni berhasil ditambahkan', 201);
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

        return $this->formatResponse($alumni, 'Data alumni berhasil diperbarui', 200);
    }

    public function destroy($id)
    {
        Alumni::findOrFail($id)->delete();
        return response()->json(['message' => 'Data alumni berhasil dihapus']);
    }

    private function formatResponse($alumni, $message, $statusCode = 200)
    {
        $alumni->load('student');
        
        return response()->json([
            'message' => $message,
            'data' => [
                'id' => $alumni->id,
                'student_id' => $alumni->student_id,
                'nisn' => $alumni->student->nisn ?? '',
                'name' => $alumni->student->name ?? '',
                'year' => $alumni->graduation_year,
                'status' => $alumni->career_status,
                'instansi' => $alumni->institution,
            ]
        ], $statusCode);
    }
}