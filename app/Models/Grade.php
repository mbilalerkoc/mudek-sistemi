<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['student_id', 'midterm', 'final', 'makeup', 'average', 'letter_grade', 'status'];

    // Bu notun ait olduğu öğrenci
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}