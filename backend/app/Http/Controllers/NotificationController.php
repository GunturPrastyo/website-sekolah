<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Gallery;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = [];

        // Logika Notifikasi Super Admin
        if ($user->role === 'super_admin') {
            $pendingNewsCount = News::where('status', 'pending')->count();
            $pendingGalleryCount = Gallery::where('status', 'pending')->count();

            if ($pendingNewsCount > 0) {
                $notifications[] = [
                    'id' => 'val_news',
                    'title' => 'Validasi Berita',
                    'message' => "Ada {$pendingNewsCount} berita menunggu persetujuan Anda.",
                    'link' => '/admin/validasi-konten',
                    'time' => 'Baru saja',
                    'type' => 'warning',
                ];
            }

            if ($pendingGalleryCount > 0) {
                $notifications[] = [
                    'id' => 'val_gallery',
                    'title' => 'Validasi Galeri',
                    'message' => "Ada {$pendingGalleryCount} foto galeri menunggu persetujuan Anda.",
                    'link' => '/admin/validasi-konten',
                    'time' => 'Baru saja',
                    'type' => 'warning',
                ];
            }
        } else {
            // Logika Notifikasi Admin Biasa
            $rejectedNewsCount = News::where('user_id', $user->id)
                ->where('status', 'rejected')
                ->count();
                
            $rejectedGalleryCount = Gallery::where('user_id', $user->id)
                ->where('status', 'rejected')
                ->count();

            if ($rejectedNewsCount > 0) {
                $notifications[] = [
                    'id' => 'rej_news',
                    'title' => 'Revisi Berita',
                    'message' => "Ada {$rejectedNewsCount} draf berita yang ditolak dan butuh revisi.",
                    'link' => '/admin/draft-berita',
                    'time' => 'Baru saja',
                    'type' => 'error',
                ];
            }

            if ($rejectedGalleryCount > 0) {
                $notifications[] = [
                    'id' => 'rej_gallery',
                    'title' => 'Revisi Galeri',
                    'message' => "Ada {$rejectedGalleryCount} draf galeri yang ditolak dan butuh revisi.",
                    'link' => '/admin/draft-berita',
                    'time' => 'Baru saja',
                    'type' => 'error',
                ];
            }
        }

        return response()->json(['data' => $notifications]);
    }
}