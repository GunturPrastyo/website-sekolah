<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PancasilaProfile;

class PancasilaProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dimensions = [
            [
                'id' => 1,
                'name' => 'Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia',
                'desc' => 'Pelajar Indonesia yang menghayati ajaran agama dan kepercayaannya serta menerapkan pemahaman tersebut dalam kehidupan sehari-hari. Pelajar memahami unsur-unsur penting akhlak beragama, akhlak pribadi, akhlak kepada manusia, akhlak kepada alam, dan akhlak bernegara.',
                'icon' => 'PhHandsPraying',
                'color' => 'text-blue-500'
            ],
            [
                'id' => 2,
                'name' => 'Berkebinekaan Global',
                'desc' => 'Pelajar Indonesia mempertahankan budaya luhur, lokalitas, dan identitasnya, namun tetap berpikiran terbuka dalam berinteraksi dengan budaya lain. Dimensi ini menumbuhkan rasa saling menghargai dan menyelaraskan perbedaan budaya tanpa menghilangkan identitas asli bangsa.',
                'icon' => 'PhGlobe',
                'color' => 'text-teal-500'
            ],
            [
                'id' => 3,
                'name' => 'Bergotong Royong',
                'desc' => 'Pelajar Indonesia memiliki kemampuan untuk melakukan kegiatan secara bersama-sama dengan sukarela agar kegiatan yang dikerjakan dapat berjalan lancar, mudah, dan ringan. Elemen kunci dimensi ini adalah kolaborasi, kepedulian, dan berbagi.',
                'icon' => 'PhHandshake',
                'color' => 'text-green-500'
            ],
            [
                'id' => 4,
                'name' => 'Mandiri',
                'desc' => 'Pelajar Indonesia merupakan pelajar yang bertanggung jawab atas proses dan hasil belajarnya. Elemen kunci dari mandiri terdiri dari kesadaran akan diri dan situasi yang dihadapi serta regulasi diri (kemampuan mengatur emosi, pikiran, dan perilaku).',
                'icon' => 'PhPerson',
                'color' => 'text-orange-500'
            ],
            [
                'id' => 5,
                'name' => 'Bernalar Kritis',
                'desc' => 'Pelajar Indonesia mampu secara objektif memproses informasi baik kualitatif maupun kuantitatif, membangun keterkaitan antara berbagai informasi, menganalisis informasi, mengevaluasi, dan menyimpulkannya guna mengambil keputusan yang tepat.',
                'icon' => 'PhBrain',
                'color' => 'text-purple-500'
            ],
            [
                'id' => 6,
                'name' => 'Kreatif',
                'desc' => 'Pelajar Indonesia mampu memodifikasi dan menghasilkan sesuatu yang orisinal, bermakna, bermanfaat, dan berdampak. Elemen kuncinya meliputi menghasilkan gagasan yang orisinal serta menghasilkan karya dan tindakan yang orisinal.',
                'icon' => 'PhPalette',
                'color' => 'text-pink-500'
            ]
        ];

        // Fallback menggunakan DB Facade (Bisa diganti dengan model jika class_exists)
        if (class_exists(PancasilaProfile::class)) {
            PancasilaProfile::updateOrCreate(
                ['id' => 1],
                [
                    'title' => 'Profil Pelajar Pancasila',
                    'description' => 'Kurikulum kami berfokus pada pembentukan karakter siswa yang berlandaskan 6 dimensi Profil Pelajar Pancasila.',
                    'dimensions' => json_encode($dimensions)
                ]
            );
        } else {
            DB::table('pancasila_profiles')->updateOrInsert(
                ['id' => 1],
                [
                    'title' => 'Profil Pelajar Pancasila',
                    'description' => 'Kurikulum kami berfokus pada pembentukan karakter siswa yang berlandaskan 6 dimensi Profil Pelajar Pancasila.',
                    'dimensions' => json_encode($dimensions),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}