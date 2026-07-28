<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemesterToStudentCoursesTable extends Migration
{
    public function up(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->string('semester')->nullable()->after('course_id'); // Hangi dönem olduğu
        });
    }

    public function down(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
}