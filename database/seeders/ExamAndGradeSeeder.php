<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Exam;
use App\Models\StudentCourse;
use App\Models\StudentExam;

class ExamAndGradeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sistemdeki dersleri alıyoruz (Örn: BLM101)
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->info('Önce ders ve öğrenci seeder\'ını çalıştırmalısın! Hiç ders bulunamadı.');
            return;
        }

        foreach ($courses as $course) {
            // Her ders için bir Vize (midterm) sınavı oluşturuyoruz
            $exam = Exam::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'exam_type' => 'midterm'
                ],
                [
                    'exam_date' => now(),
                    'question_paper_path' => 'sample.pdf'
                ]
            );

            // Bu derse kayıtlı öğrenci ilişkilerini (student_courses) buluyoruz
            $studentCourses = StudentCourse::where('course_id', $course->id)->get();

            foreach ($studentCourses as $studentCourse) {
                // Her öğrenci için bu sınava ait not kaydı (student_exams) oluşturuyoruz
                StudentExam::firstOrCreate(
                    [
                        'student_course_id' => $studentCourse->id,
                        'exam_id' => $exam->id
                    ],
                    [
                        'exam_score' => 50.00,       // Örnek sınav puanı
                        'assignment_score' => 20.00, // Örnek ödev puanı
                        'total_score' => 70.00       // Toplam puan (50 + 20)
                    ]
                );
            }
        }

        $this->command->info('Sınavlar ve örnek öğrenci notları başarıyla eklendi!');
    }
}