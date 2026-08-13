<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    // Öğretmenin derslerini getir
    public function getByUser($userId)
    {
        return $this->model
            ->whereHas('userCourses', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();
    }

    // Eski metod - öğretmen objesiyle çalışıyor
    public function getCoursesByTeacher($teacher)
    {
        return $teacher->courses;
    }

    // Form için ders detaylarını ve sınav türlerini (midterm, final, makeup vb.) getir
    public function getCourseDetailsForForm($ders_id)
    {
        $course = $this->find($ders_id);
        
        // Tüm sınavları (bütünleme dahil) tarihe göre sıralı çekiyoruz
        $exams = $course->exams()->orderBy('exam_date', 'asc')->get();

        $students = $course->students()->with([
            'studentCourses' => function($query) use ($ders_id) {
                $query->where('course_id', $ders_id);
            },
            'studentExams' => function($query) use ($exams) {
                $examIds = $exams->isNotEmpty() ? $exams->pluck('id')->toArray() : [];
                $query->whereIn('exam_id', $examIds);
            },
            'studentExams.exam' // <-- Bu sayede $studentExam->exam->exam_type diyerek hangi sınav olduğunu okuyabilirsin
        ])->get();

        return compact('course', 'exams', 'students');
    }
}