<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    public function index()
    {
        // Menampilkan data terbaru di urutan teratas
        $facilities = Facility::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $facilities
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $facility = Facility::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil ditambahkan',
            'data' => $facility
        ], 201);
    }

    public function update(Request $request, Facility $facility)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $facility->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil diperbarui',
            'data' => $facility
        ]);
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();

        return response()->json(['success' => true, 'message' => 'Fasilitas berhasil dihapus']);
    }
}