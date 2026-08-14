<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExamPaper extends Model
{
    use LogsActivity;

    protected $fillable = ['course_id', 'exam_type', 'question_paper_path', 'answer_key_path', 'exam_date'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['course_id', 'exam_type', 'question_paper_path', 'answer_key_path', 'exam_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Sınav kağıdı {$eventName}");
    }

    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function studentPapers() { return $this->hasMany(StudentPaper::class); }
}