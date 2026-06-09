<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\DismissedNotification;
use App\Http\Resources\NewsResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        // Hanya tampilkan berita yang sudah disetujui di halaman utama Admin Berita
        $news = News::with('author')->where('status', 'approved')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => NewsResource::collection($news)]);
    }

    public function myPendingNews(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Mengambil berita yang pending/rejected milik user yang sedang login (Admin biasa)
        $news = News::with('author')->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'rejected'])
            ->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => NewsResource::collection($news)]);
    }

    public function publicIndex()
    {
        // Hanya mengambil berita yang sudah disetujui (approved) untuk halaman publik
        $news = News::with('author')->where('status', 'approved')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => NewsResource::collection($news)]);
    }

    public function publicShow(string $id)
    {
        // Menampilkan detail berita publik hanya jika statusnya approved
        $news = News::with('author')->where('status', 'approved')->findOrFail($id);
        $news->increment('views'); // Update view count ketika dibaca dari halaman publik
        return response()->json(['data' => new NewsResource($news)]);
    }

    public function show(string $id)
    {
        $news = News::with('author')->findOrFail($id);
        return response()->json(['data' => new NewsResource($news)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string'
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated['images'] = $this->handleMultipleImages(
            $validated['images'] ?? [],
            [], // Tidak ada gambar lama saat membuat baru
            'news'
        );
        $validated['views'] = 0;
        $validated['user_id'] = $user->id;
        
        // Super admin langsung approved, sedangkan admin biasa menunggu persetujuan
        $validated['status'] = $user->role === 'super_admin' ? 'approved' : 'pending';

        $news = News::create($validated);

        if ($validated['status'] === 'pending') {
            DismissedNotification::where('notification_id', 'val_news')->delete();
        }

        Cache::forget('dashboard_total_artikel');

        return response()->json(['message' => 'Berita berhasil ditambahkan', 'data' => new NewsResource($news)], 201);
    }

    public function update(Request $request, string $id)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $news = News::findOrFail($id);
        
        // Mencegah SEMUA user (termasuk super admin) mengubah berita milik akun lain
        if ($news->user_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki hak akses untuk mengubah berita ini.'], 403);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string'
        ]);

        $validated['images'] = $this->handleMultipleImages(
            $validated['images'] ?? [],
            $news->images ?? [],
            'news'
        );
        
        // Jika admin biasa mengedit berita yang ditolak atau telah disetujui, kembalikan ke pending
        if ($user->role !== 'super_admin') {
            $validated['status'] = 'pending';
            $validated['rejection_note'] = null;
        }
        
        $news->update($validated);

        if (isset($validated['status']) && $validated['status'] === 'pending') {
            DismissedNotification::where('notification_id', 'val_news')->delete();
        }

        Cache::forget('dashboard_total_artikel');

        return response()->json(['message' => 'Berita berhasil diperbarui', 'data' => new NewsResource($news)]);
    }

    public function destroy(Request $request, string $id)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $news = News::findOrFail($id);

        // Mencegah admin menghapus berita milik akun lain
        if ($user->role !== 'super_admin' && $news->user_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki hak akses untuk menghapus berita ini.'], 403);
        }
        
        // Hapus semua gambar terkait berita ini dari storage
        if (!empty($news->images)) {
            foreach ($news->images as $imagePath) {
                $this->deleteOldImage($imagePath);
            }
        }
        $news->delete();

        Cache::forget('dashboard_total_artikel');

        return response()->json(['message' => 'Berita berhasil dihapus']);
    }

    public function pendingNews()
    {
        $news = News::with('author')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => NewsResource::collection($news)]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $news = News::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_note' => 'nullable|string'
        ]);

        $news->update($validated);

        // Hapus status dibaca agar admin penulis berita mendapatkan kembali notifikasi bahwa beritanya ditolak
        if ($validated['status'] === 'rejected') {
            DismissedNotification::where('user_id', $news->user_id)->where('notification_id', 'rej_news')->delete();
        } elseif ($validated['status'] === 'approved') {
            DismissedNotification::where('user_id', $news->user_id)->where('notification_id', 'appr_news')->delete();
        }

        return response()->json(['message' => 'Status berita berhasil diperbarui', 'data' => new NewsResource($news)]);
    }

    private function handleMultipleImages(array $newImageData, array $oldImagePaths, string $storagePath): array
    {
        $newPaths = [];
        $existingUrls = [];

        foreach ($newImageData as $image) {
            if (str_starts_with($image, 'data:image')) {
                $path = $this->processAndSaveImage($image, $storagePath, null, 1024);
                if ($path) {
                    $newPaths[] = $path;
                    $existingUrls[] = \Illuminate\Support\Facades\Storage::url($path);
                }
            } elseif (str_starts_with($image, 'http')) {
                $existingUrls[] = $image;
                $newPaths[] = str_replace(\Illuminate\Support\Facades\Storage::url(''), '', $image);
            }
        }

        $oldImageUrls = array_map(fn($path) => \Illuminate\Support\Facades\Storage::url($path), $oldImagePaths);
        $imagesToDelete = array_diff($oldImageUrls, $existingUrls);

        foreach ($imagesToDelete as $url) $this->deleteOldImage($url);

        return $newPaths;
    }
}