<?php

namespace App\Repositories;

use App\Models\StudentExam;
use App\Repositories\Interfaces\StudentExamRepositoryInterface;

class StudentExamRepository extends BaseRepository implements StudentExamRepositoryInterface
{
    public function __construct(StudentExam $model)
    {
        parent::__construct($model);
    }

    public function getByStudentCourse($studentCourseId)
    {
        return $this->model->where('student_course_id', $studentCourseId)->get();
    }

    public function getByExam($examId)
    {
        return $this->model->where('exam_id', $examId)->get();
    }

    public function getByLevel($examId, $level)
    {
        return $this->model
            ->where('exam_id', $examId)
            ->where('level', $level)
            ->get();
    }

    public function findByStudentCourseAndExam($studentCourseId, $examId)
    {
        return $this->model
            ->where('student_course_id', $studentCourseId)
            ->where('exam_id', $examId)
            ->first();
    }

    public function findByStudentCourseWithExam($studentCourseId)
    {
        return $this->model
            ->with('exam')
            ->where('student_course_id', $studentCourseId)
            ->get();
    }
}