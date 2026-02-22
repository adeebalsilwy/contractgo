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
            // Individual approval status fields (if they don't exist)
            if (!Schema::hasColumn('contracts', 'quantity_approval_status')) {
                $table->enum('quantity_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('workflow_status');
            }
            if (!Schema::hasColumn('contracts', 'management_review_status')) {
                $table->enum('management_review_status', ['pending', 'approved', 'rejected'])->default('pending')->after('quantity_approval_status');
            }
            if (!Schema::hasColumn('contracts', 'accounting_review_status')) {
                $table->enum('accounting_review_status', ['pending', 'approved', 'rejected'])->default('pending')->after('management_review_status');
            }
            if (!Schema::hasColumn('contracts', 'final_approval_status')) {
                $table->enum('final_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('accounting_review_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'quantity_approval_status',
                'management_review_status', 
                'accounting_review_status',
                'final_approval_status'
            ]);
        });
    }
};