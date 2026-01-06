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
        $tables = [
            'pemakmuran_teoris',
            'pemakmuran_cases',
            'pemakmuran_proyeks',
            'pemakmuran_problems',
            'pemakmuran_creatives'
        ];

        foreach ($tables as $tableName) {
            Schema::create($tableName, function (Blueprint $blueprint) {
                $blueprint->id();
                $blueprint->unsignedBigInteger('user_id');
                $blueprint->integer('year');
                $blueprint->integer('month');
                $blueprint->longText('content')->nullable();
                $blueprint->timestamps();

                $blueprint->index(['user_id', 'year', 'month']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakmuran_teoris');
        Schema::dropIfExists('pemakmuran_cases');
        Schema::dropIfExists('pemakmuran_proyeks');
        Schema::dropIfExists('pemakmuran_problems');
        Schema::dropIfExists('pemakmuran_creatives');
    }
};
