<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Visitor;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Extracurricular;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Traits\ImageUploadTrait;

class SettingController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        // Mengambil semua data pengaturan sebagai pasangan key => value
        $settings = Cache::rememberForever('global_settings', function () {
            return Setting::pluck('value', 'key')->all();
        });
        
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function visitorStats()
    {
        // Cache statistik pengunjung selama 5 menit (300 detik) untuk mengurangi beban database yang berat karena COUNT()
        $stats = Cache::remember('visitor_stats_cache', 300, function () {
            return [
                'hari' => Visitor::whereDate('visited_date', Carbon::today())->count(),
                'bulan' => Visitor::whereMonth('visited_date', Carbon::now()->month)->whereYear('visited_date', Carbon::now()->year)->count(),
                'tahun' => Visitor::whereYear('visited_date', Carbon::now()->year)->count(),
                'total' => Visitor::count()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function publicStats()
    {
        // Ambil data secara dinamis dari masing-masing tabel terkait
        $stats = Cache::remember('public_landing_stats', 3600, function () { // Cache selama 1 jam
            $schoolProfile = SchoolProfile::first();
            return [
                'akreditasi' => $schoolProfile ? $schoolProfile->accreditation : '-',
                'siswa'      => Student::where('status', 'aktif')->count(),
                'guru'       => Staff::where('category', 'pendidik')->count(),
                'ekskul'     => Extracurricular::count(),
                'prestasi'   => Achievement::count(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        
        // List key yang berpotensi menyimpan media (gambar/video) base64
        $fileKeys = [
            'logo', 'favicon', 'headerBeranda', 'headerSejarah', 'headerVisiMisi', 
            'headerFasilitas', 'headerGuruStaf', 'headerEkskul', 'headerKurikulum',
            'headerAlumni', 'headerProgramJurusan', 'headerPrestasi', 'headerPendaftaran',
            'headerBerita', 'headerGaleri', 'headerArtikel', 'headerUnduhan',
            'benefitFasilitasImage', 'benefitGuruImage', 'benefitPrestasiImage', 'programCoverImage', 'loginBackground', 'ppdbBackgroundImage', 'galleryBackgroundImage'
        ];

        foreach ($data as $key => $value) {
            // Jika value adalah base64 string, maka konversi dan simpan ke Storage
            if (in_array($key, $fileKeys) && $value) {
                if (preg_match('/^data:(image|video)\/(\w+);base64,/', $value)) {
                    
                    // Ambil URL/Path file lama jika ada
                    $oldSetting = Setting::where('key', $key)->first();
                    $oldPath = $oldSetting ? $oldSetting->value : null;

                    // Panggil trait, old file otomatis terhapus, resize + webp otomatis berjalan
                    $relativePath = $this->processAndSaveImage($value, 'settings', $oldPath);
                    
                    // Format kembali ke URL utuh seperti format aslinya
                    $value = url('storage/' . $relativePath);
                }
            }

            // Simpan atau update berdasarkan "key"
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Hapus cache pengaturan ketika ada perubahan
        Cache::forget('global_settings');

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan.',
            'data' => Setting::pluck('value', 'key')->all()
        ]);
    }
}