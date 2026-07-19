<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['course_id', 'name', 'student_no'];

    // Bu öğrencinin ait olduğu ders
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Bu öğrencinin notu
    public function grade()
    {
        return $this->hasOne(Grade::class);
    }

    // Bu öğrencinin ödev teslimleri
    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    // Bu öğrencinin sınav kağıtları
    public function studentPapers()
    {
        return $this->hasMany(StudentPaper::class);
    }
}