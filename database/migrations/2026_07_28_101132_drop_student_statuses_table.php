<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropStudentStatusesTable extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('student_statuses');
    }

    public function down(): void
    {
        Schema::create('student_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }
}