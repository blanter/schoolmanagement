<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_research_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('year');
            $table->integer('semester'); // 1: Jan-Jun, 2: Jul-Dec
            $table->boolean('judul_check')->default(false);
            $table->boolean('rumusan_check')->default(false);
            $table->boolean('penelitian_check')->default(false);
            $table->boolean('kesimpulan_check')->default(false);
            $table->text('research_link')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_research_projects');
    }
};
