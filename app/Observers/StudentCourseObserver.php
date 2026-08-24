<?php

namespace App\Observers;

use App\Models\StudentCourse;
use App\Models\Exam;
use App\Models\StudentExam;

class StudentCourseObserver
{
    /**
     * Handle the StudentCourse "created" event.
     */
    public function created(StudentCourse $studentCourse): void
    {
        // 1. Bu dersin sınavlarını bul
        $exams = Exam::where('course_id', $studentCourse->course_id)->get();

        // Eğer bu ders için henüz hiç sınav oluşturulmamışsa (Midterm, Final vs.) atla
        if ($exams->isEmpty()) {
            return;
        }

        // 2. Her sınav için öğrenciye sınav kağıdı oluştur
        foreach ($exams as $exam) {
            StudentExam::firstOrCreate([
                'student_course_id' => $studentCourse->id,
                'exam_id'           => $exam->id
            ], [
                'exam_score'       => null,
                'assignment_score' => 0,
                'total_score'      => null
            ]);
        }
    }

    /**
     * Handle the StudentCourse "updated" event.
     */
    public function updated(StudentCourse $studentCourse): void
    {
        //
    }

    /**
     * Handle the StudentCourse "deleted" event.
     */
    public function deleted(StudentCourse $studentCourse): void
    {
        //
    }

    /**
     * Handle the StudentCourse "restored" event.
     */
    public function restored(StudentCourse $studentCourse): void
    {
        //
    }

    /**
     * Handle the StudentCourse "force deleted" event.
     */
    public function forceDeleted(StudentCourse $studentCourse): void
    {
        //
    }
}
