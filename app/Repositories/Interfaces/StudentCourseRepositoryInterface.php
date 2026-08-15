<?php

namespace App\Repositories\Interfaces;

interface StudentCourseRepositoryInterface
{
    public function getByStudent($studentId);

    public function getByCourse($courseId);

    public function findByStudentAndCourse($studentId, $courseId);

    public function updateAverage($id, $average, $status);

    public function enroll($studentId, $courseId);

    public function unenroll($studentId, $courseId);
}