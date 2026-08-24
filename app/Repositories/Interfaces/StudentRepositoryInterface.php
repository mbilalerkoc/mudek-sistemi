<?php

namespace App\Repositories\Interfaces;

interface StudentRepositoryInterface extends BaseRepositoryInterface
{
    public function getByCourse($courseId);
    public function findByStudentNo($studentNo);
    public function countByCourses($courseIds);
}