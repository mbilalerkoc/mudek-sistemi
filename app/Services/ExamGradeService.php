<?php

namespace App\Services;

use App\Models\StudentExam;
use App\Models\Answer;
use App\Models\AssignmentSubmission;

class ExamGradeService
{
    public function __construct(
        private GradeService $gradeService
    ) {}

    public function cevaplariKaydet(array $data): void
    {
        if (!isset($data['grades']) || !is_array($data['grades'])) {
            return;
        }

        foreach ($data['grades'] as $studentExamId => $entry) {
            $studentExam = StudentExam::find($studentExamId);
            if (!$studentExam) {
                continue;
            }

            if (isset($entry['level']) && $entry['level'] !== '') {
                $studentExam->level = (int) $entry['level'];
                $studentExam->save();
            }

            if (isset($entry['answers']) && is_array($entry['answers'])) {
                foreach ($entry['answers'] as $questionId => $score) {
                    if ($score === null || $score === '') {
                        continue;
                    }

                    Answer::updateOrCreate(
                        [
                            'student_exam_id' => $studentExamId,
                            'question_id' => $questionId,
                        ],
                        ['score' => $score]
                    );
                }
            }

            $this->skorlariYenidenHesapla($studentExam);

            // total_score güncellendiği için ders ortalaması da yeniden hesaplanmalı
            $this->gradeService->ortalamaHesaplaVeGuncelle($studentExam->student_course_id);
        }
    }

    public function skorlariYenidenHesapla(StudentExam $studentExam): void
    {
        $examScore = $studentExam->answers()->sum('score');

        $assignmentIds = $studentExam->exam->examAssignments()->pluck('assignment_id');
        $studentId = $studentExam->studentCourse->student_id;

        $assignmentScore = 0;

        if ($assignmentIds->isNotEmpty()) {
            $assignmentScore = AssignmentSubmission::where('student_id', $studentId)
                ->whereIn('assignment_id', $assignmentIds)
                ->sum('grade_score');
        }

        $totalScore = $examScore + $assignmentScore;

        $studentExam->update([
            'exam_score' => $examScore,
            'assignment_score' => $assignmentScore,
            'total_score' => $totalScore,
        ]);
    }
}