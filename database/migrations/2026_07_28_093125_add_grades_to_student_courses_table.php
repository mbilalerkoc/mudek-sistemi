<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->decimal('midterm', 5, 2)->nullable()->after('course_id');
            $table->decimal('final', 5, 2)->nullable()->after('midterm');
            $table->decimal('makeup', 5, 2)->nullable()->after('final');
        });
    }

    public function down(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->dropColumn(['midterm', 'final', 'makeup']);
        });
    }
};