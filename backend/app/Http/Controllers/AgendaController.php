<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use App\Http\Resources\AgendaResource;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::orderBy('start_date', 'asc')->get();
        return AgendaResource::collection($agendas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'time' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'color' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $agenda = Agenda::create([
            'title' => $validated['title'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'] ?? null,
            'time' => $validated['time'] ?? null,
            'location' => $validated['location'] ?? null,
            'color' => $validated['color'],
            'description' => $validated['description'] ?? null,
        ]);

        return new AgendaResource($agenda);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'time' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'color' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $agenda->update([
            'title' => $validated['title'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'] ?? null,
            'time' => $validated['time'] ?? null,
            'location' => $validated['location'] ?? null,
            'color' => $validated['color'],
            'description' => $validated['description'] ?? null,
        ]);

        return new AgendaResource($agenda);
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();
        return response()->json(['message' => 'Agenda berhasil dihapus']);
    }
}