<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class StudentCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Sistemdeki öğrencileri ve dersleri alalım
        $students = Student::all();
        $courses = Course::all();

        if ($students->isNotEmpty() && $courses->isNotEmpty()) {
            foreach ($students as $student) {
                // Her öğrenciye rastgele 1 veya 2 ders atayalım
                $assignedCourses = $courses->random(min(2, $courses->count()));

                foreach ($assignedCourses as $course) {
                    // student_courses tablosuna kayıt atıyoruz
                    DB::table('student_courses')->updateOrInsert(
                        [
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                        ],
                        [
                            'semester' => '1. Dönem',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}