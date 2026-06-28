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

        $slug = Str::slug($validated['title']);

        if (!empty($validated['attachment'])) {
            if (str_starts_with($validated['attachment'], 'data:image')) {
                // Proses sebagai gambar, simpan dengan nama sementara lalu rename
                $tempPath = $this->processAndSaveImage($validated['attachment'], 'agendas', null, 1200);
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                $newPath = 'agendas/' . $slug . '-' . time() . '.' . $extension;
                Storage::disk('public')->move($tempPath, $newPath);
                $validated['attachment'] = $newPath;
            } elseif (str_starts_with($validated['attachment'], 'data:application/pdf')) {
                // Proses sebagai PDF
                $data = substr($validated['attachment'], strpos($validated['attachment'], ',') + 1);
                $filename = 'agendas/' . $slug . '-' . time() . '.pdf';
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

        $slug = Str::slug($validated['title']);

        if ($request->has('attachment')) {
            $attachment = $request->input('attachment');
            if (str_starts_with($attachment, 'data:image')) {
                $tempPath = $this->processAndSaveImage($attachment, 'agendas', $agenda->attachment, 1200);
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                $newPath = 'agendas/' . $slug . '-' . time() . '.' . $extension;
                Storage::disk('public')->move($tempPath, $newPath);
                $validated['attachment'] = $newPath;
            } elseif (str_starts_with($attachment, 'data:application/pdf')) {
                $this->deleteOldImage($agenda->attachment);
                $data = substr($attachment, strpos($attachment, ',') + 1);
                $filename = 'agendas/' . $slug . '-' . time() . '.pdf';
                Storage::disk('public')->put($filename, base64_decode($data));
                $validated['attachment'] = $filename;
            } elseif (empty($attachment)) {
                // Jika dikirim null atau string kosong, hapus file lama
                $this->deleteOldImage($agenda->attachment);
                $validated['attachment'] = null;
            } else {
                // Jika attachment tidak berubah (berupa URL), bersihkan domain sebelum simpan ke DB
                $validated['attachment'] = preg_replace('#^https?://[^/]+/storage/#', '', $attachment);
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