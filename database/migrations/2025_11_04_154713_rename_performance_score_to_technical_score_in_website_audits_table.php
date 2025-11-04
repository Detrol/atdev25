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
            $table->renameColumn('performance_score', 'technical_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_audits', function (Blueprint $table) {
            $table->renameColumn('technical_score', 'performance_score');
        });
    }
};
