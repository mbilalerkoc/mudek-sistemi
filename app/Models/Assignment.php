<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['course_id', 'title', 'description', 'due_date'];

    // Bu ödevin ait olduğu ders
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Bu ödevin teslimleri
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}