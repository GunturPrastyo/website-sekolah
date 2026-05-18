<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimelineController extends Controller
{
    public function index()
    {
        // Get all timelines sorted by order and then year
        $timelines = Timeline::orderBy('order', 'asc')->orderBy('year', 'asc')->get();
        return response()->json(['data' => $timelines]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'year' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'image' => 'nullable|string',
            'description' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        // Handle Base64 images embedded in the description
        if (str_contains($validatedData['description'], 'data:image')) {
            $description = $validatedData['description'];
            $dom = new \DOMDocument();
            // Menggunakan @ untuk menekan warning dari HTML yang mungkin tidak well-formed
            @$dom->loadHTML($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $images = $dom->getElementsByTagName('img');
            foreach ($images as $img) {
                $src = $img->getAttribute('src');
                if (str_starts_with($src, 'data:image')) {
                    preg_match('/data:image\/(\w+);base64,/', $src, $type);
                    $imageData = base64_decode(substr($src, strpos($src, ',') + 1));
                    $filename = 'timeline/content/' . time() . '_' . uniqid() . '.' . ($type[1] ?? 'jpg');
                    Storage::disk('public')->put($filename, $imageData);
                    $img->setAttribute('src', asset('storage/' . $filename));
                }
            }
            $validatedData['description'] = $dom->saveHTML();
        }

        if (!isset($validatedData['order'])) {
            $validatedData['order'] = Timeline::max('order') + 1;
        }

        $timeline = Timeline::create($validatedData);

        return response()->json([
            'message' => 'Timeline created successfully',
            'data' => $timeline
        ], 201);
    }

    public function update(Request $request, Timeline $timeline)
    {
        $validatedData = $request->validate([
            'year' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'image' => 'nullable|string',
            'description' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        // Handle Base64 images embedded in the description (juga untuk update)
        if (str_contains($validatedData['description'], 'data:image')) {
            $description = $validatedData['description'];
            $dom = new \DOMDocument();
            @$dom->loadHTML($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $images = $dom->getElementsByTagName('img');
            foreach ($images as $img) {
                $src = $img->getAttribute('src');
                if (str_starts_with($src, 'data:image')) {
                    preg_match('/data:image\/(\w+);base64,/', $src, $type);
                    $imageData = base64_decode(substr($src, strpos($src, ',') + 1));
                    $filename = 'timeline/content/' . time() . '_' . uniqid() . '.' . ($type[1] ?? 'jpg');
                    Storage::disk('public')->put($filename, $imageData);
                    $img->setAttribute('src', asset('storage/' . $filename));
                }
            }
            $validatedData['description'] = $dom->saveHTML();
        }

        $timeline->update($validatedData);

        return response()->json([
            'message' => 'Timeline updated successfully',
            'data' => $timeline
        ]);
    }

    public function destroy(Timeline $timeline)
    {
        $timeline->delete();

        return response()->json([
            'message' => 'Timeline deleted successfully'
        ]);
    }

    public function reorder(Request $request)
    {
        $validatedData = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:timelines,id',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($validatedData['orders'] as $item) {
            Timeline::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json([
            'message' => 'Timeline reordered successfully'
        ]);
    }
}
