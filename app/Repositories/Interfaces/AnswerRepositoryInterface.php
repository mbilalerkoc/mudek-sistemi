<?php

namespace App\Repositories\Interfaces;

interface AnswerRepositoryInterface extends BaseRepositoryInterface
{
    public function getByStudentExam($studentExamId);
    public function getByQuestion($questionId);
}