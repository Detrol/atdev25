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
        Schema::create('website_audits', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('name');
            $table->string('email');
            $table->string('company')->nullable();
            $table->string('token')->unique();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('collected_data')->nullable();
            $table->text('ai_report')->nullable();
            $table->integer('seo_score')->nullable();
            $table->integer('performance_score')->nullable();
            $table->integer('overall_score')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['email', 'created_at']); // Rate limiting
            $table->index('url'); // Duplicate check
            $table->index('status'); // Filter by status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_audits');
    }
};
