<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_id')->unique(); // q1, q2, q51, dst
            $table->string('type'); // listening atau reading
            $table->text('question');
            $table->text('options')->nullable(); // JSON array
            $table->string('answer'); // Kunci jawaban (A, B, C, D, dst)
            $table->integer('score')->default(1);
            $table->text('audio_url')->nullable(); // untuk listening questions
            $table->text('image_url')->nullable(); // untuk questions dengan gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
