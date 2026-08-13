<?php

namespace App\Repositories\Interfaces;

interface ExamAssignmentRepositoryInterface extends BaseRepositoryInterface
{
    public function getByExam($examId);
    public function getByAssignment($assignmentId);
}