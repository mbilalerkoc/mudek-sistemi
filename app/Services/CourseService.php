<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\UserCourseRepositoryInterface;

class CourseService
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private UserCourseRepositoryInterface $userCourseRepository
    ) {}

    public function assignTeacher($courseId, $userId)
    {
        $course = $this->courseRepository->find($courseId);
        $course->users()->syncWithoutDetaching([$userId]);
    }

    public function removeTeacher($courseId, $userId)
    {
        $course = $this->courseRepository->find($courseId);
        $course->users()->detach($userId);
    }

    public function createCourse(array $data)
    {
        // Kredi girilmediyse varsayılan olarak 3 ata
        $data['credits'] = $data['credits'] ?? 3;

        return $this->courseRepository->create($data);
    }

    public function updateCourse($id, array $data)
    {
        return $this->courseRepository->update($id, $data);
    }
}