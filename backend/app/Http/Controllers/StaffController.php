<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Resources\StaffResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageUploadTrait;

class StaffController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        $staff = Staff::all();
        return response()->json(['data' => StaffResource::collection($staff)]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'image' => 'nullable|string',
        ]);

        if (isset($validatedData['image']) && str_starts_with($validatedData['image'], 'data:image')) {
            $validatedData['image'] = $this->processAndSaveImage($validatedData['image'], 'staff');
        }

        $staff = Staff::create($validatedData);

        Cache::forget('dashboard_total_guru');

        return response()->json(['message' => 'Data staf berhasil ditambahkan', 'data' => new StaffResource($staff)], 201);
    }

    public function update(Request $request, Staff $staff)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'image' => 'nullable|string',
        ]);

        if (isset($validatedData['image']) && str_starts_with($validatedData['image'], 'data:image')) {
            $oldPath = $staff->image && !str_starts_with($staff->image, 'http') ? $staff->image : null;
            $validatedData['image'] = $this->processAndSaveImage($validatedData['image'], 'staff', $oldPath);
        } elseif (!isset($validatedData['image']) || empty($validatedData['image'])) {
            $this->deleteOldImage($staff->image);
            $validatedData['image'] = null;
        }

        $staff->update($validatedData);

        Cache::forget('dashboard_total_guru');

        return response()->json(['message' => 'Data staf berhasil diperbarui', 'data' => new StaffResource($staff)]);
    }

    public function destroy(Staff $staff)
    {
        $this->deleteOldImage($staff->image);
        $staff->delete();
        
        Cache::forget('dashboard_total_guru');

        return response()->json(['message' => 'Data staf berhasil dihapus']);
    }
}