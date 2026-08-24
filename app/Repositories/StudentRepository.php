<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function getByCourse($courseId)
    {
        return $this->model
            ->whereHas('studentCourses', function($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->get();
    }

    public function findByStudentNo($studentNo)
    {
        return $this->model->where('student_no', $studentNo)->firstOrFail();
    }
    public function countByCourses($courseIds)
{
    return $this->model
        ->whereHas('studentCourses', function ($query) use ($courseIds) {
            $query->whereIn('course_id', $courseIds);
        })
        ->count();
}
}