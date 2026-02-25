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
            if (!Schema::hasColumn('contracts', 'site_supervisor_id')) {
                $table->unsignedBigInteger('site_supervisor_id')->nullable()->after('client_id');
                $table->foreign('site_supervisor_id')->references('id')->on('users')->onDelete('set null');
            }
            
            // Quantity upload and approval workflow fields
            if (!Schema::hasColumn('contracts', 'quantity_approver_id')) {
                $table->unsignedBigInteger('quantity_approver_id')->nullable()->after('site_supervisor_id');
                $table->foreign('quantity_approver_id')->references('id')->on('users')->onDelete('set null');
            }
            
            // Accounting integration
            if (!Schema::hasColumn('contracts', 'accountant_id')) {
                $table->unsignedBigInteger('accountant_id')->nullable()->after('quantity_approver_id');
                $table->foreign('accountant_id')->references('id')->on('users')->onDelete('set null');
            }
            
            // Reviewer and final approver
            if (!Schema::hasColumn('contracts', 'reviewer_id')) {
                $table->unsignedBigInteger('reviewer_id')->nullable()->after('accountant_id');
                $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('contracts', 'final_approver_id')) {
                $table->unsignedBigInteger('final_approver_id')->nullable()->after('reviewer_id');
                $table->foreign('final_approver_id')->references('id')->on('users')->onDelete('set null');
            }
            
            // Journal entry tracking for Onyx Pro integration
            if (!Schema::hasColumn('contracts', 'journal_entry_number')) {
                $table->string('journal_entry_number')->nullable()->after('final_approver_id');
            }
            if (!Schema::hasColumn('contracts', 'journal_entry_date')) {
                $table->timestamp('journal_entry_date')->nullable()->after('journal_entry_number');
            }
            
            // Amendment request functionality
            if (!Schema::hasColumn('contracts', 'amendment_requested')) {
                $table->boolean('amendment_requested')->default(false)->after('journal_entry_date');
            }
            if (!Schema::hasColumn('contracts', 'amendment_reason')) {
                $table->text('amendment_reason')->nullable()->after('amendment_requested');
            }
            if (!Schema::hasColumn('contracts', 'amendment_requested_at')) {
                $table->timestamp('amendment_requested_at')->nullable()->after('amendment_reason');
            }
            if (!Schema::hasColumn('contracts', 'amendment_requested_by')) {
                $table->unsignedBigInteger('amendment_requested_by')->nullable()->after('amendment_requested_at');
                $table->foreign('amendment_requested_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Amendment approval
            if (!Schema::hasColumn('contracts', 'amendment_approved')) {
                $table->boolean('amendment_approved')->default(false)->after('amendment_requested_by');
            }
            if (!Schema::hasColumn('contracts', 'amendment_approved_at')) {
                $table->timestamp('amendment_approved_at')->nullable()->after('amendment_approved');
            }
            if (!Schema::hasColumn('contracts', 'amendment_approved_by')) {
                $table->unsignedBigInteger('amendment_approved_by')->nullable()->after('amendment_approved_at');
                $table->foreign('amendment_approved_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Archive functionality
            if (!Schema::hasColumn('contracts', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('amendment_approved_by');
            }
            if (!Schema::hasColumn('contracts', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('is_archived');
            }
            if (!Schema::hasColumn('contracts', 'archived_by')) {
                $table->unsignedBigInteger('archived_by')->nullable()->after('archived_at');
                $table->foreign('archived_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Workflow status tracking
            if (!Schema::hasColumn('contracts', 'workflow_status')) {
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
            }
            
            // Individual approval status fields
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
            
            // Electronic signatures for each stage
            if (!Schema::hasColumn('contracts', 'quantity_approval_signature')) {
                $table->text('quantity_approval_signature')->nullable()->after('final_approval_status');
            }
            if (!Schema::hasColumn('contracts', 'quantity_approval_signed_at')) {
                $table->timestamp('quantity_approval_signed_at')->nullable()->after('quantity_approval_signature');
            }
            if (!Schema::hasColumn('contracts', 'management_approval_signature')) {
                $table->text('management_approval_signature')->nullable()->after('quantity_approval_signed_at');
            }
            if (!Schema::hasColumn('contracts', 'management_approval_signed_at')) {
                $table->timestamp('management_approval_signed_at')->nullable()->after('management_approval_signature');
            }
            if (!Schema::hasColumn('contracts', 'final_approval_signature')) {
                $table->text('final_approval_signature')->nullable()->after('management_approval_signed_at');
            }
            if (!Schema::hasColumn('contracts', 'final_approval_signed_at')) {
                $table->timestamp('final_approval_signed_at')->nullable()->after('final_approval_signature');
            }
            
            // Audit trail
            if (!Schema::hasColumn('contracts', 'workflow_notes')) {
                $table->text('workflow_notes')->nullable()->after('final_approval_signed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('contracts', 'site_supervisor_id')) {
                $table->dropForeign(['site_supervisor_id']);
            }
            if (Schema::hasColumn('contracts', 'quantity_approver_id')) {
                $table->dropForeign(['quantity_approver_id']);
            }
            if (Schema::hasColumn('contracts', 'accountant_id')) {
                $table->dropForeign(['accountant_id']);
            }
            if (Schema::hasColumn('contracts', 'reviewer_id')) {
                $table->dropForeign(['reviewer_id']);
            }
            if (Schema::hasColumn('contracts', 'final_approver_id')) {
                $table->dropForeign(['final_approver_id']);
            }
            if (Schema::hasColumn('contracts', 'amendment_requested_by')) {
                $table->dropForeign(['amendment_requested_by']);
            }
            if (Schema::hasColumn('contracts', 'amendment_approved_by')) {
                $table->dropForeign(['amendment_approved_by']);
            }
            if (Schema::hasColumn('contracts', 'archived_by')) {
                $table->dropForeign(['archived_by']);
            }
            
            // Drop columns
            $columnsToDrop = [];
            if (Schema::hasColumn('contracts', 'site_supervisor_id')) $columnsToDrop[] = 'site_supervisor_id';
            if (Schema::hasColumn('contracts', 'quantity_approver_id')) $columnsToDrop[] = 'quantity_approver_id';
            if (Schema::hasColumn('contracts', 'accountant_id')) $columnsToDrop[] = 'accountant_id';
            if (Schema::hasColumn('contracts', 'reviewer_id')) $columnsToDrop[] = 'reviewer_id';
            if (Schema::hasColumn('contracts', 'final_approver_id')) $columnsToDrop[] = 'final_approver_id';
            if (Schema::hasColumn('contracts', 'journal_entry_number')) $columnsToDrop[] = 'journal_entry_number';
            if (Schema::hasColumn('contracts', 'journal_entry_date')) $columnsToDrop[] = 'journal_entry_date';
            if (Schema::hasColumn('contracts', 'amendment_requested')) $columnsToDrop[] = 'amendment_requested';
            if (Schema::hasColumn('contracts', 'amendment_reason')) $columnsToDrop[] = 'amendment_reason';
            if (Schema::hasColumn('contracts', 'amendment_requested_at')) $columnsToDrop[] = 'amendment_requested_at';
            if (Schema::hasColumn('contracts', 'amendment_requested_by')) $columnsToDrop[] = 'amendment_requested_by';
            if (Schema::hasColumn('contracts', 'amendment_approved')) $columnsToDrop[] = 'amendment_approved';
            if (Schema::hasColumn('contracts', 'amendment_approved_at')) $columnsToDrop[] = 'amendment_approved_at';
            if (Schema::hasColumn('contracts', 'amendment_approved_by')) $columnsToDrop[] = 'amendment_approved_by';
            if (Schema::hasColumn('contracts', 'is_archived')) $columnsToDrop[] = 'is_archived';
            if (Schema::hasColumn('contracts', 'archived_at')) $columnsToDrop[] = 'archived_at';
            if (Schema::hasColumn('contracts', 'archived_by')) $columnsToDrop[] = 'archived_by';
            if (Schema::hasColumn('contracts', 'workflow_status')) $columnsToDrop[] = 'workflow_status';
            if (Schema::hasColumn('contracts', 'quantity_approval_signature')) $columnsToDrop[] = 'quantity_approval_signature';
            if (Schema::hasColumn('contracts', 'quantity_approval_signed_at')) $columnsToDrop[] = 'quantity_approval_signed_at';
            if (Schema::hasColumn('contracts', 'management_approval_signature')) $columnsToDrop[] = 'management_approval_signature';
            if (Schema::hasColumn('contracts', 'management_approval_signed_at')) $columnsToDrop[] = 'management_approval_signed_at';
            if (Schema::hasColumn('contracts', 'final_approval_signature')) $columnsToDrop[] = 'final_approval_signature';
            if (Schema::hasColumn('contracts', 'final_approval_signed_at')) $columnsToDrop[] = 'final_approval_signed_at';
            if (Schema::hasColumn('contracts', 'workflow_notes')) $columnsToDrop[] = 'workflow_notes';
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};