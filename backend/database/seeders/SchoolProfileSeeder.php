<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SchoolProfile::updateOrCreate(
            ['id' => 1],
            [
                'description' => 'SMA Negeri 1 Nogosari adalah sekolah menengah atas yang berdedikasi untuk mencetak generasi cerdas, berkarakter, dan siap menghadapi tantangan global.',
                'npsn' => '12345678',
                'accreditation' => 'A',
                'location' => 'Boyolali, Jawa Tengah',
                'status' => 'Negeri',
            ]
        );
    }
}