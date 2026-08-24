<?php

namespace App\Services;

use App\Models\Exam;
use App\Repositories\Interfaces\StudentExamRepositoryInterface;
use App\Repositories\Interfaces\StudentCourseRepositoryInterface;

class GradeService
{
    public function __construct(
        private StudentExamRepositoryInterface $studentExamRepository,
        private StudentCourseRepositoryInterface $studentCourseRepository
    ) {}

    public function kaydet(array $data)
    {
        if (!isset($data['grades']) || !is_array($data['grades'])) {
            return;
        }

        $courseId = $data['course_id'] ?? null;

        if (!$courseId) {
            return;
        }

        $updatedStudentCourseIds = [];

        $examTypes = [
            'midterm' => 'midterm',
            'final' => 'final',
            'makeup' => 'makeup',
        ];

        foreach ($examTypes as $gradeType => $examType) {
            if (!isset($data['grades'][$gradeType])) {
                continue;
            }

            $exam = Exam::where('course_id', $courseId)
                ->where('exam_type', $examType)
                ->first();

            if (!$exam) {
                continue;
            }

            foreach ($data['grades'][$gradeType] as $studentId => $examScore) {
                if ($examScore === null || $examScore === '') {
                    continue;
                }

                $studentCourse = $this->studentCourseRepository
                    ->findByStudentAndCourse($studentId, $courseId);

                if (!$studentCourse) {
                    continue;
                }

                $studentExam = $this->studentExamRepository
                    ->findByStudentCourseAndExam(
                        $studentCourse->id,
                        $exam->id
                    );

                if (!$studentExam) {
                    $studentExam = $this->studentExamRepository->create([
                        'student_course_id' => $studentCourse->id,
                        'exam_id' => $exam->id,
                        'exam_score' => $examScore,
                        'assignment_score' => 0,
                        'total_score' => $examScore,
                    ]);
                } else {
                    $this->studentExamRepository->update(
                        $studentExam->id,
                        [
                            'exam_score' => $examScore,
                        ]
                    );
                }
                $examService = app(\App\Services\ExamService::class);
                $examService->skorlariYenidenHesapla($studentExam);

                $updatedStudentCourseIds[$studentCourse->id] = $studentCourse->id;
            }
        }

        foreach ($updatedStudentCourseIds as $studentCourseId) {
            $this->ortalamaHesaplaVeGuncelle($studentCourseId);
        }
    }

    public function ortalamaHesaplaVeGuncelle($studentCourseId)
    {
        $studentExams = $this->studentExamRepository
            ->findByStudentCourseWithExam($studentCourseId);

        $midterm = null;
        $final = null;
        $makeup = null;

        foreach ($studentExams as $studentExam) {
            if (!$studentExam->exam) {
                continue;
            }

            $puan = $studentExam->total_score;

            if ($studentExam->exam->exam_type === 'midterm' && $puan !== null) {
                $midterm = (float) $puan;
            } elseif ($studentExam->exam->exam_type === 'final' && $puan !== null) {
                $final = (float) $puan;
            } elseif ($studentExam->exam->exam_type === 'makeup' && $puan !== null) {
                $makeup = (float) $puan;
            }
        }

        // KURAL: Final veya Bütünleme notu girilmediyse ortalama ve durum hesaplanmaz!
        if (is_null($final) && is_null($makeup)) {
            $this->studentCourseRepository->update(
                $studentCourseId,
                [
                    'average' => null,
                    'status'  => null,
                ]
            );
            return;
        }

        // Hesaplama (Bütünleme varsa bütünleme finalin yerine geçer)
        if (!is_null($makeup)) {
            $ortalama = ($midterm ?? 0) * 0.4 + ($makeup * 0.6);
        } else {
            $ortalama = ($midterm ?? 0) * 0.4 + ($final * 0.6);
        }

        $durum = $ortalama >= 50 ? 'passed' : 'failed';

        $this->studentCourseRepository->update(
            $studentCourseId,
            [
                'average' => round($ortalama, 2),
                'status'  => $durum,
            ]
        );
    }

public function harfNotuHesapla($ortalama): string
{
    return match (true) {
        $ortalama >= 90 => 'AA',
        $ortalama >= 80 => 'BA',
        $ortalama >= 70 => 'BB',
        $ortalama >= 60 => 'CB',
        $ortalama >= 50 => 'CC',
        default => 'FF',
    };
}
}