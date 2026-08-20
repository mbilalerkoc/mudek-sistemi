<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;

class AssignmentRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    public function __construct(Assignment $model)
    {
        parent::__construct($model);
    }

    public function getByCourse($courseId)
    {
        return $this->model
            ->where('course_id', $courseId)
            ->get();
    }

    public function getByExam($examId)
    {
        return $this->model
            ->whereHas('examAssignments', function($q) use ($examId) {
                $q->where('exam_id', $examId);
            })
            ->get();
    }
}