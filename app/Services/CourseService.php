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
}