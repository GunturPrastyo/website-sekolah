<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = Download::orderBy('created_at', 'desc')->get()->map(function ($download) {
            return [
                'id' => $download->id,
                'name' => $download->name,
                'category' => $download->category,
                'type' => $download->type,
                'size' => $download->size,
                'date' => $download->created_at->format('d M Y'),
                'file_url' => asset('storage/' . $download->file_path),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $downloads
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'file' => 'required|file|max:20480', // max 20MB
        ]);

        $file = $request->file('file');
        $filePath = $file->store('downloads', 'public');
        $type = strtolower($file->getClientOriginalExtension());
        $sizeBytes = $file->getSize();
        
        $sizeInMB = number_format($sizeBytes / (1024 * 1024), 1, '.', '');
        $sizeInKB = number_format($sizeBytes / 1024, 0, '.', '');
        $sizeFormatted = $sizeBytes > 1024 * 1024 ? "{$sizeInMB} MB" : "{$sizeInKB} KB";

        $download = Download::create([
            'name' => $request->name,
            'category' => $request->category,
            'type' => $type,
            'size' => $sizeFormatted,
            'file_path' => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $download->id,
                'name' => $download->name,
                'category' => $download->category,
                'type' => $download->type,
                'size' => $download->size,
                'date' => $download->created_at->format('d M Y'),
                'file_url' => asset('storage/' . $download->file_path),
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $download = Download::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'file' => 'nullable|file|max:20480',
        ]);

        $download->name = $request->name;
        $download->category = $request->category;

        if ($request->hasFile('file')) {
            if ($download->file_path) {
                Storage::disk('public')->delete($download->file_path);
            }

            $file = $request->file('file');
            $filePath = $file->store('downloads', 'public');
            $type = strtolower($file->getClientOriginalExtension());
            $sizeBytes = $file->getSize();
            
            $sizeInMB = number_format($sizeBytes / (1024 * 1024), 1, '.', '');
            $sizeInKB = number_format($sizeBytes / 1024, 0, '.', '');
            $sizeFormatted = $sizeBytes > 1024 * 1024 ? "{$sizeInMB} MB" : "{$sizeInKB} KB";

            $download->file_path = $filePath;
            $download->type = $type;
            $download->size = $sizeFormatted;
        }

        $download->save();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $download->id,
                'name' => $download->name,
                'category' => $download->category,
                'type' => $download->type,
                'size' => $download->size,
                'date' => $download->created_at->format('d M Y'),
                'file_url' => asset('storage/' . $download->file_path),
            ]
        ]);
    }

    public function destroy($id)
    {
        $download = Download::findOrFail($id);
        
        if ($download->file_path) {
            Storage::disk('public')->delete($download->file_path);
        }
        
        $download->delete();

        return response()->json([
            'success' => true,
            'message' => 'File berhasil dihapus'
        ]);
    }
}