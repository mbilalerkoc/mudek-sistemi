<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade'); // hangi öğrenci
            $table->decimal('midterm', 5, 2)->nullable();  // vize notu
            $table->decimal('final', 5, 2)->nullable();    // final notu
            $table->decimal('makeup', 5, 2)->nullable();   // bütünleme notu
            $table->decimal('average', 5, 2)->nullable();  // genel ortalama
            $table->string('letter_grade')->nullable();    // AA, BB, CC...
            $table->enum('status', ['passed', 'failed', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};