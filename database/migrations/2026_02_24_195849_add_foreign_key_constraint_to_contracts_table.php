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
        // Add the foreign key constraint after both tables exist
        if (Schema::hasTable('contracts') && Schema::hasTable('contract_types')) {
            Schema::table('contracts', function (Blueprint $table) {
                // Check if the foreign key already exists to avoid duplication
                $table->foreign('contract_type_id')
                      ->references('id')
                      ->on('contract_types')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropForeign(['contract_type_id']);
            });
        }
    }
};