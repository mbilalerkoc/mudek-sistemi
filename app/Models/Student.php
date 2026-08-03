<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\StudentExam;

class Student extends Model
{
    protected $guarded = []; // veya gerekli doldurulabilir alanlar

    // Bu öğrencinin dersleri (pivot tablo: student_courses)
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'student_courses', 'student_id', 'course_id')
                    ->withPivot('semester', 'midterm', 'final', 'makeup');
    }

    // student_courses tablosuna doğrudan hasMany ilişkisi (Notları ve ara tablo verilerini çekmek için)
    public function studentCourses(): HasMany
    {
        return $this->hasMany(StudentCourse::class, 'student_id');
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

    public function studentExams(): HasManyThrough
    {
        return $this->hasManyThrough(
            StudentExam::class,   // Ulaşmak istediğimiz hedef tablo (Model)
            StudentCourse::class, // Köprü/Ara tablo (Model)
            'student_id',         // StudentCourse tablosundaki öğrenci sütunu
            'student_course_id',  // StudentExam tablosundaki köprü ID sütunu
            'id',                 // Students tablosundaki anahtar
            'id'                  // StudentCourses tablosundaki anahtar
        );
    }
}