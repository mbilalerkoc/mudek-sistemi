<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade'); // hangi ödev
            $table->foreignId('student_id')->constrained()->onDelete('cascade');    // hangi öğrenci
            $table->string('file_path');             // yüklenen dosya yolu
            $table->decimal('grade', 5, 2)->nullable(); // ödev notu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};