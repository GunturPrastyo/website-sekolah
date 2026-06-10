<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search');
        $grade = $request->query('grade');
        $major = $request->query('major');
        $status = $request->query('status');

        $query = Student::with('schoolClass')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        if ($grade) {
            $query->where('grade', $grade);
        }
        if ($major) {
            $query->where('major', $major);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $students = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data'    => StudentResource::collection($students->items()),
            'pagination' => [
                'total'        => $students->total(),
                'per_page'     => $students->perPage(),
                'current_page' => $students->currentPage(),
                'last_page'    => $students->lastPage(),
                'from'         => $students->firstItem(),
                'to'           => $students->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn'   => 'required|string|unique:students,nisn',
            'name'   => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'grade'  => 'nullable|string',
            'major'  => 'nullable|string',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|in:aktif,alumni',
        ]);

        $student = Student::create($validated);
        $student->load('schoolClass');

        // Hapus cache dashboard agar data terupdate
        Cache::forget('dashboard_total_siswa');
        Cache::forget('public_landing_stats');

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
            'grade'  => 'nullable|string',
            'major'  => 'nullable|string',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|in:aktif,alumni',
        ]);

        $student->update($validated);
        $student->load('schoolClass');

        // Hapus cache dashboard agar data terupdate
        Cache::forget('dashboard_total_siswa');
        Cache::forget('public_landing_stats');

        return response()->json([
            'success' => true,
            'data'    => new StudentResource($student)
        ]);
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();
            
            Cache::forget('dashboard_total_siswa');
            Cache::forget('public_landing_stats');

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: Siswa ini masih terhubung dengan data lain di sistem (misal: Data Alumni).'
            ], 400);
        }
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id'
        ]);

        try {
            Student::whereIn('id', $validated['ids'])->delete();
            
            Cache::forget('dashboard_total_siswa');
            Cache::forget('public_landing_stats');

            return response()->json([
                'success' => true,
                'message' => 'Data siswa terpilih berhasil dihapus secara massal'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: Beberapa siswa masih terhubung dengan data lain (misal: Data Alumni).'
            ], 400);
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
            'grade' => 'nullable|string',
            'major' => 'nullable|string',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'status' => 'nullable|in:aktif,alumni',
        ]);

        $updateData = [];
        if (array_key_exists('grade', $validated)) $updateData['grade'] = $validated['grade'];
        if (array_key_exists('major', $validated)) $updateData['major'] = $validated['major'];
        if (array_key_exists('status', $validated)) $updateData['status'] = $validated['status'];
        if (array_key_exists('school_class_id', $validated)) {
            $updateData['school_class_id'] = $validated['school_class_id'];
        }

        if (empty($updateData)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang diubah'], 400);
        }

        try {
            Student::whereIn('id', $validated['ids'])->update($updateData);
            
            Cache::forget('dashboard_total_siswa');
            Cache::forget('public_landing_stats');

            return response()->json([
                'success' => true,
                'message' => 'Data siswa terpilih berhasil diperbarui secara massal'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data massal.'
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array',
            'students.*.nisn' => 'required|string',
            'students.*.name' => 'required|string|max:255',
            'students.*.gender' => 'required|in:L,P',
            'students.*.grade' => 'nullable|string',
            'students.*.major' => 'nullable|string',
            'students.*.status' => 'required|in:aktif,alumni',
        ]);

        $importedCount = 0;
        foreach ($validated['students'] as $studentData) {
            Student::updateOrCreate(
                ['nisn' => $studentData['nisn']],
                [
                    'name' => $studentData['name'],
                    'gender' => $studentData['gender'],
                    'grade' => $studentData['grade'] ?? null,
                    'major' => $studentData['major'] ?? null,
                    'status' => $studentData['status'],
                ]
            );
            $importedCount++;
        }

        Cache::forget('dashboard_total_siswa');
        Cache::forget('public_landing_stats');

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimpor $importedCount data siswa."
        ]);
    }
}