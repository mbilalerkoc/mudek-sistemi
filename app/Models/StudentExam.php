<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StudentExam extends Model
{
    use LogsActivity;

    protected $table = 'student_exams';
    protected $guarded = [];
    protected $fillable = [
        'student_course_id', 'exam_id',
        'exam_score', 'assignment_score', 'total_score',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['student_course_id', 'exam_id', 'exam_score', 'assignment_score', 'total_score'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Öğrenci sınav kaydı {$eventName}");
    }

    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id'); }
    public function exam(): BelongsTo { return $this->belongsTo(Exam::class, 'exam_id'); }
    public function studentCourse(): BelongsTo { return $this->belongsTo(StudentCourse::class, 'student_course_id'); }
}