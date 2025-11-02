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
        Schema::create('cookie_consents', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->boolean('essential')->default(true); // Alltid aktiverad - nödvändiga cookies
            $table->boolean('functional')->default(false); // UI-preferenser (dark mode, chat)
            $table->boolean('analytics')->default(false); // Google Analytics, statistik
            $table->boolean('marketing')->default(false); // Social media pixels
            $table->string('ip_address')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index('session_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cookie_consents');
    }
};
