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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('jenis', ['days', 'week', 'month']);
            $table->enum('tipe', ['guru', 'nonguru']);
            $table->string('judul_task');
            $table->enum('proyek', ['wajib', 'pribadi']);
            $table->string('week_num')->nullable();
            $table->string('month_num')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
