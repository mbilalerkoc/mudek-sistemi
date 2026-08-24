<?php
namespace App\Repositories;

use App\Models\Exam;
use App\Models\Question;
use App\Repositories\Interfaces\ExamRepositoryInterface;

class ExamRepository extends BaseRepository implements ExamRepositoryInterface
{
    public function __construct(Exam $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        return Exam::create($data);
    }
    
    public function getByCourse($courseId)
    {
        return $this->model->where('course_id', $courseId)->with('questions')->get();
    }

    public function getByType($courseId, $type)
    {
        return $this->model
            ->where('course_id', $courseId)
            ->where('exam_type', $type)
            ->get();
    }

    // Sınava ait detayları ilişkileriyle getirmek için
    public function findExamWithDetails($examId)
    {
        return $this->model->with([
            'questions',
            'studentExams.studentCourse.student',
            'studentExams.answers',
        ])->findOrFail($examId);
    }

    // Sınava soru eklemek için
    public function createQuestion(array $data)
    {
        return Question::create($data);
    }
}