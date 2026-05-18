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
        User::factory()->create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@sman1nogosari.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'super_admin',
        ]);

        User::factory()->create([
            'name'     => 'Admin Humas',
            'email'    => 'admin@sman1nogosari.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);
    }
}
