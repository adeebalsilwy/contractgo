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
        Schema::create('contract_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->enum('approval_stage', [
                'quantity_approval',      // Stage for approving quantities
                'management_review',      // Management review stage
                'accounting_review',      // Accounting review stage
                'final_approval'          // Final approval stage
            ]);
            $table->unsignedBigInteger('approver_id'); // User ID of the person who approves
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comments')->nullable(); // Approver's comments
            $table->timestamp('approved_rejected_at')->nullable();
            $table->text('approval_signature')->nullable(); // Digital signature
            $table->text('rejection_reason')->nullable(); // If rejected, the reason
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_approvals');
    }
};