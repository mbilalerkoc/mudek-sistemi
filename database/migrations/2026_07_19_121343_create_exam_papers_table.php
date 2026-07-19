<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // hangi derse ait
            $table->enum('exam_type', ['midterm', 'final', 'makeup']);          // vize / final / bütünleme
            $table->string('question_paper_path');  // sınav soruları dosyası
            $table->string('answer_key_path');      // cevap anahtarı dosyası
            $table->date('exam_date');              // sınav tarihi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_papers');
    }
};