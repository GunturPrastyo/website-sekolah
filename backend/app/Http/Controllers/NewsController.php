<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\DismissedNotification;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        // Hanya tampilkan berita yang sudah disetujui di halaman utama Admin Berita
        $news = News::with('author')->where('status', 'approved')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => NewsResource::collection($news)]);
    }

    public function myPendingNews()
    {
        // Mengambil berita yang pending/rejected milik user yang sedang login (Admin biasa)
        $news = News::with('author')->where('user_id', auth()->id())
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

    public function publicShow($id)
    {
        // Menampilkan detail berita publik hanya jika statusnya approved
        $news = News::with('author')->where('status', 'approved')->findOrFail($id);
        $news->increment('views'); // Update view count ketika dibaca dari halaman publik
        return response()->json(['data' => new NewsResource($news)]);
    }

    public function show($id)
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

        $validated['images'] = $this->handleBase64Images($validated['images'] ?? []);
        $validated['views'] = 0;
        $validated['user_id'] = auth()->id();
        
        // Super admin langsung approved, sedangkan admin biasa menunggu persetujuan
        $validated['status'] = auth()->user()->role === 'super_admin' ? 'approved' : 'pending';

        $news = News::create($validated);

        if ($validated['status'] === 'pending') {
            DismissedNotification::where('notification_id', 'val_news')->delete();
        }

        return response()->json(['message' => 'Berita berhasil ditambahkan', 'data' => new NewsResource($news)], 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        // Mencegah SEMUA user (termasuk super admin) mengubah berita milik akun lain
        if ($news->user_id !== auth()->id()) {
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

        $validated['images'] = $this->handleBase64Images($validated['images'] ?? []);
        
        // Jika admin biasa mengedit berita yang ditolak atau telah disetujui, kembalikan ke pending
        if (auth()->user()->role !== 'super_admin') {
            $validated['status'] = 'pending';
            $validated['rejection_note'] = null;
        }
        
        $news->update($validated);

        if (isset($validated['status']) && $validated['status'] === 'pending') {
            DismissedNotification::where('notification_id', 'val_news')->delete();
        }

        return response()->json(['message' => 'Berita berhasil diperbarui', 'data' => new NewsResource($news)]);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Mencegah admin menghapus berita milik akun lain
        if (auth()->user()->role !== 'super_admin' && $news->user_id !== auth()->id()) {
            return response()->json(['message' => 'Anda tidak memiliki hak akses untuk menghapus berita ini.'], 403);
        }
        
        $news->delete();
        return response()->json(['message' => 'Berita berhasil dihapus']);
    }

    public function pendingNews()
    {
        $news = News::with('author')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => NewsResource::collection($news)]);
    }

    public function updateStatus(Request $request, $id)
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
        }

        return response()->json(['message' => 'Status berita berhasil diperbarui', 'data' => new NewsResource($news)]);
    }

    private function handleBase64Images(array $images)
    {
        $processedImages = [];
        foreach ($images as $image) {
            if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
                $imageData = substr($image, strpos($image, ',') + 1);
                $imageData = base64_decode($imageData);
                $extension = strtolower($type[1]);
                $filename = 'news/' . time() . '_' . uniqid() . '.' . $extension;
                Storage::disk('public')->put($filename, $imageData);
                
                $processedImages[] = asset('storage/' . $filename);
            } else {
                $processedImages[] = $image;
            }
        }
        return $processedImages;
    }
}