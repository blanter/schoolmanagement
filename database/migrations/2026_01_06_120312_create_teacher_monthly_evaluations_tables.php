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
        // Table for 'guru' type evaluations (Fixed set per month)
        Schema::create('teacher_monthly_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('year');
            $table->integer('month');

            // Box 1: Monthly Evaluation
            $table->text('evaluasi')->nullable();
            $table->text('student_progress')->nullable();

            // Box 2: Monthly Review
            $table->text('review')->nullable();

            // Box 3: Monthly Reflection
            $table->text('berhasil')->nullable();
            $table->text('belum_berhasil')->nullable();
            $table->text('tauladan')->nullable();

            $table->timestamps();
        });

        // Table for 'nonguru' type evaluations (Dynamic list)
        Schema::create('teacher_nonguru_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('year');
            $table->integer('month');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_nonguru_evaluations');
        Schema::dropIfExists('teacher_monthly_evaluations');
    }
};
