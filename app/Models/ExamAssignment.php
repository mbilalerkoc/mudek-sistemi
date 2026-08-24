<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExamAssignment extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'exam_assignments'; // Veritabanı tablosu adı
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['exam_id', 'assignment_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Sınav ödev ilişkisi {$eventName}");
    }

    public function exam() {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function assignment() {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}