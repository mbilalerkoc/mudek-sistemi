<?php

namespace App\Repositories;

use App\Models\StudentCourse;
use App\Repositories\Interfaces\StudentCourseRepositoryInterface;

class StudentCourseRepository extends BaseRepository implements StudentCourseRepositoryInterface
{
    public function __construct(StudentCourse $model)
    {
        parent::__construct($model);
    }

    public function getByStudent($studentId)
    {
        return $this->model->where('student_id', $studentId)->get();
    }

    public function getByCourse($courseId)
    {
        return $this->model->where('course_id', $courseId)->get();
    }

    public function updateAverage($id, $average, $status)
    {
        $record = $this->find($id);
        $record->update([
            'average' => $average,
            'status'  => $status,
        ]);
        return $record;
    }

    public function findByStudentAndCourse($studentId, $courseId)
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();
    }
}   