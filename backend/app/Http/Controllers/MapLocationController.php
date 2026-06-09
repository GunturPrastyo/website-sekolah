<?php

namespace App\Http\Controllers;

use App\Models\MapLocation;
use App\Http\Resources\MapLocationResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class MapLocationController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        $locations = MapLocation::all();
        return response()->json(['data' => MapLocationResource::collection($locations)]);
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

        return response()->json(['message' => 'Lokasi berhasil disimpan', 'data' => new MapLocationResource($location)], 201);
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

        return response()->json(['message' => 'Lokasi berhasil diperbarui', 'data' => new MapLocationResource($mapLocation)]);
    }

    public function destroy(MapLocation $mapLocation)
    {
        // Hapus semua logo institusi yang terkait
        if (!empty($mapLocation->institutions)) {
            foreach ($mapLocation->institutions as $inst) {
                if (!empty($inst['logo'])) {
                    $this->deleteOldImage($inst['logo']);
                }
            }
        }
        $mapLocation->delete();
        return response()->json(['message' => 'Lokasi berhasil dihapus']);
    }

    private function processInstitutionsImages($newInstitutions, $oldInstitutions = [])
    {
        $oldLogos = collect($oldInstitutions)->pluck('logo')->filter()->all();
        $currentLogos = [];

        foreach ($newInstitutions as &$inst) {
            $inst['logo'] = $this->processAndSaveImage($inst['logo'] ?? null, 'institutions', null, 200);
            if ($inst['logo']) $currentLogos[] = $inst['logo'];
        }

        $logosToDelete = array_diff($oldLogos, $currentLogos);
        foreach ($logosToDelete as $logoPath) $this->deleteOldImage($logoPath);

        return $newInstitutions;
    }
}