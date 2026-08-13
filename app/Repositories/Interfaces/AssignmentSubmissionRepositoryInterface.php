<?php

namespace App\Repositories\Interfaces;

interface AssignmentSubmissionRepositoryInterface extends BaseRepositoryInterface
{
    public function getByAssignment($assignmentId);
    public function getByStudent($studentId);
    public function updateGrade($id, $grade);
}