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
    public function getStats()
    {
        // Cache total siswa selama 10 menit (600 detik)
        $totalSiswa = Cache::remember('dashboard_total_siswa', 600, function () {
            return Student::where('status', 'aktif')->count();
        });
        
        // Cache total guru selama 10 menit
        $totalGuru = Cache::remember('dashboard_total_guru', 600, function () {
            return Staff::where('category', 'pendidik')->count();
        });
        
        // Cache jumlah artikel selama 5 menit
        $totalArtikel = Cache::remember('dashboard_total_artikel', 300, function () {
            return News::count();
        });
        
        // Menghitung jumlah pengunjung unik hari ini
        $pengunjungHariIni = Cache::remember('dashboard_pengunjung_hari_ini', 60, function () {
            return Visitor::whereDate('visited_date', Carbon::today())->count(); 
        });

        // Menyiapkan data chart pengunjung untuk 7 hari terakhir
        $chartDataObj = Cache::remember('dashboard_chart_visitor', 3600, function () {
            $labels = [];
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $labels[] = $date->format('d M');
                $data[] = Visitor::whereDate('visited_date', $date->toDateString())->count();
            }
            return ['labels' => $labels, 'data' => $data];
        });
        $chartLabels = $chartDataObj['labels'];
        $chartData = $chartDataObj['data'];

        // Mengambil data aktivitas terbaru dari Berita dan Galeri
        $recentNews = News::with('author')->orderBy('created_at', 'desc')->take(5)->get()->map(function ($item) {
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

        $recentGalleries = Gallery::with('author')->orderBy('created_at', 'desc')->take(5)->get()->map(function ($item) {
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

        // Gabungkan, urutkan berdasarkan waktu terbaru, dan ambil 5 teratas
        $recentActivities = $recentNews->concat($recentGalleries)
            ->sortByDesc('created_at')
            ->take(5)
            ->values()
            ->all();

        return response()->json([
            'data' => [
                'total_siswa' => $totalSiswa,
                'total_guru' => $totalGuru,
                'total_artikel' => $totalArtikel,
                'pengunjung_hari_ini' => $pengunjungHariIni,
                'chart' => [
                    'labels' => $chartLabels,
                    'data' => $chartData
                ],
                'recent_activities' => $recentActivities
            ]
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