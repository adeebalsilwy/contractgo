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
        // Check if columns already exist before adding them
        if (!Schema::hasColumn('notification_user', 'is_system')) {
            Schema::table('notification_user', function (Blueprint $table) {
                $table->boolean('is_system')->default(false)->after('read_at');
            });
        }
        
        if (!Schema::hasColumn('notification_user', 'is_push')) {
            Schema::table('notification_user', function (Blueprint $table) {
                $table->boolean('is_push')->default(false)->after('is_system');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_user', function (Blueprint $table) {
            $table->dropColumn(['is_system', 'is_push']);
        });
    }
};