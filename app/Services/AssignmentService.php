<?php

namespace App\Services;

use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use App\Repositories\Interfaces\AssignmentSubmissionRepositoryInterface;
use App\Enums\Messages\AssignmentMessages;

class AssignmentService
{
    protected $assignmentRepository;
    protected $submissionRepository;

    public function __construct(
        AssignmentRepositoryInterface $assignmentRepository,
        AssignmentSubmissionRepositoryInterface $submissionRepository
    ) {
        $this->assignmentRepository = $assignmentRepository;
        $this->submissionRepository = $submissionRepository;
    }

    public function getCourseAssignments($courseId)
    {
        return $this->assignmentRepository->getByCourse($courseId);
    }

    public function findAssignment($id)
    {
        return $this->assignmentRepository->find($id);
    }

    public function createAssignment(array $data)
    {
        return $this->assignmentRepository->create($data);
    }

    public function deleteAssignment($id)
    {
        $assignment = $this->assignmentRepository->find($id);

        activity('odev')
            ->performedOn($assignment)
            ->log(
                AssignmentMessages::DELETED->value .
                ' - ' .
                $assignment->title
            );

        return $this->assignmentRepository->delete($id);
    }

    public function getSubmissions($assignmentId)
    {
        return $this->submissionRepository
            ->getByAssignment($assignmentId);
    }

    public function getSubmission($assignmentId, $studentId)
    {
        return $this->submissionRepository
            ->findByAssignmentAndStudent(
                $assignmentId,
                $studentId
            );
    }

    public function saveSubmission($assignmentId, $studentId, array $data)
    {
        $assignment = $this->assignmentRepository->find($assignmentId);

        if (
            ($data['grade_score'] ?? null) === null &&
            empty($data['file_path'])
        ) {
            $submission = $this->submissionRepository
                ->findByAssignmentAndStudent(
                    $assignmentId,
                    $studentId
                );

            if ($submission) {
                $this->submissionRepository
                    ->delete($submission->id);
            }

            return null;
        }

        $submission = $this->submissionRepository
            ->saveSubmission(
                $assignmentId,
                $studentId,
                $data
            );

        activity('odev_teslim')
            ->performedOn($assignment)
            ->withProperties([
                'student_id' => $studentId,
                'grade_score' => $data['grade_score'] ?? null,
                'file_path' => $data['file_path'] ?? null,
            ])
            ->log(
                AssignmentMessages::SUBMISSION_SAVED->value
            );

        return $submission;
    }

    public function uploadSubmissionFile($file, $assignmentId, $studentId)
{
    return $file->storeAs(
        'submissions/' . $assignmentId,
        $studentId . '_' . $file->getClientOriginalName(),
        'public'
    );
}

public function deleteSubmissionFile($filePath)
{
    if (empty($filePath)) {
        return;
    }

    \Illuminate\Support\Facades\Storage::disk('public')
        ->delete($filePath);
}

public function uploadAssignmentFile($file)
{
    return $file->storeAs(
        'assignments',
        $file->getClientOriginalName(),
        'public'
    );
}

public function createAssignmentWithExamCheck(array $data, ?int $examId)
    {
        // 1. Ham Puan Modu Kontrolü
        if ($examId) {
            // Exam modelini ve ilişkili ödevleri repository/model üzerinden buluyoruz
            $exam = \App\Models\Exam::with('examAssignments.assignment')->find($examId);
            
            if ($exam && ($exam->grading_type ?? 'weighted') === 'raw_sum') {
                $examWeight = (int) ($exam->weight ?? 80);
                $newAssignmentScore = (int) ($data['max_score'] ?? 0);
                
                $existingAssignmentsScore = 0;
                if ($exam->examAssignments) {
                    foreach ($exam->examAssignments as $ea) {
                        if ($ea->assignment) {
                            $existingAssignmentsScore += (int) ($ea->assignment->max_score ?? 0);
                        }
                    }
                }

                $totalSystemScore = $examWeight + $existingAssignmentsScore + $newAssignmentScore;

                if ($totalSystemScore > 100) {
                    // İş kuralı hatası fırlatıyoruz
                    throw new \InvalidArgumentException(\App\Enums\Messages\ExamMessages::RAW_SUM_EXCEEDED->value);
                }
            }
        }

        // 2. Ödevi Oluştur
        $assignment = $this->assignmentRepository->create($data);

        // 3. Sınav ile İlişkilendir
        if ($examId && $assignment) {
            \App\Models\ExamAssignment::create([
                'exam_id'       => $examId,
                'assignment_id' => $assignment->id
            ]);
        }

        return $assignment;
    }
}