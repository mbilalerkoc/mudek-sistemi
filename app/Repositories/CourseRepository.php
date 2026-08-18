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

    // User id ile öğretmenin derslerini getir
    public function getByUser($userId)
    {
        return $this->model
            ->whereHas('users', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();
    }

    // Öğretmen objesiyle çalışır
    public function getCoursesByTeacher($teacher)
    {
        return $this->getByUser($teacher->id);
    }

    // Form için ders detaylarını getir
    public function getCourseDetailsForForm($ders_id)
    {
        $course = $this->find($ders_id);

        $exams = $course->exams()->orderBy('exam_date', 'asc')->get();

        $students = $course->students()->with([
            'studentCourses' => function($query) use ($ders_id) {
                $query->where('course_id', $ders_id);
            },
            'studentExams' => function($query) use ($exams) {
                $examIds = $exams->isNotEmpty() ? $exams->pluck('id')->toArray() : [];
                $query->whereIn('exam_id', $examIds);
            },
            'studentExams.exam'
        ])->get();

        return compact('course', 'exams', 'students');
    }

    // Tüm dersleri öğretmen bilgisiyle getir
    public function allWithUsers()
    {
        return $this->model->with(['users.academicTitle'])->get();
    }
}