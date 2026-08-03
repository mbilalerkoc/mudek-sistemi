<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentCourse extends Model
{
    protected $table = 'student_courses';
    protected $guarded = [];

    // Hangi öğrenciye ait olduğu
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Hangi derse ait olduğu
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // İLİŞKİ: Bu öğrenci-kurs kaydına ait sınav sonuçları (student_exams)
    public function studentExams(): HasMany
    {
        return $this->hasMany(StudentExam::class, 'student_course_id');
    }
}