<?php

namespace App\Http\Controllers;

use App\Models\SchoolVideo;
use App\Http\Resources\SchoolVideoResource;
use Illuminate\Http\Request;

class SchoolVideoController extends Controller
{
    public function show()
    {
        $video = SchoolVideo::first();
        return response()->json(['data' => $video ? new SchoolVideoResource($video) : null]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $video = SchoolVideo::first() ?? new SchoolVideo();
        $video->url = $request->url;
        $video->save();

        return response()->json([
            'message' => 'Video profil berhasil diperbarui', 
            'data' => new SchoolVideoResource($video)
        ]);
    }

    public function destroy()
    {
        $video = SchoolVideo::first();
        if ($video) $video->delete();
        
        return response()->json(['message' => 'Video profil berhasil dihapus']);
    }
}