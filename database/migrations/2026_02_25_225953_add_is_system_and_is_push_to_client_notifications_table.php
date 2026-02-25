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
        if (!Schema::hasColumn('client_notifications', 'is_system')) {
            Schema::table('client_notifications', function (Blueprint $table) {
                $table->boolean('is_system')->default(false)->after('read_at');
            });
        }
        
        if (!Schema::hasColumn('client_notifications', 'is_push')) {
            Schema::table('client_notifications', function (Blueprint $table) {
                $table->boolean('is_push')->default(false)->after('is_system');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_notifications', function (Blueprint $table) {
            $table->dropColumn(['is_system', 'is_push']);
        });
    }
};