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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add the missing fields for advanced logging
            $table->unsignedBigInteger('user_id')->nullable()->after('workspace_id');
            $table->string('action', 100)->nullable()->after('activity');
            $table->string('entity_type', 100)->nullable()->after('action');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            $table->text('description')->nullable()->after('entity_type');
            $table->json('metadata')->nullable()->after('description');
            
            // Add indexes for performance
            $table->index(['user_id']);
            $table->index(['action']);
            $table->index(['entity_type', 'entity_id']);
            $table->index(['workspace_id', 'entity_type', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Remove the added fields
            $table->dropIndex(['user_id']); // Drop index first
            $table->dropIndex(['action']);
            $table->dropIndex(['entity_type', 'entity_id']);
            $table->dropIndex(['workspace_id', 'entity_type', 'action']);
            
            $table->dropColumn([
                'user_id',
                'action',
                'entity_type',
                'entity_id',
                'description',
                'metadata'
            ]);
        });
    }
};