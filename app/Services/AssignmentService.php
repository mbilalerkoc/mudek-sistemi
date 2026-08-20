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

        /*
         * Puan ve dosya yoksa teslim kaydı olmamalı.
         */
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
}