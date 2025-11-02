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
        Schema::create('gdpr_data_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->enum('type', ['export', 'delete']); // Typ av GDPR-request
            $table->string('token', 64)->unique(); // Verifikationstoken
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamp('expires_at'); // Token expiration
            $table->timestamp('processed_at')->nullable();
            $table->text('data')->nullable(); // Export data (JSON) eller deletion metadata
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('token');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gdpr_data_requests');
    }
};
