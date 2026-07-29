<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Matematik I öğrencileri (course_id: 1)
        Student::create(['name' => 'Ali Veli',      'student_no' => '2021001']);
        Student::create(['name' => 'Ayşe Fatma',    'student_no' => '2021002']);
        Student::create(['name' => 'Mehmet Yılmaz', 'student_no' => '2021003']);

        // Fizik I öğrencileri (course_id: 2)
        Student::create(['name' => 'Zeynep Kaya',   'student_no' => '2021004']);
        Student::create(['name' => 'Hasan Demir',   'student_no' => '2021005']);

        // Algoritma öğrencileri (course_id: 3)
        Student::create(['name' => 'Elif Şahin',    'student_no' => '2021006']);
        Student::create(['name' => 'Burak Çelik',   'student_no' => '2021007']);
        Student::create(['name' => 'Selin Arslan',  'student_no' => '2021008']);
    }
}