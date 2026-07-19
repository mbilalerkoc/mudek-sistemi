<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamPaper extends Model
{
    protected $fillable = ['course_id', 'exam_type', 'question_paper_path', 'answer_key_path', 'exam_date'];

    // Bu sınavın ait olduğu ders
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Bu sınavın öğrenci kağıtları
    public function studentPapers()
    {
        return $this->hasMany(StudentPaper::class);
    }
}