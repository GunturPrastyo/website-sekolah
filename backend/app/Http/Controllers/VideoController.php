<?php

namespace App\Http\Controllers;

use App\Models\SchoolVideo;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $video = SchoolVideo::first();
        return response()->json(['data' => $video]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'url' => 'nullable|string'
        ]);

        $video = SchoolVideo::first();
        if ($video) {
            $video->update(['url' => $validated['url']]);
        } else {
            $video = SchoolVideo::create(['url' => $validated['url']]);
        }

        return response()->json(['data' => $video]);
    }
}
