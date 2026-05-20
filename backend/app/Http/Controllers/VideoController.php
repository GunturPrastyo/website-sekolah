<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $video = Video::first();
        return response()->json(['data' => $video]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'url' => 'nullable|string'
        ]);

        $video = Video::first();
        if ($video) {
            $video->update(['url' => $validated['url']]);
        } else {
            $video = Video::create(['url' => $validated['url']]);
        }

        return response()->json(['data' => $video]);
    }
}
