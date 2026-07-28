<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAverageAndStatusToStudentCoursesTable extends Migration
{
    public function up(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->decimal('average', 5, 2)->nullable()->after('course_id'); // Ders ortalaması
            $table->string('status')->nullable()->after('average'); // Geçti / Kaldı durumu
        });
    }

    public function down(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->dropColumn(['average', 'status']);
        });
    }
}