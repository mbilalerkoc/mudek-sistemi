<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['user_id', 'code', 'name', 'credits', 'semester'];

    // Bu dersi veren öğretmen
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bu dersin öğrencileri
    public function students()
    {
        return $this->hasMany(Student::class);
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