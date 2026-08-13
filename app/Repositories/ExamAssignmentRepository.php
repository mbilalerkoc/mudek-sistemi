<?php

namespace App\Repositories;

use App\Models\ExamAssignment;
use App\Repositories\Interfaces\ExamAssignmentRepositoryInterface;

class ExamAssignmentRepository extends BaseRepository implements ExamAssignmentRepositoryInterface
{
    public function __construct(ExamAssignment $model)
    {
        parent::__construct($model);
    }

    public function getByExam($examId)
    {
        return $this->model->where('exam_id', $examId)->get();
    }

    public function getByAssignment($assignmentId)
    {
        return $this->model->where('assignment_id', $assignmentId)->get();
    }
}