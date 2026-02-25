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
        if (Schema::hasTable('payslips') && Schema::hasTable('payment_methods')) {
            Schema::table('payslips', function (Blueprint $table) {
                // Check if the foreign key already exists to avoid duplication
                $table->foreign('payment_method_id')
                      ->references('id')
                      ->on('payment_methods');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payslips')) {
            Schema::table('payslips', function (Blueprint $table) {
                $table->dropForeign(['payment_method_id']);
            });
        }
    }
};