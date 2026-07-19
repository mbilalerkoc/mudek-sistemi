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
            'email'    => 'admin@ktun.edu.tr',
            'password' => Hash::make('123456'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Prof. Dr. Ahmet Yılmaz',
            'email'    => 'ahmet@ktun.edu.tr',
            'password' => Hash::make('123456'),
            'role'     => 'teacher',
        ]);

        User::create([
            'name'     => 'Doç. Dr. Ayşe Kaya',
            'email'    => 'ayse@ktun.edu.tr',
            'password' => Hash::make('123456'),
            'role'     => 'teacher',
        ]);
    }
}