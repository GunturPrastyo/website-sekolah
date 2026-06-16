<?php

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\VisionMissionController;
use App\Http\Controllers\SchoolProfileController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SchoolVideoController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MapLocationController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CurriculumSubjectController;
use App\Http\Controllers\PancasilaProfileController;
use App\Http\Controllers\LessonScheduleController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\PpdbInfoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Info PPDB (Akses Publik untuk halaman pendaftaran)
Route::get('/ppdb-info', [PpdbInfoController::class, 'index']);

// API Pengaturan Umum (Akses Publik untuk mengambil data konfigurasi dan tampilan)
Route::get('/settings', [SettingController::class, 'index']);

// API Statistik Pengunjung (Akses Publik)
Route::get('/visitor-stats', [SettingController::class, 'visitorStats']);

// API Statistik Sekolah (Akses Publik untuk landing page)
Route::get('/public-stats', [SettingController::class, 'publicStats']);

// API Tracking Pengunjung
Route::get('/track-visitor', [VisitorController::class, 'track']);

// API Berita (Akses Publik)
Route::get('/public-news', [NewsController::class, 'publicIndex']);
Route::get('/public-news/{id}', [NewsController::class, 'publicShow']);

// API Galeri (Akses Publik)
Route::get('/public-galleries', [GalleryController::class, 'publicIndex']);
Route::post('/public-galleries/{id}/like', [GalleryController::class, 'toggleLike']);
Route::get('/public-galleries/{id}/download', [GalleryController::class, 'download']);

// API Video Profil Sekolah (Akses Publik)
Route::get('/public-school-video', [SchoolVideoController::class, 'show']);

// API Visi & Misi (Akses Publik)
Route::get('/vision-mission', [VisionMissionController::class, 'index']);

// API Profil Sekolah & Sejarah (Akses Publik)
Route::get('/profil-sekolah', [SchoolProfileController::class, 'show']);
Route::get('/sejarah', [TimelineController::class, 'index']);

// API Fasilitas (Akses Publik)
Route::get('/fasilitas', [FacilityController::class, 'index']);

// API Guru & Staf (Akses Publik)
Route::get('/guru-staf', [StaffController::class, 'index']);

// API Program Jurusan (Akses Publik)
Route::get('/public-programs', [ProgramController::class, 'index']);

// API Kurikulum (Akses Publik)
Route::get('/public-curriculum-subjects', [CurriculumSubjectController::class, 'index']);

// API Profil Pelajar Pancasila (Akses Publik)
Route::get('/public-pancasila-profile', [PancasilaProfileController::class, 'show']);

// API Ekstrakurikuler (Akses Publik)
Route::get('/public-extracurriculars', [ExtracurricularController::class, 'index']);

// API Prestasi (Akses Publik)
Route::get('/public-achievements', [AchievementController::class, 'index']);

// API Agenda (Akses Publik)
Route::get('/public-agendas', [AgendaController::class, 'index']);

// API Unduhan (Akses Publik)
Route::get('/public-downloads', [DownloadController::class, 'index']);

// API Data Persebaran Peta Alumni (Akses Publik)
Route::get('/public-map-locations', [MapLocationController::class, 'index']);

