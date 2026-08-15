<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\UserCourseRepositoryInterface;
use App\Repositories\Interfaces\StudentCourseRepositoryInterface;

class CourseService
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private UserCourseRepositoryInterface $userCourseRepository,
        private StudentCourseRepositoryInterface $studentCourseRepository
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

    public function enrollStudent($courseId, $studentId)
{
    $course = $this->courseRepository->find($courseId);
    
    activity()
        ->performedOn($course)
        ->withProperties([
            'course_id'  => $courseId,
            'student_id' => $studentId,
        ])
        ->log('Öğrenci derse eklendi');

    return $this->studentCourseRepository->enroll($studentId, $courseId);
}

public function unenrollStudent($courseId, $studentId)
{
    $course = $this->courseRepository->find($courseId);

    activity()
        ->performedOn($course)
        ->withProperties([
            'course_id'  => $courseId,
            'student_id' => $studentId,
        ])
        ->log('Öğrenci dersten çıkarıldı');

    return $this->studentCourseRepository->unenroll($studentId, $courseId);
}
}