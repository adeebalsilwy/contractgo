<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->nullable()->after('status');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
        });
        
        // Populate status_id based on existing status values (for tasks with existing records)
        DB::table('tasks')->where('status', 'active')->update(['status_id' => 1]);
        // Add more mappings as needed for other status values
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
