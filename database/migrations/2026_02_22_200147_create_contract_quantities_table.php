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
        if (!Schema::hasTable('contract_quantities')) {
            Schema::create('contract_quantities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contract_id');
                $table->unsignedBigInteger('user_id'); // Site supervisor who uploaded the quantities
                $table->string('item_description'); // Description of the work item
                $table->decimal('requested_quantity', 15, 2); // Quantity requested by site supervisor
                $table->decimal('approved_quantity', 15, 2)->nullable(); // Quantity approved by manager
                $table->string('unit'); // Unit of measurement
                $table->decimal('unit_price', 15, 2)->nullable(); // Price per unit
                $table->decimal('total_amount', 15, 2)->nullable(); // Calculated total amount
                $table->text('notes')->nullable(); // Additional notes
                $table->text('supporting_documents')->nullable(); // JSON array of document paths
                $table->enum('status', ['pending', 'approved', 'rejected', 'modified'])->default('pending');
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('approved_rejected_at')->nullable();
                $table->unsignedBigInteger('approved_rejected_by')->nullable(); // Manager who approved/rejected
                $table->text('approval_rejection_notes')->nullable(); // Reason for rejection or modification
                $table->text('quantity_approval_signature')->nullable(); // Digital signature for approval
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_rejected_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_quantities');
    }
};