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
        Schema::table('todos', function (Blueprint $table) {
            // Add creator_id column if it doesn't exist
            if (!Schema::hasColumn('todos', 'creator_id')) {
                $table->unsignedBigInteger('creator_id')->after('is_completed');
            }
            
            // Add creator_type column if it doesn't exist
            if (!Schema::hasColumn('todos', 'creator_type')) {
                $table->string('creator_type')->after('creator_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn(['creator_type', 'creator_id']);
        });
    }
};