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
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('midterm', 5, 2)->nullable();
            $table->decimal('final', 5, 2)->nullable();
            $table->decimal('makeup', 5, 2)->nullable();
            $table->decimal('average', 5, 2)->nullable();
            $table->string('letter_grade')->nullable();
            $table->enum('status', ['passed', 'failed', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};