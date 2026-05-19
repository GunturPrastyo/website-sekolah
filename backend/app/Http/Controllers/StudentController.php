<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('id', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data'    => $students
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn'   => 'required|string|unique:students,nisn',
            'name'   => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'grade'  => 'required|string',
            'major'  => 'required|string',
            'rombel' => 'required|string',
            'status' => 'required|in:aktif,alumni',
        ]);

        $student = Student::create($validated);

        return response()->json([
            'success' => true,
            'data'    => $student
        ], 201);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nisn'   => 'required|string|unique:students,nisn,' . $student->id,
            'name'   => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'grade'  => 'required|string',
            'major'  => 'required|string',
            'rombel' => 'required|string',
            'status' => 'required|in:aktif,alumni',
        ]);

        $student->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $student
        ]);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil dihapus'
        ]);
    }
}