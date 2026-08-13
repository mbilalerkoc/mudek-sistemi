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
        return $this->model->where('assignment_id', $assignmentId)->get();
    }

    public function getByStudent($studentId)
    {
        return $this->model->where('student_id', $studentId)->get();
    }

    public function updateGrade($id, $grade)
    {
        $record = $this->find($id);
        $record->update(['grade_score' => $grade]);
        return $record;
    }
}