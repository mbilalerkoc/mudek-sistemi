<?php

namespace App\Repositories;

use App\Models\AssignmentSubmission;
use App\Repositories\Interfaces\AssignmentSubmissionRepositoryInterface;

class AssignmentSubmissionRepository extends BaseRepository implements AssignmentSubmissionRepositoryInterface
{
    public function __construct(AssignmentSubmission $model)
    {
        parent::__construct($model);
    }

    public function getByAssignment($assignmentId)
    {
        return $this->model
            ->where('assignment_id', $assignmentId)
            ->get();
    }

    public function findByAssignmentAndStudent($assignmentId, $studentId)
    {
        return $this->model
            ->where('assignment_id', $assignmentId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function saveSubmission($assignmentId, $studentId, array $data)
    {
        return $this->model->updateOrCreate(
            [
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
            ],
            [
                'grade_score' => $data['grade_score'] ?? null,
                'file_path' => $data['file_path'] ?? null,
            ]
        );
    }
}