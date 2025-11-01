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
        Schema::create('price_estimations', function (Blueprint $table) {
            $table->id();

            // Input
            $table->text('description'); // Original user description

            // AI Analysis Results
            $table->string('project_type'); // simple|webapp|api|maintenance|custom
            $table->integer('complexity'); // 1-10
            $table->string('project_type_label'); // Formatted label for display
            $table->string('complexity_label'); // Description of complexity level
            $table->json('key_features')->nullable(); // Array of identified features

            // Time Estimates (hours) - raw values
            $table->integer('hours_traditional_min');
            $table->integer('hours_traditional_max');
            $table->integer('hours_ai_min');
            $table->integer('hours_ai_max');

            // Formatted time strings
            $table->string('hours_traditional'); // "X-Y timmar"
            $table->string('hours_ai'); // "X-Y timmar"

            // Price Estimates (ex VAT) - raw values
            $table->integer('price_traditional_min');
            $table->integer('price_traditional_max');
            $table->integer('price_ai_min');
            $table->integer('price_ai_max');

            // Formatted price strings (ex VAT)
            $table->string('price_traditional'); // "X-Y kr"
            $table->string('price_ai'); // "X-Y kr"

            // Price with VAT - raw values
            $table->integer('price_traditional_vat_min');
            $table->integer('price_traditional_vat_max');
            $table->integer('price_ai_vat_min');
            $table->integer('price_ai_vat_max');

            // Formatted with VAT
            $table->string('price_traditional_vat'); // "X-Y kr"
            $table->string('price_ai_vat'); // "X-Y kr"

            // Savings - raw values
            $table->integer('savings_min');
            $table->integer('savings_max');
            $table->string('savings'); // "X-Y kr"
            $table->integer('savings_vat_min');
            $table->integer('savings_vat_max');
            $table->string('savings_vat'); // "X-Y kr"
            $table->integer('savings_percent');

            // Delivery Time
            $table->string('delivery_weeks_traditional'); // "X veckor"
            $table->string('delivery_weeks_ai'); // "X veckor"

            // Link to Contact Message (nullable - not all estimations lead to contact)
            $table->foreignId('contact_message_id')
                ->nullable()
                ->constrained('contact_messages')
                ->onDelete('cascade');

            // Metadata
            $table->ipAddress('ip_address')->nullable();
            $table->string('session_id')->nullable(); // To track sessions

            $table->timestamps();

            // Indexes for queries
            $table->index('contact_message_id');
            $table->index('created_at');
            $table->index('project_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_estimations');
    }
};
