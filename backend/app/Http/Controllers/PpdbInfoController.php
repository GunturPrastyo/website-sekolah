<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PpdbInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PpdbInfoController extends Controller
{
    public function index()
    {
        $info = PpdbInfo::first();
        if (!$info) {
            $info = PpdbInfo::create([
                'syarat' => [],
                'alur' => [],
                'jalur' => [],
                'faqs' => []
            ]);
        }
        
        if ($info->brosur_path) {
            $info->brosur_url = Storage::url($info->brosur_path);
        }

        return response()->json(['data' => $info]);
    }

    public function update(Request $request)
    {
        $info = PpdbInfo::first() ?? new PpdbInfo();

        $info->fill($request->only(['opening_date', 'syarat', 'alur', 'jalur', 'faqs']));
        $info->save();

        return response()->json(['message' => 'Data berhasil disimpan', 'data' => $info]);
    }

    public function uploadBrosur(Request $request)
    {
        $request->validate([
            'brosur' => 'required|mimes:pdf|max:10240'
        ]);

        $info = PpdbInfo::first() ?? new PpdbInfo();

        if ($info->brosur_path) {
            Storage::disk('public')->delete($info->brosur_path);
        }

        $path = $request->file('brosur')->store('brosur', 'public');
        $info->brosur_path = $path;
        $info->save();

        return response()->json([
            'message' => 'Brosur berhasil diunggah',
            'url' => Storage::url($path)
        ]);
    }
    
    public function deleteBrosur()
    {
        $info = PpdbInfo::first();
        if ($info && $info->brosur_path) {
            Storage::disk('public')->delete($info->brosur_path);
            $info->brosur_path = null;
            $info->save();
        }

        return response()->json(['message' => 'Brosur berhasil dihapus']);
    }

    public function downloadBrosur()
    {
        $ppdbInfo = PpdbInfo::first();

        if (!$ppdbInfo || !$ppdbInfo->brosur_path) {
            return response()->json(['message' => 'File brosur tidak ditemukan.'], 404);
        }

        $path = $ppdbInfo->brosur_path;

        if (Storage::disk('public')->exists($path)) {
            return response()->download(storage_path('app/public/' . $path));
        }

        return response()->json(['message' => 'File tidak ditemukan di penyimpanan.'], 404);
    }
}