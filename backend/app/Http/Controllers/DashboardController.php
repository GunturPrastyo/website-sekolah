<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Staff;
use App\Models\News;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats()
    {
        // Menghitung data dari database berdasarkan model
        $totalSiswa = Student::count();
        
        // Mengambil jumlah staf dengan kategori pendidik (guru)
        $totalGuru = Staff::where('category', 'pendidik')->count();
        
        // Menghitung jumlah berita / artikel
        $totalArtikel = News::count();
        
        // Menghitung jumlah pengunjung unik hari ini
        $pengunjungHariIni = Visitor::whereDate('visited_date', Carbon::today())->count(); 

        // Menyiapkan data chart pengunjung untuk 7 hari terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M'); // Format misal: "18 May"
            $chartData[] = Visitor::whereDate('visited_date', $date->toDateString())->count();
        }

        return response()->json([
            'data' => [
                'total_siswa' => $totalSiswa,
                'total_guru' => $totalGuru,
                'total_artikel' => $totalArtikel,
                'pengunjung_hari_ini' => $pengunjungHariIni,
                'chart' => [
                    'labels' => $chartLabels,
                    'data' => $chartData
                ]
            ]
        ]);
    }
}