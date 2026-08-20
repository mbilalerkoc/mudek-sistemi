<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssignmentSubmission extends Model
{
    use LogsActivity;

    protected $fillable = ['assignment_id', 'student_id', 'file_path', 'grade_score'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['assignment_id', 'student_id', 'file_path', 'grade'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Ödev teslimi {$eventName}");
    }

    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function student() { return $this->belongsTo(Student::class); }
}