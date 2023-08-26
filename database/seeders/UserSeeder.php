<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(20)->create();
        User::create([
            'name' => 'Masjoel',
            'email' => 'masjoel@gmail.com',
            'password' => Hash::make('123123123'),
            'role' => 'admin',
            'phone' => '6285290721234',
            'bio' => 'web developer',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'superadmin',
            'phone' => '6285290721111',
            'bio' => 'devops',
            'email_verified_at' => now(),
        ]);
    }
}
