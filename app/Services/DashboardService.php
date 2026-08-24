<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use App\Repositories\Interfaces\ExamRepositoryInterface;
use App\Services\CourseService;

class DashboardService
{
    public function __construct(
    private CourseRepositoryInterface $courseRepository,
    private StudentRepositoryInterface $studentRepository,
    private AssignmentRepositoryInterface $assignmentRepository,
    private ExamRepositoryInterface $examRepository,
    private CourseService $courseService
) {}

    public function getAdminDashboardData()
    {
        $courses = $this->courseRepository->all();

        return [
            'coursesCount' => $this->courseRepository->count(),
            'studentsCount' => $this->studentRepository->count(),
            'assignmentsCount' => $this->assignmentRepository->count(),
            'examsCount' => $this->examRepository->count(),
            'courses' => $courses,
        ];
    }

    public function getUserDashboardData($user)
    {
        $courses = $this->courseService->getAllCoursesWithCompletionStatus($user);

        $courseIds = $courses->pluck('id');

        $studentsCount = $this->studentRepository->countByCourses($courseIds);

        $assignmentsCount = $this->assignmentRepository
            ->all()
            ->whereIn('course_id', $courseIds)
            ->count();

        $examsCount = $this->examRepository
            ->all()
            ->whereIn('course_id', $courseIds)
            ->count();

        return [
            'coursesCount' => $courses->count(),
            'studentsCount' => $studentsCount,
            'assignmentsCount' => $assignmentsCount,
            'examsCount' => $examsCount,
            'courses' => $courses,
        ];
    }
}