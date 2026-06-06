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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('std_code')->unique(); // NISN
            $table->string('std_name');
            $table->string('std_nisn')->unique();
            $table->string('std_gender')->nullable();
            $table->date('std_dob')->nullable();
            $table->string('std_npsn');
            $table->string('sch_code')->nullable();
            $table->string('std_school')->nullable();
            $table->string('std_class')->nullable();
            $table->string('std_email')->nullable();
            $table->string('std_phone')->nullable();
            $table->string('kompetensi_keahlian')->nullable();
            $table->string('program_keahlian')->nullable();
            $table->string('bidang_keahlian')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
