<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Exam extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['course_id', 'exam_type', 'exam_date', 'question_paper_path', 'answers_paper_path'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Sınav {$eventName}");
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }
    
    public function studentExams() {
        return $this->hasMany(StudentExam::class);
    }
    public function examAssignments()
    {
        return $this->hasMany(ExamAssignment::class);
    }

}