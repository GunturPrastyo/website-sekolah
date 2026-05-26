<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\DismissedNotification;
use App\Http\Resources\GalleryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        // Hanya tampilkan galeri yang sudah disetujui di halaman utama Admin Galeri
        $galleries = Gallery::with('author')->where('status', 'approved')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => GalleryResource::collection($galleries)]);
    }

    public function publicIndex()
    {
        // Untuk halaman publik
        $galleries = Gallery::with('author')->where('status', 'approved')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => GalleryResource::collection($galleries)]);
    }

    public function myPendingGalleries(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Mengambil galeri yang pending/rejected milik user yang sedang login (Admin biasa)
        $galleries = Gallery::with('author')->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'rejected'])
            ->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => GalleryResource::collection($galleries)]);
    }

    public function pendingGalleries()
    {
        // Galeri pending yang butuh di-approve oleh Super Admin
        $galleries = Gallery::with('author')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => GalleryResource::collection($galleries)]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'images' => 'required|array|min:1',
            'images.*' => 'required|string',
        ]);

        $createdGalleries = [];
        
        /** @var \App\Models\User $user */
        $user = $request->user();
        $status = $user->role === 'super_admin' ? 'approved' : 'pending';

        foreach ($validatedData['images'] as $imgData) {
            $imagePath = $imgData;
            
            if (str_starts_with($imgData, 'data:image')) {
                preg_match('/data:image\/(\w+);base64,/', $imgData, $type);
                $imageData = base64_decode(substr($imgData, strpos($imgData, ',') + 1));
                $filename = 'gallery/' . time() . '_' . uniqid() . '.' . ($type[1] ?? 'jpg');
                Storage::disk('public')->put($filename, $imageData);
                $imagePath = $filename;
            }

            $gallery = Gallery::create([
                'title' => $validatedData['title'],
                'category' => $validatedData['category'],
                'image' => $imagePath,
                'likes' => 0,
                'status' => $status,
                'user_id' => $user->id
            ]);

            $createdGalleries[] = $gallery;
        }

        if ($status === 'pending') {
            DismissedNotification::where('notification_id', 'val_gallery')->delete();
        }

        return response()->json(['data' => GalleryResource::collection($createdGalleries)], 201);
    }

    public function update(Request $request, Gallery $gallery)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Mencegah admin menghapus/mengubah galeri milik akun lain (kecuali super_admin)
        if ($gallery->user_id !== $user->id && $user->role !== 'super_admin') {
            return response()->json(['message' => 'Anda tidak memiliki hak akses untuk mengubah galeri ini.'], 403);
        }
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'images' => 'required|array|min:1',
            'images.*' => 'required|string',
        ]);

        $status = $gallery->status;
        $rejectionNote = $gallery->rejection_note;
        
        // Jika admin biasa mengedit galeri yang ditolak atau telah disetujui, kembalikan ke pending
        if ($user->role !== 'super_admin') {
            $status = 'pending';
            $rejectionNote = null;
        }

        $createdGalleries = [];

        foreach ($validatedData['images'] as $index => $imgData) {
            $imagePath = $imgData;
            $isNewFile = false;

            if (str_starts_with($imgData, 'data:image')) {
                preg_match('/data:image\/(\w+);base64,/', $imgData, $type);
                $imageData = base64_decode(substr($imgData, strpos($imgData, ',') + 1));
                $filename = 'gallery/' . time() . '_' . uniqid() . '.' . ($type[1] ?? 'jpg');
                Storage::disk('public')->put($filename, $imageData);
                $imagePath = $filename;
                $isNewFile = true;
            } elseif (str_starts_with($imgData, 'http')) {
                $baseUrl = asset('storage/') . '/';
                if (str_starts_with($imgData, $baseUrl)) {
                    $imagePath = str_replace($baseUrl, '', $imgData);
                }
            }

            // Update the existing row with the first image, create new rows for additional images
            if ($index === 0) {
                if ($isNewFile && $gallery->image && !str_starts_with($gallery->image, 'http')) {
                    Storage::disk('public')->delete($gallery->image);
                }

                $gallery->update([
                    'title' => $validatedData['title'],
                    'category' => $validatedData['category'],
                    'image' => $imagePath,
                    'status' => $status,
                    'rejection_note' => $rejectionNote
                ]);
                
                $createdGalleries[] = $gallery;
            } else {
                $newGallery = Gallery::create([
                    'title' => $validatedData['title'],
                    'category' => $validatedData['category'],
                    'image' => $imagePath,
                    'likes' => 0,
                    'status' => $status,
                    'user_id' => $user->id
                ]);
                
                $createdGalleries[] = $newGallery;
            }
        }

        if ($status === 'pending') {
            DismissedNotification::where('notification_id', 'val_gallery')->delete();
        }

        return response()->json(['data' => GalleryResource::collection($createdGalleries)]);
    }

    public function destroy(Request $request, Gallery $gallery)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Mencegah admin menghapus galeri milik akun lain
        if ($user->role !== 'super_admin' && $gallery->user_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki hak akses untuk menghapus galeri ini.'], 403);
        }

        if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
            Storage::disk('public')->delete($gallery->image);
        }
        $gallery->delete();

        return response()->json(['message' => 'Gallery deleted successfully']);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id'
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $galleries = Gallery::whereIn('id', $validated['ids'])->get();

        /** @var \App\Models\Gallery $gallery */
        foreach ($galleries as $gallery) {
            // Melewati galeri yang bukan miliknya untuk Admin biasa
            if ($user->role !== 'super_admin' && $gallery->user_id !== $user->id) {
                continue;
            }

            if ($gallery->image && !str_starts_with($gallery->image, 'http')) {
                Storage::disk('public')->delete($gallery->image);
            }
            $gallery->delete();
        }

        return response()->json(['message' => 'Galleries deleted successfully']);
    }

    public function updateStatus(Request $request, string $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_note' => 'nullable|string'
        ]);

        $gallery->update($validated);

        // Hapus status dibaca agar admin pengunggah mendapatkan kembali notifikasi penolakan
        if ($validated['status'] === 'rejected') {
            DismissedNotification::where('user_id', $gallery->user_id)->where('notification_id', 'rej_gallery')->delete();
        }

        return response()->json(['message' => 'Status galeri berhasil diperbarui', 'data' => new GalleryResource($gallery)]);
    }
}