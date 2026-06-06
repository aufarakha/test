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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('std_code');
            $table->string('sch_code')->nullable();
            $table->integer('listening_score')->default(0);
            $table->integer('reading_score')->default(0);
            $table->integer('total_score')->default(0);
            $table->text('jawaban_peserta')->nullable(); // JSON field
            $table->string('device')->nullable();
            $table->boolean('is_view_only')->default(false); // true jika hanya melihat jawaban
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
