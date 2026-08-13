<?php

namespace App\Repositories\Interfaces;

interface AssignmentRepositoryInterface extends BaseRepositoryInterface
{
    public function getByCourse($courseId);
    public function getByExam($examId);
}