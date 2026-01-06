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
        Schema::create('student_lifebooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('year');
            $table->integer('month');
            $table->longText('goals_monthly')->nullable();
            $table->longText('life_aspects')->nullable();
            $table->longText('vision_yearly')->nullable();
            $table->longText('vision_progress')->nullable();
            $table->longText('gratitude')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_lifebooks');
    }
};
