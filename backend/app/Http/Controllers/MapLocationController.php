<?php

namespace App\Http\Controllers;

use App\Models\MapLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MapLocationController extends Controller
{
    public function index()
    {
        $locations = MapLocation::all();
        return response()->json(['data' => $locations]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'top' => 'required|string|max:50',
            'left' => 'required|string|max:50',
            'institutions' => 'nullable|array',
        ]);

        $validated['institutions'] = $this->processInstitutionsImages($validated['institutions'] ?? []);

        $location = MapLocation::create($validated);

        return response()->json(['message' => 'Lokasi berhasil disimpan', 'data' => $location], 201);
    }

    public function update(Request $request, MapLocation $mapLocation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'top' => 'required|string|max:50',
            'left' => 'required|string|max:50',
            'institutions' => 'nullable|array',
        ]);

        $validated['institutions'] = $this->processInstitutionsImages($validated['institutions'] ?? [], $mapLocation->institutions ?? []);

        $mapLocation->update($validated);

        return response()->json(['message' => 'Lokasi berhasil diperbarui', 'data' => $mapLocation]);
    }

    public function destroy(MapLocation $mapLocation)
    {
        $this->processInstitutionsImages([], $mapLocation->institutions ?? []);
        $mapLocation->delete();
        return response()->json(['message' => 'Lokasi berhasil dihapus']);
    }

    private function processInstitutionsImages($newInstitutions, $oldInstitutions = [])
    {
        $oldImages = collect($oldInstitutions)->pluck('logo')->filter(fn($val) => str_starts_with($val, '/storage/'))->toArray();
        $newImages = [];

        foreach ($newInstitutions as &$inst) {
            if (!empty($inst['logo'])) {
                if (str_starts_with($inst['logo'], 'data:image')) {
                    preg_match('/data:image\/(\w+);base64,/', $inst['logo'], $type);
                    $extension = $type[1] ?? 'png';
                    $imageData = base64_decode(substr($inst['logo'], strpos($inst['logo'], ',') + 1));
                    $filename = 'institutions/' . time() . '_' . uniqid() . '.' . $extension;
                    Storage::disk('public')->put($filename, $imageData);
                    $inst['logo'] = '/storage/' . $filename;
                }
                if (str_starts_with($inst['logo'], '/storage/')) $newImages[] = $inst['logo'];
            }
        }

        $imagesToDelete = array_diff($oldImages, $newImages);
        foreach ($imagesToDelete as $img) Storage::disk('public')->delete(str_replace('/storage/', '', $img));

        return $newInstitutions;
    }
}