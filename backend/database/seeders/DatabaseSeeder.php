<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@sman1nogosari.sch.id'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password123'),
                'role'     => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@sman1nogosari.sch.id'],
            [
                'name'     => 'Admin Humas',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        $this->call(VisionMissionSeeder::class);
        $this->call(SchoolProfileSeeder::class);
    }
}
