<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Mengambil semua data pengaturan sebagai pasangan key => value
        $settings = Setting::pluck('value', 'key')->all();
        
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        
        // List key yang berpotensi menyimpan media (gambar/video) base64
        $fileKeys = ['logo', 'favicon', 'headerBeranda', 'headerSejarah', 'headerVisiMisi', 'headerFasilitas', 'headerGuruStaf', 'headerEkskul'];

        foreach ($data as $key => $value) {
            // Jika value adalah base64 string, maka konversi dan simpan ke Storage
            if (in_array($key, $fileKeys) && $value) {
                if (preg_match('/^data:(image|video)\/(\w+);base64,/', $value, $type)) {
                    $valueData = substr($value, strpos($value, ',') + 1);
                    $extension = strtolower($type[2]);
                    
                    // Normalisasi string base64
                    $valueData = str_replace(' ', '+', $valueData);
                    $fileDecoded = base64_decode($valueData);
                    $fileName = 'settings/' . $key . '_' . time() . '.' . $extension;
                    
                    // Hapus file lama jika ada dan merupakan file storage
                    $oldSetting = Setting::where('key', $key)->first();
                    if ($oldSetting && $oldSetting->value && str_contains($oldSetting->value, url('storage'))) {
                        $oldPath = str_replace(url('storage') . '/', '', $oldSetting->value);
                        Storage::disk('public')->delete($oldPath);
                    }
                    
                    Storage::disk('public')->put($fileName, $fileDecoded);
                    $value = url('storage/' . $fileName);
                }
            }

            // Simpan atau update berdasarkan "key"
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan.',
            'data' => Setting::pluck('value', 'key')->all()
        ]);
    }
}