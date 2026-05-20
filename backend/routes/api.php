<?php

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\VisionMissionController;
use App\Http\Controllers\SchoolProfileController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MapLocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // API Profil Sekolah
    Route::get('/profil-sekolah', [SchoolProfileController::class, 'show']);
    // Rute untuk update profil. Dikirim sebagai POST dari frontend dengan _method 'PUT' untuk menangani upload file.
    Route::put('/profil-sekolah', [SchoolProfileController::class, 'update']);

    // API Sejarah (Lini Masa Timeline)
    Route::get('/sejarah', [TimelineController::class, 'index']);
    Route::post('/sejarah', [TimelineController::class, 'store']);
    Route::put('/sejarah/{timeline}', [TimelineController::class, 'update']);
    Route::delete('/sejarah/{timeline}', [TimelineController::class, 'destroy']);

    // API khusus untuk memperbarui urutan timeline lewat drag & drop
    Route::post('/sejarah/reorder', [TimelineController::class, 'reorder']);

    // API Visi & Misi
    Route::get('/vision-mission', [VisionMissionController::class, 'index']);
    Route::post('/vision-mission', [VisionMissionController::class, 'update']);

    // API Fasilitas
    Route::get('/fasilitas', [FacilityController::class, 'index']);
    Route::post('/fasilitas', [FacilityController::class, 'store']);
    Route::put('/fasilitas/{facility}', [FacilityController::class, 'update']);
    Route::delete('/fasilitas/{facility}', [FacilityController::class, 'destroy']);

    // API Data Siswa
    Route::apiResource('students', StudentController::class);

    // API Data Guru & Staf
    Route::apiResource('staff', StaffController::class);

    // API Data Kelas
    Route::apiResource('school-classes', SchoolClassController::class);

    // API Berita / Pengumuman
    Route::apiResource('news', NewsController::class);

    // API Data Alumni
    Route::get('/alumnis/unassigned-students', [AlumniController::class, 'unassignedStudents']);
    Route::apiResource('/alumnis', AlumniController::class);

    // API Data Persebaran Peta Alumni
    Route::apiResource('/map-locations', MapLocationController::class);
});
