<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait ImageUploadTrait
{
    /**
     * Proses dan simpan gambar (Mendukung Base64 atau UploadedFile).
     * Melakukan pengecilan (resize), konversi ke WebP, dan menghapus file lama.
     * 
     * @param mixed  $imageInput Data base64 atau object UploadedFile
     * @param string $directory  Direktori penyimpanan (misal: 'settings', 'students')
     * @param string $oldFilePath Path atau URL file lama yang akan dihapus
     * @param int    $maxWidth   Maksimal resolusi lebar gambar
     * @return string Path relatif file yang baru disimpan
     */
    public function processAndSaveImage($imageInput, $directory, $oldFilePath = null, $maxWidth = 1024)
    {
        $fileDecoded = null;
        $extension = null;
        $fileType = 'image';
        
        // 1. Cek apakah input adalah Base64
        if (is_string($imageInput) && preg_match('/^data:(image|video)\/(\w+);base64,/', $imageInput, $type)) {
            $fileType = strtolower($type[1]);
            $extension = strtolower($type[2]);
            $valueData = substr($imageInput, strpos($imageInput, ',') + 1);
            $valueData = str_replace(' ', '+', $valueData);
            $fileDecoded = base64_decode($valueData);
        } 
        // 2. Cek apakah input adalah file upload standar Laravel (UploadedFile)
        elseif ($imageInput instanceof UploadedFile) {
            $extension = strtolower($imageInput->getClientOriginalExtension());
            $fileType = explode('/', $imageInput->getMimeType())[0]; // e.g., 'image' dari 'image/jpeg'
            $fileDecoded = file_get_contents($imageInput->getRealPath());
        } else {
            return $imageInput; // Kembalikan nilai asli jika format tidak dikenali
        }

        // 3. Proses Resize & Konversi ke WebP (Abaikan Video/SVG/Ico)
        if ($fileType === 'image' && !in_array($extension, ['svg', 'svg+xml', 'ico'])) {
            $image = @imagecreatefromstring($fileDecoded);
            if ($image !== false) {
                $width = imagesx($image);
                $height = imagesy($image);

                if ($width > $maxWidth) {
                    $ratio = $maxWidth / $width;
                    $newWidth = round($width * $ratio);
                    $newHeight = round($height * $ratio);

                    $newImage = imagecreatetruecolor($newWidth, $newHeight);
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);

                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagedestroy($image);
                    $image = $newImage;
                }

                ob_start();
                imagewebp($image, null, 80); // Kualitas WebP
                $fileDecoded = ob_get_clean();
                $extension = 'webp';
                imagedestroy($image);
            }
        }

        // 4. Generate nama file unik
        $fileName = $directory . '/' . uniqid() . '_' . time() . '.' . $extension;

        // 5. Hapus file lama di storage sebelum menyimpan yang baru
        $this->deleteOldImage($oldFilePath);

        // 6. Simpan file baru
        Storage::disk('public')->put($fileName, $fileDecoded);

        return $fileName; // Mengembalikan path relatif misal: 'settings/1234_time.webp'
    }

    /**
     * Hapus gambar lama dari storage.
     * 
     * @param string|null $oldFilePath Path relatif atau URL penuh
     */
    public function deleteOldImage($oldFilePath)
    {
        if ($oldFilePath) {
            // Otomatis ekstrak path relatif jika ternyata old path berupa URL penuh
            if (str_contains($oldFilePath, url('storage'))) {
                $oldFilePath = str_replace(url('storage') . '/', '', $oldFilePath);
            }
            
            if (Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }
        }
    }
}