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
        Schema::create('task_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('jenis', ['days', 'week', 'month']);
            $table->enum('tipe', ['guru', 'nonguru']);
            $table->string('judul_task');
            $table->integer('tahun');
            $table->integer('bulan');
            $table->enum('proyek', ['wajib', 'pribadi']);
            $table->string('link')->nullable();
            $table->string('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_checks');
    }
};
