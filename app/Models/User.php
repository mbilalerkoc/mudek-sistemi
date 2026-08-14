<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity; // LogsActivity eklendi

    protected $fillable = [
    'name',
    'surname',
    'email',
    'password',
    'role',
    'academic_title_id',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Activitylog ayarları
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role']) // Hangi sütunlar loglanacak (Şifreyi güvenlik için hariç tuttuk)
            ->logOnlyDirty() // Sadece değişen verileri kaydet
            ->dontSubmitEmptyLogs() // Değişiklik yoksa boş log atma
            ->setDescriptionForEvent(fn(string $eventName) => "Kullanıcı {$eventName}"); // Oluşturuldu, güncellendi gibi olay açıklamaları
    }

    // Bu öğretmenin dersleri
    public function courses() {
        return $this->belongsToMany(Course::class, 'user_courses', 'user_id', 'course_id');
    }

    public function academicTitle()
    {
        return $this->belongsTo(AcademicTitle::class, 'academic_title_id');
    }
}