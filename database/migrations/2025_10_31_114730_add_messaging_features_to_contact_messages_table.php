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
        Schema::table('contact_messages', function (Blueprint $table) {
            // Unik token för att identifiera replies via email
            $table->string('reply_token', 64)->unique()->after('id');

            // Threading - koppla replies till original meddelande
            $table->foreignId('parent_id')->nullable()->after('reply_token')
                ->constrained('contact_messages')->onDelete('cascade');

            // Status tracking
            $table->enum('status', ['pending', 'replied', 'closed'])->default('pending')->after('read');

            // Flagga för att identifiera admin-svar
            $table->boolean('is_admin_reply')->default(false)->after('status');

            // Spåra vem som svarade och när
            $table->foreignId('admin_user_id')->nullable()->after('is_admin_reply')
                ->constrained('users')->onDelete('set null');
            $table->timestamp('replied_at')->nullable()->after('admin_user_id');

            // Index för prestanda
            $table->index('reply_token');
            $table->index('parent_id');
            $table->index('status');
            $table->index('is_admin_reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            // Ta bort i omvänd ordning
            $table->dropIndex(['is_admin_reply']);
            $table->dropIndex(['status']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['reply_token']);

            $table->dropColumn(['replied_at', 'admin_user_id', 'is_admin_reply', 'status', 'parent_id', 'reply_token']);
        });
    }
};
