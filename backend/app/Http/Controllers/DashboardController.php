<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Staff;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $period = $request->query('period', '30_days');

        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Data khusus untuk admin biasa
        $adminData = [];
        if ($user && $user->role !== 'super_admin') {
            $artikelSaya = News::where('user_id', $user->id)->count();
            $galeriSaya = Gallery::where('user_id', $user->id)->count();
            $menungguValidasi = News::where('user_id', $user->id)->where('status', 'pending')->count() + Gallery::where('user_id', $user->id)->where('status', 'pending')->count();
            $kontenDitolak = News::where('user_id', $user->id)->where('status', 'rejected')->count() + Gallery::where('user_id', $user->id)->where('status', 'rejected')->count();

            $artikelSayaLastMonth = News::where('user_id', $user->id)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
            $galeriSayaLastMonth = Gallery::where('user_id', $user->id)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
            $menungguValidasiLastMonth = News::where('user_id', $user->id)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->where('status', 'pending')->count() + Gallery::where('user_id', $user->id)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->where('status', 'pending')->count();
            $kontenDitolakLastMonth = News::where('user_id', $user->id)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->where('status', 'rejected')->count() + Gallery::where('user_id', $user->id)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->where('status', 'rejected')->count();

            $artikelSayaChange = $artikelSayaLastMonth > 0 ? round((($artikelSaya - $artikelSayaLastMonth) / $artikelSayaLastMonth) * 100) : ($artikelSaya > 0 ? 100 : 0);
            $galeriSayaChange = $galeriSayaLastMonth > 0 ? round((($galeriSaya - $galeriSayaLastMonth) / $galeriSayaLastMonth) * 100) : ($galeriSaya > 0 ? 100 : 0);
            $menungguValidasiChange = $menungguValidasiLastMonth > 0 ? round((($menungguValidasi - $menungguValidasiLastMonth) / $menungguValidasiLastMonth) * 100) : ($menungguValidasi > 0 ? 100 : 0);
            $kontenDitolakChange = $kontenDitolakLastMonth > 0 ? round((($kontenDitolak - $kontenDitolakLastMonth) / $kontenDitolakLastMonth) * 100) : ($kontenDitolak > 0 ? 100 : 0);

            $adminData = [
                'artikel_saya' => $artikelSaya,
                'artikel_saya_change' => $artikelSayaChange,
                'galeri_saya' => $galeriSaya,
                'galeri_saya_change' => $galeriSayaChange,
                'menunggu_validasi' => $menungguValidasi,
                'menunggu_validasi_change' => $menungguValidasiChange,
                'konten_ditolak' => $kontenDitolak,
                'konten_ditolak_change' => $kontenDitolakChange,
            ];
        }

        // Cache total siswa selama 10 menit (600 detik)
        $totalSiswa = Cache::remember('dashboard_total_siswa', 600, function () {
            return Student::where('status', 'aktif')->count();
        });
        $totalSiswaLastMonth = Student::where('status', 'aktif')->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $totalSiswaChange = $totalSiswaLastMonth > 0 ? round((($totalSiswa - $totalSiswaLastMonth) / $totalSiswaLastMonth) * 100) : ($totalSiswa > 0 ? 10 : 0);
        
        // Cache total guru selama 10 menit
        $totalGuru = Cache::remember('dashboard_total_guru', 600, function () {
            return Staff::where('category', 'pendidik')->count();
        });
        $totalGuruLastMonth = Staff::where('category', 'pendidik')->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $totalGuruChange = $totalGuruLastMonth > 0 ? round((($totalGuru - $totalGuruLastMonth) / $totalGuruLastMonth) * 100) : ($totalGuru > 0 ? 5 : 0);
        
        // Cache jumlah artikel selama 5 menit
        $totalArtikel = Cache::remember('dashboard_total_artikel', 300, function () {
            return News::count();
        });
        $totalArtikelLastMonth = News::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $totalArtikelChange = $totalArtikelLastMonth > 0 ? round((($totalArtikel - $totalArtikelLastMonth) / $totalArtikelLastMonth) * 100) : ($totalArtikel > 0 ? 100 : 0);
        
        // Menghitung jumlah pengunjung unik hari ini
        $pengunjungHariIni = Cache::remember('dashboard_pengunjung_hari_ini', 60, function () {
            return Visitor::whereDate('visited_date', Carbon::today())->count(); 
        });
        $pengunjungKemarin = Visitor::whereDate('visited_date', Carbon::yesterday())->count();
        $pengunjungHariIniChange = $pengunjungKemarin > 0 ? round((($pengunjungHariIni - $pengunjungKemarin) / $pengunjungKemarin) * 100) : ($pengunjungHariIni > 0 ? 100 : 0);

        // Menyiapkan data chart pengunjung
        if ($period === 'this_year') {
            $chartDataObj = Cache::remember('dashboard_chart_visitor_year', 3600, function () {
                $labels = [];
                $data = [];
                for ($i = 1; $i <= Carbon::now()->month; $i++) {
                    $labels[] = Carbon::create()->month($i)->translatedFormat('M');
                    $data[] = Visitor::whereMonth('visited_date', $i)->whereYear('visited_date', Carbon::now()->year)->count();
                }
                return ['labels' => $labels, 'data' => $data];
            });
        } elseif ($period === '30_days') {
             $chartDataObj = Cache::remember('dashboard_chart_visitor_30', 3600, function () {
                $labels = [];
                $data = [];
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('d/m');
                    $data[] = Visitor::whereDate('visited_date', $date->toDateString())->count();
                }
                return ['labels' => $labels, 'data' => $data];
            });
        } else {
            $chartDataObj = Cache::remember('dashboard_chart_visitor_7', 3600, function () {
                $labels = [];
                $data = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('d M');
                    $data[] = Visitor::whereDate('visited_date', $date->toDateString())->count();
                }
                return ['labels' => $labels, 'data' => $data];
            });
        }

        $chartLabels = $chartDataObj['labels'];
        $chartData = $chartDataObj['data'];

        // Menentukan jumlah maksimal aktivitas terbaru yang ingin ditampilkan
        $activityLimit = 15;

        // Mengambil data aktivitas terbaru dari Berita dan Galeri
        $newsQuery = News::with('author')->orderBy('created_at', 'desc')->take($activityLimit);
        $galleryQuery = Gallery::with('author')->orderBy('created_at', 'desc')->take($activityLimit);

        if ($user && $user->role !== 'super_admin') {
            $newsQuery->where('user_id', $user->id);
            $galleryQuery->where('user_id', $user->id);
        }

        $recentNews = $newsQuery->get()->map(function ($item) {
            $authorName = $item->author ? $item->author->name : 'Admin';
            return [
                'id' => 'news_' . $item->id,
                'user' => $authorName,
                'user_initials' => $this->getInitials($authorName),
                'action' => 'menerbitkan berita/artikel baru',
                'target' => '"' . $item->title . '"',
                'created_at' => $item->created_at,
                'color' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600',
            ];
        });

        $recentGalleries = $galleryQuery->get()->map(function ($item) {
            $authorName = $item->author ? $item->author->name : 'Admin';
            return [
                'id' => 'gallery_' . $item->id,
                'user' => $authorName,
                'user_initials' => $this->getInitials($authorName),
                'action' => 'mengunggah galeri foto',
                'target' => '"' . $item->title . '"',
                'created_at' => $item->created_at,
                'color' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600',
            ];
        });

        // Gabungkan, urutkan berdasarkan waktu terbaru, dan ambil teratas sesuai limit
        $recentActivities = $recentNews->concat($recentGalleries)
            ->sortByDesc('created_at')
            ->take($activityLimit)
            ->values()
            ->all();

        $responseData = [
            'total_siswa' => $totalSiswa,
            'total_siswa_change' => $totalSiswaChange,
            'total_guru' => $totalGuru,
            'total_guru_change' => $totalGuruChange,
            'total_artikel' => $totalArtikel,
            'total_artikel_change' => $totalArtikelChange,
            'pengunjung_hari_ini' => $pengunjungHariIni,
            'pengunjung_hari_ini_change' => $pengunjungHariIniChange,
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData
            ],
            'recent_activities' => $recentActivities
        ];

        if ($user && $user->role !== 'super_admin') {
            $responseData = array_merge($responseData, $adminData);
        }

        return response()->json([
            'data' => $responseData
        ]);
    }

    private function getInitials($name)
    {
        $words = explode(' ', trim($name));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= strtoupper($w[0]);
            }
        }
        return substr($initials, 0, 2);
    }
}