// API Data Direktori Alumni (Akses Publik)
Route::get('/public-alumnis', [AlumniController::class, 'publicIndex']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // API Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    // API Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead']);
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead']);

    // API Pengaturan Akun Profil (Bisa diakses Super Admin & Admin Biasa)
    Route::post('/profile', [UserController::class, 'updateProfile']);

    // Rute yang BISA diakses oleh admin biasa (Berita, Galeri, Pengaturan, Profil)
    Route::apiResource('news', NewsController::class);
    Route::get('/my-pending-news', [NewsController::class, 'myPendingNews']);
    Route::get('/my-pending-galleries', [GalleryController::class, 'myPendingGalleries']);
    
    // API Galeri
    Route::get('/galleries', [GalleryController::class, 'index']);
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::put('/galleries/{gallery}', [GalleryController::class, 'update']);
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy']);
    Route::post('/galleries/bulk-delete', [GalleryController::class, 'bulkDelete']);
    
    // API Video Profil Sekolah (Akses baca untuk admin biasa dan super_admin)
    Route::get('/school-video', [SchoolVideoController::class, 'show']);

  // Group untuk Rute yang HANYA BOLEH diakses oleh Super Admin
  Route::middleware([CheckRole::class . ':super_admin'])->group(function () {
    // API Profil Sekolah
    // Rute untuk update profil. Dikirim sebagai POST dari frontend dengan _method 'PUT' untuk menangani upload file.
    Route::put('/profil-sekolah', [SchoolProfileController::class, 'update']);

    // API Sejarah (Lini Masa Timeline)
    Route::post('/sejarah', [TimelineController::class, 'store']);
    Route::put('/sejarah/{timeline}', [TimelineController::class, 'update']);
    Route::delete('/sejarah/{timeline}', [TimelineController::class, 'destroy']);

    // API khusus untuk memperbarui urutan timeline lewat drag & drop
    Route::post('/sejarah/reorder', [TimelineController::class, 'reorder']);

    // API Visi & Misi (Hanya Update yang butuh autentikasi Super Admin)
    Route::post('/vision-mission', [VisionMissionController::class, 'update']);

    // API Fasilitas
    Route::post('/fasilitas', [FacilityController::class, 'store']);
    Route::put('/fasilitas/{facility}', [FacilityController::class, 'update']);
    Route::delete('/fasilitas/{facility}', [FacilityController::class, 'destroy']);

    // API Data Siswa
    Route::post('/students/bulk-delete', [StudentController::class, 'bulkDelete']);
    Route::post('/students/bulk-update', [StudentController::class, 'bulkUpdate']);
    Route::post('/students/import', [StudentController::class, 'import']);
    Route::apiResource('students', StudentController::class);

    // API Data Guru & Staf
    Route::apiResource('staff', StaffController::class);

    // API Data Kelas
    Route::apiResource('school-classes', SchoolClassController::class);

    // API Data Alumni
    Route::get('/alumnis/unassigned-students', [AlumniController::class, 'unassignedStudents']);
    Route::post('/alumnis/bulk-update', [AlumniController::class, 'bulkUpdate']);
    Route::post('/alumnis/bulk-delete', [AlumniController::class, 'bulkDelete']);
    Route::apiResource('/alumnis', AlumniController::class);

    // API Data Persebaran Peta Alumni
    Route::apiResource('/map-locations', MapLocationController::class);

    // API Video Profil Sekolah
    Route::post('/school-video', [SchoolVideoController::class, 'update']);
    Route::delete('/school-video', [SchoolVideoController::class, 'destroy']);

    // API Unduhan
    Route::apiResource('downloads', DownloadController::class);

    // API Program Jurusan
    Route::apiResource('programs', ProgramController::class);

    // API Kurikulum (Mata Pelajaran)
    Route::apiResource('curriculum-subjects', CurriculumSubjectController::class);

    // API Profil Pelajar Pancasila
    Route::get('/pancasila-profile', [PancasilaProfileController::class, 'show']);
    Route::post('/pancasila-profile', [PancasilaProfileController::class, 'update']);

    // API Jadwal Pelajaran
    Route::apiResource('lesson-schedules', LessonScheduleController::class);

    // API Ekstrakurikuler
    Route::apiResource('extracurriculars', ExtracurricularController::class);

    // API Prestasi
    Route::apiResource('achievements', AchievementController::class);

    // API Agenda
    Route::apiResource('agendas', AgendaController::class);

    // API Manajemen Info PPDB (Akses Admin)
    Route::put('/ppdb-info', [PpdbInfoController::class, 'update']);
    Route::post('/ppdb-info/brosur', [PpdbInfoController::class, 'uploadBrosur']);
    Route::delete('/ppdb-info/brosur', [PpdbInfoController::class, 'deleteBrosur']);

    // API Pengaturan Umum (Akses Admin untuk menyimpan)
    Route::post('/settings', [SettingController::class, 'update']);

    // API Manajemen Pengguna
    Route::apiResource('users', UserController::class);

    // API Validasi Konten (Berita)
    Route::get('/validasi-konten/berita', [NewsController::class, 'pendingNews']);
    Route::put('/validasi-konten/berita/{news}/status', [NewsController::class, 'updateStatus']);

    // API Validasi Konten (Galeri)
    Route::get('/validasi-konten/galeri', [GalleryController::class, 'pendingGalleries']);
    Route::put('/validasi-konten/galeri/{gallery}/status', [GalleryController::class, 'updateStatus']);
  });
});
