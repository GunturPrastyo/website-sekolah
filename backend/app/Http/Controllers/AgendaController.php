<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use App\Http\Resources\AgendaResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'attachment' => 'nullable|string',
        ]);

        $attachmentPath = null;
        if (!empty($validated['attachment']) && preg_match('/^data:(image|application)\/(\w+);base64,/', $validated['attachment'], $type)) {
            $data = substr($validated['attachment'], strpos($validated['attachment'], ',') + 1);
            $extension = strtolower($type[2]);
            if ($type[1] === 'application' && $extension === 'pdf') {
                $extension = 'pdf';
            } elseif ($type[1] === 'image') {
                $extension = $extension === 'jpeg' ? 'jpg' : $extension;
            }
            $filename = 'agendas/' . time() . '_' . uniqid() . '.' . $extension;
            Storage::disk('public')->put($filename, base64_decode($data));
            $attachmentPath = '/storage/' . $filename;
        }

        $agenda = Agenda::create([
            'title' => $validated['title'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'] ?? null,
            'time' => $validated['time'] ?? null,
            'location' => $validated['location'] ?? null,
            'color' => $validated['color'],
            'attachment' => $attachmentPath,
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
            'attachment' => 'nullable|string',
        ]);

        $attachmentPath = $agenda->attachment;
        if (!empty($validated['attachment']) && preg_match('/^data:(image|application)\/(\w+);base64,/', $validated['attachment'], $type)) {
            $data = substr($validated['attachment'], strpos($validated['attachment'], ',') + 1);
            $extension = strtolower($type[2]);
            if ($type[1] === 'application' && $extension === 'pdf') {
                $extension = 'pdf';
            } elseif ($type[1] === 'image') {
                $extension = $extension === 'jpeg' ? 'jpg' : $extension;
            }
            $filename = 'agendas/' . time() . '_' . uniqid() . '.' . $extension;
            Storage::disk('public')->put($filename, base64_decode($data));
            $attachmentPath = '/storage/' . $filename;

            if ($agenda->attachment && Str::startsWith($agenda->attachment, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $agenda->attachment));
            }
        } elseif (empty($validated['attachment']) && $request->has('attachment')) {
            if ($agenda->attachment && Str::startsWith($agenda->attachment, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $agenda->attachment));
            }
            $attachmentPath = null;
        }

        $agenda->update([
            'title' => $validated['title'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'] ?? null,
            'time' => $validated['time'] ?? null,
            'location' => $validated['location'] ?? null,
            'color' => $validated['color'],
            'attachment' => $attachmentPath,
        ]);

        return new AgendaResource($agenda);
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->attachment && Str::startsWith($agenda->attachment, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $agenda->attachment));
        }
        $agenda->delete();
        return response()->json(['message' => 'Agenda berhasil dihapus']);
    }
}