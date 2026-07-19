<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPaper extends Model
{
    protected $fillable = ['exam_paper_id', 'student_id', 'level', 'file_path'];

    // Bu kağıdın ait olduğu sınav
    public function examPaper()
    {
        return $this->belongsTo(ExamPaper::class);
    }

    // Bu kağıdın ait olduğu öğrenci
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}