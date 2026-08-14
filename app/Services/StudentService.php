<?php

namespace App\Services;

use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentService
{
    public function __construct(
        private StudentRepositoryInterface $studentRepository
    ) {}

    public function createStudent(array $data)
    {
        return $this->studentRepository->create($data);
    }

    public function updateStudent($id, array $data)
    {
        return $this->studentRepository->update($id, $data);
    }
}