<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Grade extends Model
{
    use LogsActivity;

    protected $fillable = ['student_id', 'midterm', 'final', 'makeup', 'average', 'letter_grade', 'status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['student_id', 'midterm', 'final', 'makeup', 'average', 'letter_grade', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Not {$eventName}");
    }

    public function student() { return $this->belongsTo(Student::class); }
}