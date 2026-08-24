<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Assignment extends Model
{
    use LogsActivity;

    protected $fillable = ['course_id', 'title', 'description', 'due_date', 'max_score', 'file_path'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'description',
                'max_score',
                'due_date',
                'course_id'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(
                fn(string $eventName) => "Odev {$eventName}"
            );
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
    public function examAssignments()
    {
        return $this->hasMany(ExamAssignment::class, 'assignment_id', 'id');
    }
}