<?php

namespace App\Repositories\Interfaces;

interface StudentExamRepositoryInterface
{
    public function getByStudentCourse($studentCourseId);

    public function getByExam($examId);

    public function getByLevel($examId, $level);

    public function findByStudentCourseAndExam($studentCourseId, $examId);

    public function findByStudentCourseWithExam($studentCourseId);
}