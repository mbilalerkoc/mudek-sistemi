<?php

namespace App\Repositories\Interfaces;

interface ExamRepositoryInterface extends BaseRepositoryInterface
{
    public function getByCourse($courseId);
    public function getByType($courseId, $type);
    public function findExamWithDetails($examId);
    public function createQuestion(array $data);
}