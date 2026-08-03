<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {   
        // 0. Academic Titles
        Schema::create('academic_titles', function (Blueprint $table) {
            $table->id();
            $table->integer('title');
            $table->timestamps();
        });
        // 1. Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('academic_title_id')->nullable()->constrained('academic_titles')->onDelete('set null');
            $table->string('role')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        
        // 2. Courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->integer('credits')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
        });

        // 3. User Courses (Akademisyen - Ders ilişkisi)
        Schema::create('user_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->timestamps();
        });

        // 4. Students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('student_no')->unique();
            $table->timestamps();
        });

        // 5. Student Courses (Öğrenci - Ders ilişkisi ve notlar)
        Schema::create('student_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->decimal('average', 5, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // 6. Exams (Sınavlar tablosu)
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->enum('exam_type', ['midterm', 'final', 'makeup']);
            $table->dateTime('exam_date')->nullable();
            $table->string('question_paper_path')->nullable();
            $table->string('answers_paper_path')->nullable();
            $table->timestamps();
        });

        // 7. Student Exams (Öğrencinin sınav kağıdı/sonucu ve skor sütunları)
        Schema::create('student_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_course_id')->constrained('student_courses')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('path')->nullable();
            $table->decimal('exam_score', 5, 2)->nullable();
            $table->decimal('assignment_score', 5, 2)->nullable();
            $table->decimal('total_score', 5, 2)->nullable();
            $table->tinyInteger('level')->nullable();
            $table->timestamps();
        });

        // 8. Assignments (Ödevler)
        // 8. Assignments (Ödevler - Hangi sınava ait olduğu exam_id ile belirlenir)
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('max_score', 5, 2)->nullable(); // Ödevin max puanı
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('exam_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->timestamps();
        });

        // 9. Assignment Submissions (Ödev Teslimleri)
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->decimal('grade_score', 5, 2)->nullable();
            $table->timestamps();
        });

        // 10. Questions (Sorular)
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('file')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
        });

        // 11. Answers (Cevaplar - student_id yerine student_exam_id eklendi)
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('student_exam_id')->constrained('student_exams')->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('exam_assignments');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('student_exams');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('student_courses');
        Schema::dropIfExists('students');
        Schema::dropIfExists('user_courses');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('academic_titles');
    }
};