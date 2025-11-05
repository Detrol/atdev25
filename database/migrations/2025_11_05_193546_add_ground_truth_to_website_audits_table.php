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
        Schema::table('website_audits', function (Blueprint $table) {
            // Ground truth data: exact, deterministic measurements from WebsiteDataCollector
            $table->json('ground_truth_data')->nullable()->after('collected_data');

            // Validation status: whether AI report passed validation against ground truth
            $table->boolean('validation_passed')->default(true)->after('overall_score');

            // Validation errors (if any) for debugging
            $table->json('validation_errors')->nullable()->after('validation_passed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_audits', function (Blueprint $table) {
            $table->dropColumn(['ground_truth_data', 'validation_passed', 'validation_errors']);
        });
    }
};
