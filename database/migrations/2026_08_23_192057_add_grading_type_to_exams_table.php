<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // weighted: Yüzdelik Ağırlıklı, raw_sum: Ham Puan Toplama
            $table->string('grading_type')->default('weighted')->after('weight');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('grading_type');
        });
    }
};