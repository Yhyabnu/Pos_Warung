<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        User::create([
            'name' => 'Admin Warung',
            'email' => 'admin@warung.com',
            'password' => bcrypt('password'),
            'peran' => 'admin',
        ]);

        // User Kasir
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@warung.com', 
            'password' => bcrypt('password'),
            'peran' => 'kasir',
        ]);

        User::create([
            'name' => 'Kasir 2',
            'email' => 'kasir2@warung.com',
            'password' => bcrypt('password'),
            'peran' => 'kasir',
        ]);

        // Tambahkan 3 kasir random
        User::factory(3)->create();
    }
}