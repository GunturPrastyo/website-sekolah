<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Resources\StaffResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
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
            preg_match('/data:image\/(\w+);base64,/', $validatedData['image'], $type);
            $extension = $type[1] ?? 'jpg';
            $imageData = base64_decode(substr($validatedData['image'], strpos($validatedData['image'], ',') + 1));
            $filename = 'staff/' . time() . '_' . uniqid() . '.' . $extension;
            Storage::disk('public')->put($filename, $imageData);
            $validatedData['image'] = $filename;
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
            if ($staff->image && !str_starts_with($staff->image, 'http')) {
                Storage::disk('public')->delete($staff->image);
            }
            preg_match('/data:image\/(\w+);base64,/', $validatedData['image'], $type);
            $extension = $type[1] ?? 'jpg';
            $imageData = base64_decode(substr($validatedData['image'], strpos($validatedData['image'], ',') + 1));
            $filename = 'staff/' . time() . '_' . uniqid() . '.' . $extension;
            Storage::disk('public')->put($filename, $imageData);
            $validatedData['image'] = $filename;
        } elseif (!isset($validatedData['image']) || empty($validatedData['image'])) {
            if ($staff->image && !str_starts_with($staff->image, 'http')) {
                Storage::disk('public')->delete($staff->image);
            }
            $validatedData['image'] = null;
        }

        $staff->update($validatedData);

        Cache::forget('dashboard_total_guru');

        return response()->json(['message' => 'Data staf berhasil diperbarui', 'data' => new StaffResource($staff)]);
    }

    public function destroy(Staff $staff)
    {
        if ($staff->image && !str_starts_with($staff->image, 'http')) {
            Storage::disk('public')->delete($staff->image);
        }
        $staff->delete();
        
        Cache::forget('dashboard_total_guru');

        return response()->json(['message' => 'Data staf berhasil dihapus']);
    }
}