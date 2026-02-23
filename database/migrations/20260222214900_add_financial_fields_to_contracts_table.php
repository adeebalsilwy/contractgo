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
            $table->string('financial_status')->nullable()->after('workflow_notes');
            $table->unsignedBigInteger('invoice_reference')->nullable()->after('financial_status');
            
            // Add foreign key constraint
            $table->foreign('invoice_reference')->references('id')->on('estimates_invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['invoice_reference']);
            $table->dropColumn(['financial_status', 'invoice_reference']);
        });
    }
};