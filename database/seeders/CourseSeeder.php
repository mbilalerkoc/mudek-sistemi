<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'code' => 'BLM101',
            'name' => 'Bilgisayar Mühendisliğine Giriş',
            'credits' => 4,
            'semester' => '1. Dönem'
        ]);
        Course::create([
            'code' => 'MAT2',
            'name' => 'Matematik 2',
            'credits' => 6,
            'semester' => '2. Dönem'
        ]);
    }
}
