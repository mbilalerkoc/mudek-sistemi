<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Repositories\Interfaces\AnswerRepositoryInterface;

class AnswerRepository extends BaseRepository implements AnswerRepositoryInterface
{
    public function __construct(Answer $model)
    {
        parent::__construct($model);
    }

    public function getByStudentExam($studentExamId)
    {
        return $this->model->where('student_exam_id', $studentExamId)->get();
    }

    public function getByQuestion($questionId)
    {
        return $this->model->where('question_id', $questionId)->get();
    }
}