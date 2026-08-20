<?php

namespace App\Repositories\Interfaces;

interface AssignmentSubmissionRepositoryInterface extends BaseRepositoryInterface
{
    public function getByAssignment($assignmentId);

    public function findByAssignmentAndStudent($assignmentId, $studentId);

    public function saveSubmission($assignmentId, $studentId, array $data);
}