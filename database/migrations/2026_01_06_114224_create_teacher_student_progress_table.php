<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_student_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Teacher ID
            $table->integer('year');
            $table->integer('month');
            $table->text('student_ids'); // Stored as JSON or comma separated
            $table->string('subject');
            $table->string('score')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_student_progress');
    }
};
