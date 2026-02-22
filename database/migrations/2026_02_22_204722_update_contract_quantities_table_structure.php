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
        Schema::table('contract_quantities', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('contract_quantities', 'contract_id')) {
                $table->unsignedBigInteger('contract_id')->nullable();
                $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            }
            if (!Schema::hasColumn('contract_quantities', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable(); // Site supervisor who uploaded the quantities
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('contract_quantities', 'item_description')) {
                $table->string('item_description')->nullable(); // Description of the work item
            }
            if (!Schema::hasColumn('contract_quantities', 'requested_quantity')) {
                $table->decimal('requested_quantity', 15, 2)->nullable(); // Quantity requested by site supervisor
            }
            if (!Schema::hasColumn('contract_quantities', 'approved_quantity')) {
                $table->decimal('approved_quantity', 15, 2)->nullable(); // Quantity approved by manager
            }
            if (!Schema::hasColumn('contract_quantities', 'unit')) {
                $table->string('unit')->nullable(); // Unit of measurement
            }
            if (!Schema::hasColumn('contract_quantities', 'unit_price')) {
                $table->decimal('unit_price', 15, 2)->nullable(); // Price per unit
            }
            if (!Schema::hasColumn('contract_quantities', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->nullable(); // Calculated total amount
            }
            if (!Schema::hasColumn('contract_quantities', 'notes')) {
                $table->text('notes')->nullable(); // Additional notes
            }
            if (!Schema::hasColumn('contract_quantities', 'supporting_documents')) {
                $table->text('supporting_documents')->nullable(); // JSON array of document paths
            }
            if (!Schema::hasColumn('contract_quantities', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'modified'])->default('pending');
            }
            if (!Schema::hasColumn('contract_quantities', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable();
            }
            if (!Schema::hasColumn('contract_quantities', 'approved_rejected_at')) {
                $table->timestamp('approved_rejected_at')->nullable();
            }
            if (!Schema::hasColumn('contract_quantities', 'approved_rejected_by')) {
                $table->unsignedBigInteger('approved_rejected_by')->nullable(); // Manager who approved/rejected
                $table->foreign('approved_rejected_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('contract_quantities', 'approval_rejection_notes')) {
                $table->text('approval_rejection_notes')->nullable(); // Reason for rejection or modification
            }
            if (!Schema::hasColumn('contract_quantities', 'quantity_approval_signature')) {
                $table->text('quantity_approval_signature')->nullable(); // Digital signature for approval
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_quantities', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['approved_rejected_by']);
            
            $table->dropColumn([
                'contract_id',
                'user_id',
                'item_description',
                'requested_quantity',
                'approved_quantity',
                'unit',
                'unit_price',
                'total_amount',
                'notes',
                'supporting_documents',
                'status',
                'submitted_at',
                'approved_rejected_at',
                'approved_rejected_by',
                'approval_rejection_notes',
                'quantity_approval_signature'
            ]);
        });
    }
};