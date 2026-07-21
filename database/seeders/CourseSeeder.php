<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::create([
            'user_id'  => 2,
            'code'     => 'BIL101',
            'name'     => 'Matematik I',
            'credits'  => 5,
            'semester' => '2024-Güz',
        ]);

        Course::create([
            'user_id'  => 2,
            'code'     => 'BIL102',
            'name'     => 'Fizik I',
            'credits'  => 4,
            'semester' => '2024-Güz',
        ]);

        Course::create([
            'user_id'  => 3,
            'code'     => 'BIL103',
            'name'     => 'Algoritma ve Programlama',
            'credits'  => 6,
            'semester' => '2024-Güz',
        ]);
        Course::create([
            'user_id'  => 3,
            'code'     => 'BIL105',
            'name'     => 'Veri Tabanı Yönetim Sistemleri',
            'credits'  => 3,
            'semester' => '2024-Güz',
        ]);
        Course::create([
            'user_id'  => 2,
            'code'     => 'BIL104',
            'name'     => 'Veri Yapıları',
            'credits'  => 6,
            'semester' => '2024-Güz',
        ]);
    }
}