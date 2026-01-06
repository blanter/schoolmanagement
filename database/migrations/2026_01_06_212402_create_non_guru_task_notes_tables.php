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
        Schema::create('non_guru_note_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('color')->default('#B9FBC0');
            $table->timestamps();
        });

        Schema::create('non_guru_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('non_guru_note_categories')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_checked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_guru_note_items');
        Schema::dropIfExists('non_guru_note_categories');
    }
};
