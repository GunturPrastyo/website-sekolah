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
        // Ambil data dari cache
        $settings = Cache::rememberForever('global_settings', function () {
            return Setting::pluck('value', 'key')->all();
        });

        // List key yang bertindak sebagai file gambar/media sesuai array kodinganmu
        $fileKeys = [
            'logo', 'favicon', 'headerBeranda', 'headerSejarah', 'headerVisiMisi',
            'headerFasilitas', 'headerGuruStaf', 'headerEkskul', 'headerKurikulum',
            'headerAlumni', 'headerProgramJurusan', 'headerPrestasi', 'headerPendaftaran',
            'headerBerita', 'headerGaleri', 'headerArtikel', 'headerUnduhan',
            'benefitFasilitasImage', 'benefitGuruImage', 'benefitPrestasiImage',
            'programCoverImage', 'loginBackground', 'ppdbBackgroundImage', 'galleryBackgroundImage'
        ];

        // Bersihkan teks domain lama/localhost secara dinamis mengikuti server aktif
        foreach ($settings as $key => $value) {
            if (in_array($key, $fileKeys) && $value) {
                
                // 👈 JAGA-JAGA: Jika di DB terlanjur tersimpan URL utuh (http/https), ekstrak nama file/path aslinya saja
                if (str_starts_with($value, 'http')) {
                    // Ambil path setelah kata '/storage/'
                    $parts = explode('/storage/', $value);
                    $cleanPath = end($parts);
                } else {
                    $cleanPath = str_replace('storage/', '', $value);
                }

                // Bungkus murni menggunakan domain server yang aktif saat ini secara dinamis
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

        $fileKeys = [
            'logo', 'favicon', 'headerBeranda', 'headerSejarah', 'headerVisiMisi',
            'headerFasilitas', 'headerGuruStaf', 'headerEkskul', 'headerKurikulum',
            'headerAlumni', 'headerProgramJurusan', 'headerPrestasi', 'headerPendaftaran',
            'headerBerita', 'headerGaleri', 'headerArtikel', 'headerUnduhan',
            'benefitFasilitasImage', 'benefitGuruImage', 'benefitPrestasiImage',
            'programCoverImage', 'loginBackground', 'ppdbBackgroundImage', 'galleryBackgroundImage'
        ];

        foreach ($data as $key => $value) {
            if (in_array($key, $fileKeys) && $value) {
                if (preg_match('/^data:(image|video)\/(\w+);base64,/', $value)) {

                    $oldSetting = Setting::where('key', $key)->first();
                    $oldPath = $oldSetting ? $oldSetting->value : null;

                    // Bersihkan oldPath dari balutan URL jika ada sebelum dihapus lewat trait
                    if ($oldPath && str_starts_with($oldPath, 'http')) {
                        $parts = explode('/storage/', $oldPath);
                        $oldPath = end($parts);
                    }

                    // Panggil trait untuk menyimpan file fisik baru
                    $relativePath = $this->processAndSaveImage($value, 'settings', $oldPath);

                    // 👈 BEST PRACTICE: Di database cukup simpan path relatifnya saja (misal: settings/foto.webp)
                    $value = $relativePath;
                } elseif (str_starts_with($value, 'http')) {
                    // Jika data tidak diubah (masih URL lama dari frontend), bersihkan domainnya sebelum masuk DB kembali
                    $parts = explode('/storage/', $value);
                    $value = end($parts);
                }
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Wajib hapus cache pengaturan agar perubahannya langsung segar kembali
        Cache::forget('global_settings');

        // Panggil method index() internal agar respons data yang dikembalikan langsung berbalut URL bersih
        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan.',
            'data' => Setting::pluck('value', 'key')->all()
        ]);
    }
}