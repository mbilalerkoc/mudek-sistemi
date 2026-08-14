<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StudentCourse extends Model
{
    use LogsActivity;

    protected $table = 'student_courses';
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['student_id', 'course_id', 'average', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Öğrenci ders kaydı {$eventName}");
    }

    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id'); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class, 'course_id'); }
    public function studentExams(): HasMany { return $this->hasMany(StudentExam::class, 'student_course_id'); }
}