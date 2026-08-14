<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\Exam;
class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        foreach (['midterm', 'final', 'makeup'] as $type) {
            Exam::firstOrCreate([
                'course_id' => $course->id,
                'exam_type' => $type,
            ]);
        }
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }
}
