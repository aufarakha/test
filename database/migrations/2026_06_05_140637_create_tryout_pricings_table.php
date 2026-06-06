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
        Schema::create('tryout_pricings', function (Blueprint $table) {
            $table->id();
            $table->integer('tryout_quota_cost')->default(1); // Berapa kuota untuk 1x tryout
            $table->integer('view_answer_quota_cost')->default(1); // Berapa kuota untuk 1x lihat jawaban
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_pricings');
    }
};
