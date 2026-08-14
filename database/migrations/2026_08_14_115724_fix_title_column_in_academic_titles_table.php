<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('academic_titles', function (Blueprint $table) {
        $table->string('title', 100)->change();
    });
}

public function down()
{
    Schema::table('academic_titles', function (Blueprint $table) {
        $table->integer('title')->change();
    });
}
};
