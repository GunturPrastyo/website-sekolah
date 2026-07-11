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

    /**
     * @var array<int, string>
     */
    private array $fileKeys = [
        'logo', 'favicon', 'headerBeranda',
        'headerSejarah_bgImage', 'headerVisiMisi_bgImage',
        'headerFasilitas_bgImage', 'headerGuruStaf_bgImage', 'headerEkskul_bgImage', 'headerKurikulum_bgImage',
        'headerAlumni_bgImage', 'headerProgramJurusan_bgImage', 'headerPrestasi_bgImage', 'headerPendaftaran_bgImage',
        'headerBerita_bgImage', 'headerGaleri_bgImage', 'headerArtikel_bgImage', 'headerUnduhan_bgImage',
        'benefitFasilitasImage', 'benefitGuruImage', 'benefitPrestasiImage',
        'programCoverImage', 'loginBackground', 'ppdbBackgroundImage', 'galleryBackgroundImage'
    ];

    public function index()
    {
      
        $settings = Cache::rememberForever('global_settings', function () {
            return Setting::pluck('value', 'key')->all();
        });

        foreach ($settings as $key => $value) {
           
            if (in_array($key, $this->fileKeys) && is_string($value) && $value) {
                
                if (str_starts_with($value, 'http')) {
                   
                    $parts = explode('/storage/', $value);
                    $cleanPath = end($parts);
                } else {
                    $cleanPath = str_replace('storage/', '', $value);
                }

                $settings[$key] = url('storage/' . $cleanPath);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function visitorStats()
    {
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
        $stats = Cache::remember('public_landing_stats', 3600, function () {
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

        foreach ($data as $key => $value) {
            
            if (in_array($key, $this->fileKeys) && is_string($value) && $value) {
                if (preg_match('/^data:(image|video)\/(\w+);base64,/', $value)) {

                    $oldSetting = Setting::where('key', $key)->first();
                    $oldPath = $oldSetting ? $oldSetting->value : null;

                   
                    if ($oldPath && str_starts_with($oldPath, 'http')) {
                        $parts = explode('/storage/', $oldPath);
                        $oldPath = end($parts);
                    }

                   
                    $relativePath = $this->processAndSaveImage($value, 'settings', $oldPath);
                    $value = $relativePath;
                } elseif (str_starts_with($value, 'http')) {
                   
                    $parts = explode('/storage/', $value);
                    $value = end($parts);
                }
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('global_settings');

        $response = $this->index();
        $responseData = json_decode($response->getContent(), true);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan.',
            'data' => $responseData['data'] ?? Setting::pluck('value', 'key')->all()
        ]);
    }
}