<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;

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
