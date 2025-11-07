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
        Schema::table('price_estimations', function (Blueprint $table) {
            $table->string('website_url')->nullable()->after('description');
            $table->text('scraped_content')->nullable()->after('website_url');
            $table->json('scraped_metadata')->nullable()->after('scraped_content');
            $table->boolean('scrape_successful')->default(false)->after('scraped_metadata');
            $table->string('scrape_error')->nullable()->after('scrape_successful');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_estimations', function (Blueprint $table) {
            $table->dropColumn([
                'website_url',
                'scraped_content',
                'scraped_metadata',
                'scrape_successful',
                'scrape_error',
            ]);
        });
    }
};
