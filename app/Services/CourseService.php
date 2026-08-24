<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\UserCourseRepositoryInterface;
use App\Repositories\Interfaces\StudentCourseRepositoryInterface;
use App\Repositories\Interfaces\ExamRepositoryInterface;

class CourseService
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private UserCourseRepositoryInterface $userCourseRepository,
        private StudentCourseRepositoryInterface $studentCourseRepository,
        private ExamRepositoryInterface $examRepository
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
        $data['credits'] = $data['credits'] ?? 3;

        // Dersi oluştur
        $course = $this->courseRepository->create($data);

        // Otomatik vize + final + bütünleme sınavları oluştur
        foreach (['midterm', 'final', 'makeup'] as $type) {
            $this->examRepository->create([
                'course_id' => $course->id,
                'exam_type' => $type,
            ]);
        }

        activity()
            ->performedOn($course)
            ->withProperties(['code' => $course->code, 'name' => $course->name])
            ->log('Ders oluşturuldu, sınavlar otomatik eklendi');

        return $course;
    }

    public function updateCourse($id, array $data)
    {
        $course = $this->courseRepository->update($id, $data);

        activity()
            ->performedOn($course)
            ->withProperties($data)
            ->log('Ders güncellendi');

        return $course;
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

    public function getCourseWithCompletionStatus($courseId)
{
    $course = $this->courseRepository->getCourseCompletionData($courseId);

    if (!$course) {
        return null;
    }

    $totalForms = 3;
    $completedForms = 0;

    // 1. Sınav formu
    if ($course->exams && $course->exams->isNotEmpty()) {
        $completedForms++;
    }

    // 2. Ödev formu
    if ($course->assignments && $course->assignments->isNotEmpty()) {
        $completedForms++;
    }

    // 3. Öğrenci formu
    if ($course->students && $course->students->isNotEmpty()) {
        $completedForms++;
    }

    $percentage = round(($completedForms / $totalForms) * 100);

    $course->toplam_form = $totalForms;
    $course->doldurulan_form = $completedForms;
    $course->yuzde = $percentage;

    return $course;
}

   public function getAllCoursesWithCompletionStatus($user)
{
    $courses = $user->role === 'super_admin'
        ? $this->courseRepository->allWithUsers()
        : $this->courseRepository->getCoursesByTeacher($user);

    foreach ($courses as $course) {
        $completedCourse = $this->getCourseWithCompletionStatus($course->id);

        if ($completedCourse) {
            $course->toplam_form = $completedCourse->toplam_form;
            $course->doldurulan_form = $completedCourse->doldurulan_form;
            $course->yuzde = $completedCourse->yuzde;
        } else {
            $course->toplam_form = 0;
            $course->doldurulan_form = 0;
            $course->yuzde = 0;
        }
    }

    return $courses;
}
}