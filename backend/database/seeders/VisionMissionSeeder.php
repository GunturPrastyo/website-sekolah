<?php

namespace Database\Seeders;

use App\Models\VisionMission;
use Illuminate\Database\Seeder;

class VisionMissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VisionMission::updateOrCreate(
            ['id' => 1],
            [
                'vision' => 'Terwujudnya Lulusan yang Bertaqwa, Berkarakter, Cerdas, Terampil, dan Berbudaya Lingkungan serta Mampu Bersaing di Era Global.',
                'missions' => [
                    ['text' => 'Menumbuhkembangkan keimanan dan ketaqwaan melalui pembiasaan kegiatan keagamaan di lingkungan sekolah.'],
                    ['text' => 'Melaksanakan proses pembelajaran dan bimbingan secara efektif, inovatif, dan berpusat pada peserta didik.'],
                    ['text' => 'Membina peserta didik untuk mengembangkan minat, bakat, dan potensi yang dimiliki melalui kegiatan ekstrakurikuler.'],
                    ['text' => 'Mewujudkan lingkungan sekolah yang bersih, asri, aman, dan nyaman sebagai pilar utama pendukung proses belajar mengajar.'],
                    ['text' => 'Meningkatkan kemampuan pemanfaatan teknologi informasi dan komunikasi untuk siap bersaing di tingkat global.']
                ],
                'principal_speech' => '<p><strong>"Selamat datang di website resmi SMA Negeri 1 Nogosari. Kami berkomitmen untuk memberikan pendidikan berkualitas yang tidak hanya berfokus pada kecerdasan akademis, tetapi juga pembentukan karakter peserta didik yang berakhlak mulia dan berbudaya lingkungan."</strong></p><p>Di era digital dan globalisasi saat ini, lembaga pendidikan memiliki tanggung jawab besar. Oleh karena itu, kami terus berinovasi dalam metode pembelajaran serta melengkapi berbagai fasilitas guna memastikan anak-anak kita siap menghadapi tantangan masa depan dengan bekal ilmu dan iman yang seimbang.</p><p>Kolaborasi yang erat antara guru, siswa, komite sekolah, dan masyarakat adalah kunci utama dari kesuksesan yang kita raih bersama. Melalui visi dan misi yang jelas, kami berharap dapat mewujudkan sekolah sebagai tempat yang menyenangkan untuk belajar dan bertumbuh.</p><p>Terima kasih atas dukungan dan kepercayaan Bapak/Ibu sekalian terhadap sekolah kami. Mari kita bersama-sama mewujudkan generasi penerus yang cerdas, terampil, dan siap berkontribusi bagi nusa dan bangsa.</p>',
            ]
        );
    }
}