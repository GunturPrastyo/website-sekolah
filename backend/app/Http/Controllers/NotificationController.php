<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Gallery;
use App\Models\DismissedNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = [];
        
        $dismissed = DismissedNotification::where('user_id', $user->id)->pluck('notification_id')->toArray();

        // Logika Notifikasi Super Admin
        if ($user->role === 'super_admin') {
            $pendingNewsCount = News::where('status', 'pending')->count();
            $pendingGalleryCount = Gallery::where('status', 'pending')->count();

            if ($pendingNewsCount > 0 && !in_array('val_news', $dismissed)) {
                $notifications[] = [
                    'id' => 'val_news',
                    'title' => 'Validasi Berita',
                    'message' => "Ada {$pendingNewsCount} berita menunggu persetujuan Anda.",
                    'link' => '/admin/validasi-konten',
                    'time' => 'Baru saja',
                    'type' => 'warning',
                ];
            }

            if ($pendingGalleryCount > 0 && !in_array('val_gallery', $dismissed)) {
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

            if ($rejectedNewsCount > 0 && !in_array('rej_news', $dismissed)) {
                $notifications[] = [
                    'id' => 'rej_news',
                    'title' => 'Revisi Berita',
                    'message' => "Ada {$rejectedNewsCount} draf berita yang ditolak dan butuh revisi.",
                    'link' => '/admin/draft-berita',
                    'time' => 'Baru saja',
                    'type' => 'error',
                ];
            }

            if ($rejectedGalleryCount > 0 && !in_array('rej_gallery', $dismissed)) {
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

    public function markRead(Request $request, $id)
    {
        DismissedNotification::firstOrCreate([
            'user_id' => $request->user()->id,
            'notification_id' => $id
        ]);

        return response()->json(['message' => 'Notifikasi berhasil ditandai dibaca']);
    }

    public function markAllRead(Request $request)
    {
        $user = $request->user();
        $notificationIds = [];

        if ($user->role === 'super_admin') {
            $notificationIds = ['val_news', 'val_gallery'];
        } else {
            $notificationIds = ['rej_news', 'rej_gallery'];
        }

        foreach ($notificationIds as $id) {
            DismissedNotification::firstOrCreate([
                'user_id' => $user->id,
                'notification_id' => $id
            ]);
        }

        return response()->json(['message' => 'Semua notifikasi berhasil ditandai dibaca']);
    }
}