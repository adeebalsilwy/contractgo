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
        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'financial_status')) {
                $table->string('financial_status')->nullable(); // Don't specify position to avoid dependency
            }
            if (!Schema::hasColumn('contracts', 'invoice_reference')) {
                $table->unsignedBigInteger('invoice_reference')->nullable(); // Don't specify position to avoid dependency
                
                // Add foreign key constraint after column is added
                $table->foreign('invoice_reference')->references('id')->on('estimates_invoices')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'invoice_reference')) {
                $table->dropForeign(['invoice_reference']);
            }
            $table->dropColumn(['financial_status', 'invoice_reference']);
        });
    }
};