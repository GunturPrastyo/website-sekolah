<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::all();
        
        $staff->transform(function ($item) {
            if ($item->image && !str_starts_with($item->image, 'http') && !str_starts_with($item->image, 'data:image')) {
                $item->image = asset('storage/' . $item->image);
            }
            return $item;
        });
        
        return response()->json(['data' => $staff]);
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

        if ($staff->image && !str_starts_with($staff->image, 'http') && !str_starts_with($staff->image, 'data:image')) {
            $staff->image = asset('storage/' . $staff->image);
        }

        return response()->json(['message' => 'Data staf berhasil ditambahkan', 'data' => $staff], 201);
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

        if ($staff->image && !str_starts_with($staff->image, 'http') && !str_starts_with($staff->image, 'data:image')) {
            $staff->image = asset('storage/' . $staff->image);
        }

        return response()->json(['message' => 'Data staf berhasil diperbarui', 'data' => $staff]);
    }

    public function destroy(Staff $staff)
    {
        if ($staff->image && !str_starts_with($staff->image, 'http')) {
            Storage::disk('public')->delete($staff->image);
        }
        $staff->delete();
        return response()->json(['message' => 'Data staf berhasil dihapus']);
    }
}