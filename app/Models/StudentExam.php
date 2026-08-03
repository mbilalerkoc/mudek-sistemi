<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentExam extends Model
{
    protected $table = 'student_exams';
    
    protected $guarded = [];

    // Hangi öğrenciye ait olduğu
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Hangi sınava ait olduğu
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    // Hangi öğrenci-kurs kaydına ait olduğu
    public function studentCourse(): BelongsTo
    {
        return $this->belongsTo(StudentCourse::class, 'student_course_id');
    }
}