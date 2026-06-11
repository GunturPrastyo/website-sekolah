<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VisionMission;
use App\Http\Resources\VisionMissionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisionMissionController extends Controller
{
    /**
     * Display the vision and mission data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // We assume there is only one record for vision and mission, or we create a default one.
        $visionMission = VisionMission::firstOrCreate(
            ['id' => 1],
            [
                'vision' => 'Terwujudnya Lulusan yang Bertaqwa, Berkarakter, Cerdas, Terampil, dan Berbudaya Lingkungan serta Mampu Bersaing di Era Global.',
                'missions' => [],
                'principal_speech' => '<p>Selamat datang di website resmi SMA Negeri 1 Nogosari.</p>',
            ]
        );

        return response()->json([
            'success' => true,
            'data' => new VisionMissionResource($visionMission),
        ]);
    }

    /**
     * Update the vision and mission data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vision' => 'required|string',
            'missions' => 'nullable|array',
            'missions.*.text' => 'required|string',
            'principal_speech' => 'nullable|string',
            'principal_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Use updateOrCreate to handle both creation and update of the single record.
        $visionMission = VisionMission::updateOrCreate(
            ['id' => 1], // The condition to find the record
            $validator->validated() // The data to update or create with
        );

        return response()->json([
            'success' => true,
            'message' => 'Data Visi, Misi, dan Sambutan berhasil diperbarui.',
            'data' => new VisionMissionResource($visionMission),
        ]);
    }
}