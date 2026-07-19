<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_paper_id')->constrained()->onDelete('cascade'); // hangi sınava ait
            $table->foreignId('student_id')->constrained()->onDelete('cascade');    // hangi öğrenci
            $table->enum('level', ['best', 'average', 'worst']); // en iyi / orta / kötü
            $table->string('file_path');             // taranan kağıt dosyası
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_papers');
    }
};