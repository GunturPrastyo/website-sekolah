<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\AlumniResource;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Alumni::with('student')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('graduation_year', 'like', "%{$search}%")
                ->orWhere('career_status', 'like', "%{$search}%")
                ->orWhere('institution', 'like', "%{$search}%")
                ->orWhereHas('student', function($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('nisn', 'like', "%{$search}%");
                });
            });
        }

        if ($status && $status !== 'semua') {
            $query->where('career_status', $status);
        }

        $alumnis = $query->paginate($perPage);

        return response()->json([
            'data' => AlumniResource::collection($alumnis->items()),
            'pagination' => [
                'total'        => $alumnis->total(),
                'per_page'     => $alumnis->perPage(),
                'current_page' => $alumnis->currentPage(),
                'last_page'    => $alumnis->lastPage(),
                'from'         => $alumnis->firstItem(),
                'to'           => $alumnis->lastItem(),
            ]
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

    public function unassignedStudents(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search');

        $trackedStudentIds = Alumni::pluck('student_id')->toArray();
        
        // Mengambil siswa dengan status "alumni" yang belum ada di tabel alumnis
        $query = Student::where('status', 'alumni')
            ->whereNotIn('id', $trackedStudentIds);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $unassigned = $query->paginate($perPage, ['id', 'nisn', 'name', 'grade', 'major']);

        return response()->json([
            'data' => $unassigned->items(),
            'pagination' => [
                'total'        => $unassigned->total(),
                'per_page'     => $unassigned->perPage(),
                'current_page' => $unassigned->currentPage(),
                'last_page'    => $unassigned->lastPage(),
            ]
        ]);
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

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:alumnis,id',
            'year' => 'nullable|string|max:10',
            'status' => 'nullable|string|max:50',
            'instansi' => 'nullable|string|max:255',
        ]);

        $updateData = [];
        if ($request->filled('year')) {
            $updateData['graduation_year'] = $validated['year'] === 'kosong' ? null : $validated['year'];
        }
        if ($request->filled('status')) {
            $updateData['career_status'] = $validated['status'];
        }
        if ($request->has('instansi')) { // allow empty string
            $updateData['institution'] = $validated['instansi'] === 'kosong' ? null : $validated['instansi'];
        }

        if (empty($updateData)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih untuk diperbarui.'], 400);
        }

        Alumni::whereIn('id', $validated['ids'])->update($updateData);

        return response()->json(['message' => 'Data alumni terpilih berhasil diperbarui.']);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:alumnis,id',
        ]);

        Alumni::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Data alumni terpilih berhasil dihapus.']);
    }
}