<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCourse extends Model
{
    // Tablo adını açıkça belirtiyoruz (Laravel varsayılan olarak student_courses'u bulur ama garanti olsun)
    protected $table = 'student_courses';

    protected $guarded = [];

    // Bu kaydın ait olduğu öğrenci
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Bu kaydın ait olduğu ders
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}