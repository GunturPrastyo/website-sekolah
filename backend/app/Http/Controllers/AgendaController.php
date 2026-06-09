<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use App\Http\Resources\AgendaResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    use ImageUploadTrait;

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

        if (!empty($validated['attachment'])) {
            if (str_starts_with($validated['attachment'], 'data:image')) {
                // Proses sebagai gambar
                $validated['attachment'] = $this->processAndSaveImage($validated['attachment'], 'agendas', null, 1200);
            } elseif (str_starts_with($validated['attachment'], 'data:application/pdf')) {
                // Proses sebagai PDF
                $data = substr($validated['attachment'], strpos($validated['attachment'], ',') + 1);
                $filename = 'agendas/' . Str::random(20) . '.pdf';
                Storage::disk('public')->put($filename, base64_decode($data));
                $validated['attachment'] = $filename;
            }
        }

        $agenda = Agenda::create([
            'title' => $validated['title'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'] ?? null,
            'time' => $validated['time'] ?? null,
            'location' => $validated['location'] ?? null,
            'color' => $validated['color'],
            'attachment' => $validated['attachment'] ?? null,
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

        if ($request->has('attachment')) {
            $attachment = $request->input('attachment');
            if (str_starts_with($attachment, 'data:image')) {
                $validated['attachment'] = $this->processAndSaveImage($attachment, 'agendas', $agenda->attachment, 1200);
            } elseif (str_starts_with($attachment, 'data:application/pdf')) {
                $this->deleteOldImage($agenda->attachment);
                $data = substr($attachment, strpos($attachment, ',') + 1);
                $filename = 'agendas/' . Str::random(20) . '.pdf';
                Storage::disk('public')->put($filename, base64_decode($data));
                $validated['attachment'] = $filename;
            } else {
                // Jika dikirim null atau string kosong, hapus file lama
                $this->deleteOldImage($agenda->attachment);
                $validated['attachment'] = null;
            }
        }

        $agenda->update($validated);

        return new AgendaResource($agenda);
    }

    public function destroy(Agenda $agenda)
    {
        $this->deleteOldImage($agenda->attachment);
        $agenda->delete();
        return response()->json(['message' => 'Agenda berhasil dihapus']);
    }
}