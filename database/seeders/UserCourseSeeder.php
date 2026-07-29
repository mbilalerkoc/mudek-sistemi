<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class UserCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Örnek: Belirli bir hocayı bulalım (Örn: emailine göre veya role göre)
        $teacher = User::where('role', 'teacher')->first();

        // Örnek: Bazı dersleri seçelim
        $courses = Course::take(3)->get(); // İlk 3 dersi alalım

        if ($teacher && $courses->isNotEmpty()) {
            foreach ($courses as $course) {
                // user_courses tablosuna ekleme yapıyoruz ( mükerrer kayıt olmaması için updateOrInsert kullanabiliriz )
                DB::table('user_courses')->updateOrInsert(
                    [
                        'user_id' => $teacher->id,
                        'course_id' => $course->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}