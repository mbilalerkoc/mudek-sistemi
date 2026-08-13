<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Sınavın ait olduğu ders
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Bu sınava ait öğrenci sınav/not kayıtları
    public function studentExams()
    {
        return $this->hasMany(StudentExam::class, 'exam_id');
    }
}