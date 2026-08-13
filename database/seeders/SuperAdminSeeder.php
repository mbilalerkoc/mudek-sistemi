<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ktun.edu.tr'], // Benzersiz e-posta kontrolü
            [
                'name' => 'Süper',
                'surname' => 'Admin',
                'email' => 'admin@ktun.edu.tr',
                'password' => Hash::make('admin12345'), // Güvenli şifre
                'role' => 'super_admin', // Süper admin rolü
            ]
        );
    }
}