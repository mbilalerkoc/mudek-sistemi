<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        // Matematik I öğrencilerinin notları
        Grade::create(['student_id' => 1, 'midterm' => 75, 'final' => 80, 'average' => 78, 'letter_grade' => 'BB', 'status' => 'passed']);
        Grade::create(['student_id' => 2, 'midterm' => 90, 'final' => 95, 'average' => 93, 'letter_grade' => 'AA', 'status' => 'passed']);
        Grade::create(['student_id' => 3, 'midterm' => 45, 'final' => 50, 'average' => 48, 'letter_grade' => 'FF', 'status' => 'failed']);

        // Fizik I öğrencilerinin notları
        Grade::create(['student_id' => 4, 'midterm' => 60, 'final' => 65, 'average' => 63, 'letter_grade' => 'CC', 'status' => 'passed']);
        Grade::create(['student_id' => 5, 'midterm' => 85, 'final' => 88, 'average' => 87, 'letter_grade' => 'BA', 'status' => 'passed']);

        // Algoritma öğrencilerinin notları
        Grade::create(['student_id' => 6, 'midterm' => 70, 'final' => 75, 'average' => 73, 'letter_grade' => 'CB', 'status' => 'passed']);
        Grade::create(['student_id' => 7, 'midterm' => 55, 'final' => 40, 'makeup' => 60, 'average' => 58, 'letter_grade' => 'DC', 'status' => 'passed']);
        Grade::create(['student_id' => 8, 'midterm' => 95, 'final' => 98, 'average' => 97, 'letter_grade' => 'AA', 'status' => 'passed']);
    }
}