<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('schoolClass')->orderBy('id', 'desc')->get();
        
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
            'school_class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|in:aktif,alumni',
        ]);

        $student = Student::create($validated);
        $student->load('schoolClass');

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
            'school_class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|in:aktif,alumni',
        ]);

        $student->update($validated);
        $student->load('schoolClass');

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