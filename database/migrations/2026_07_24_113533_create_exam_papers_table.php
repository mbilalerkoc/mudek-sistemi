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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('exam_type', ['midterm', 'final', 'makeup']);
            $table->string('question_paper_path');
            $table->string('answer_paper_path');
            $table->date('exam_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_papers');
    }
};