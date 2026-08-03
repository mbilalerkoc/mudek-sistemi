<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Models\Course;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getCoursesByTeacher($teacher)
    {
        return $teacher->courses;
    }

    public function getCourseDetailsForForm($ders_id)
    {
        $course = $this->find($ders_id);
        $exams = $course->exams ?? collect();

        $students = $course->students()->with([
            'studentCourses' => function($query) use ($ders_id) {
                $query->where('course_id', $ders_id);
            },
            'studentExams' => function($query) use ($exams) {
                $examIds = $exams->isNotEmpty() ? $exams->pluck('id')->toArray() : [];
                $query->whereIn('exam_id', $examIds);
            }
        ])->get();

        return compact('course', 'exams', 'students');
    }
}