<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'surname' => 'Yetkili',
            'email'    => 'admin@ktun.edu.tr',
            'password' => Hash::make('123456'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Prof. Dr. Ahmet',
            'surname' => 'Yılmaz',
            'email'    => 'ahmet@ktun.edu.tr',
            'password' => Hash::make('123456'),
            'role'     => 'teacher',
        ]);

        User::create([
            'name'     => 'Doç. Dr. Ayşe',
            'surname' => 'Kaya',
            'email'    => 'ayse@ktun.edu.tr',
            'password' => Hash::make('123456'),
            'role'     => 'teacher',
        ]);
    }
}