<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['user_id', 'code', 'name', 'credits', 'semester'];

    // Bu dersi veren öğretmen
    public function users() {
    return $this->belongsToMany(User::class, 'user_courses', 'course_id', 'user_id');
}
public function students() {
    return $this->belongsToMany(Student::class, 'student_courses', 'course_id', 'student_id');
}
public function studentCourses() {
    return $this->hasMany(StudentCourse::class, 'course_id');
}

    // Bu dersin ödevleri
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // Bu dersin sınavları
    public function examPapers()
    {
        return $this->hasMany(ExamPaper::class);
    }
}