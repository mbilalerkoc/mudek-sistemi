<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StudentPaper extends Model
{
    use LogsActivity;

    protected $fillable = ['exam_paper_id', 'student_id', 'level', 'file_path'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['exam_paper_id', 'student_id', 'level', 'file_path'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Öğrenci kağıdı {$eventName}");
    }

    public function examPaper() { return $this->belongsTo(ExamPaper::class); }
    public function student() { return $this->belongsTo(Student::class); }
}