<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('schoolClass')->orderBy('id', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data'    => StudentResource::collection($students)
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
            'data'    => new StudentResource($student)
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
            'data'    => new StudentResource($student)
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

    public function import(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array',
            'students.*.nisn' => 'required|string',
            'students.*.name' => 'required|string|max:255',
            'students.*.gender' => 'required|in:L,P',
            'students.*.grade' => 'required|string',
            'students.*.major' => 'required|string',
            'students.*.status' => 'required|in:aktif,alumni',
        ]);

        $importedCount = 0;
        foreach ($validated['students'] as $studentData) {
            Student::updateOrCreate(
                ['nisn' => $studentData['nisn']],
                [
                    'name' => $studentData['name'],
                    'gender' => $studentData['gender'],
                    'grade' => $studentData['grade'],
                    'major' => $studentData['major'],
                    'status' => $studentData['status'],
                ]
            );
            $importedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimpor $importedCount data siswa."
        ]);
    }
}