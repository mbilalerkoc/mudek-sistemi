<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = ['assignment_id', 'student_id', 'file_path', 'grade'];

    // Bu teslimin ait olduğu ödev
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    // Bu teslimin ait olduğu öğrenci
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}