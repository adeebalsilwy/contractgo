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
            // Site supervisor assignment
            $table->unsignedBigInteger('site_supervisor_id')->nullable()->after('client_id');
            $table->foreign('site_supervisor_id')->references('id')->on('users')->onDelete('set null');
            
            // Quantity upload and approval workflow fields
            $table->unsignedBigInteger('quantity_approver_id')->nullable()->after('site_supervisor_id');
            $table->foreign('quantity_approver_id')->references('id')->on('users')->onDelete('set null');
            
            // Accounting integration
            $table->unsignedBigInteger('accountant_id')->nullable()->after('quantity_approver_id');
            $table->foreign('accountant_id')->references('id')->on('users')->onDelete('set null');
            
            // Reviewer and final approver
            $table->unsignedBigInteger('reviewer_id')->nullable()->after('accountant_id');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('set null');
            
            $table->unsignedBigInteger('final_approver_id')->nullable()->after('reviewer_id');
            $table->foreign('final_approver_id')->references('id')->on('users')->onDelete('set null');
            
            // Journal entry tracking for Onyx Pro integration
            $table->string('journal_entry_number')->nullable()->after('final_approver_id');
            $table->timestamp('journal_entry_date')->nullable()->after('journal_entry_number');
            
            // Amendment request functionality
            $table->boolean('amendment_requested')->default(false)->after('journal_entry_date');
            $table->text('amendment_reason')->nullable()->after('amendment_requested');
            $table->timestamp('amendment_requested_at')->nullable()->after('amendment_reason');
            $table->unsignedBigInteger('amendment_requested_by')->nullable()->after('amendment_requested_at');
            $table->foreign('amendment_requested_by')->references('id')->on('users')->onDelete('set null');
            
            // Amendment approval
            $table->boolean('amendment_approved')->default(false)->after('amendment_requested_by');
            $table->timestamp('amendment_approved_at')->nullable()->after('amendment_approved');
            $table->unsignedBigInteger('amendment_approved_by')->nullable()->after('amendment_approved_at');
            $table->foreign('amendment_approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Archive functionality
            $table->boolean('is_archived')->default(false)->after('amendment_approved_by');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
            $table->unsignedBigInteger('archived_by')->nullable()->after('archived_at');
            $table->foreign('archived_by')->references('id')->on('users')->onDelete('set null');
            
            // Workflow status tracking
            $table->enum('workflow_status', [
                'draft',                    // Initial state
                'site_supervisor_upload',   // Waiting for site supervisor to upload quantities
                'quantity_approval',        // Awaiting quantity approval
                'management_review',        // Under management review
                'accounting_processing',    // Being processed by accounting
                'final_review',             // Under final review
                'approved',                 // Fully approved
                'amendment_pending',        // Amendment requested but pending approval
                'amendment_approved',       // Amendment approved, workflow restarted
                'archived'                  // Archived after completion
            ])->default('draft')->after('archived_by');
            
            // Individual approval status fields
            $table->enum('quantity_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('workflow_status');
            $table->enum('management_review_status', ['pending', 'approved', 'rejected'])->default('pending')->after('quantity_approval_status');
            $table->enum('accounting_review_status', ['pending', 'approved', 'rejected'])->default('pending')->after('management_review_status');
            $table->enum('final_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('accounting_review_status');
            
            // Electronic signatures for each stage
            $table->text('quantity_approval_signature')->nullable()->after('final_approval_status');
            $table->timestamp('quantity_approval_signed_at')->nullable()->after('quantity_approval_signature');
            $table->text('management_approval_signature')->nullable()->after('quantity_approval_signed_at');
            $table->timestamp('management_approval_signed_at')->nullable()->after('management_approval_signature');
            $table->text('final_approval_signature')->nullable()->after('management_approval_signed_at');
            $table->timestamp('final_approval_signed_at')->nullable()->after('final_approval_signature');
            
            // Audit trail
            $table->text('workflow_notes')->nullable()->after('final_approval_signed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['site_supervisor_id']);
            $table->dropForeign(['quantity_approver_id']);
            $table->dropForeign(['accountant_id']);
            $table->dropForeign(['reviewer_id']);
            $table->dropForeign(['final_approver_id']);
            $table->dropForeign(['amendment_requested_by']);
            $table->dropForeign(['amendment_approved_by']);
            $table->dropForeign(['archived_by']);
            
            // Drop columns
            $table->dropColumn([
                'site_supervisor_id',
                'quantity_approver_id',
                'accountant_id',
                'reviewer_id',
                'final_approver_id',
                'journal_entry_number',
                'journal_entry_date',
                'amendment_requested',
                'amendment_reason',
                'amendment_requested_at',
                'amendment_requested_by',
                'amendment_approved',
                'amendment_approved_at',
                'amendment_approved_by',
                'is_archived',
                'archived_at',
                'archived_by',
                'workflow_status',
                'quantity_approval_signature',
                'quantity_approval_signed_at',
                'management_approval_signature',
                'management_approval_signed_at',
                'final_approval_signature',
                'final_approval_signed_at',
                'workflow_notes'
            ]);
        });
    }
};