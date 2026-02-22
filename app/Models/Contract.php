<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'title',
        'value',
        'start_date',
        'end_date',
        'client_id',
        'project_id',
        'contract_type_id',
        'description',
        'created_by',
        'signed_pdf',
        // Site supervisor assignment
        'site_supervisor_id',
        'quantity_approver_id',
        'accountant_id',
        'reviewer_id',
        'final_approver_id',
        // Journal entry tracking for Onyx Pro integration
        'journal_entry_number',
        'journal_entry_date',
        // Amendment request functionality
        'amendment_requested',
        'amendment_reason',
        'amendment_requested_at',
        'amendment_requested_by',
        'amendment_approved',
        'amendment_approved_at',
        'amendment_approved_by',
        // Archive functionality
        'is_archived',
        'archived_at',
        'archived_by',
        // Workflow status tracking
        'workflow_status',
        'quantity_approval_status',
        'management_review_status',
        'accounting_review_status',
        'final_approval_status',
        // Electronic signatures for each stage
        'quantity_approval_signature',
        'quantity_approval_signed_at',
        'management_approval_signature',
        'management_approval_signed_at',
        'final_approval_signature',
        'final_approval_signed_at',
        // Audit trail
        'workflow_notes'
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function contract_type()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    // Relationship for site supervisor
    public function siteSupervisor()
    {
        return $this->belongsTo(User::class, 'site_supervisor_id');
    }

    // Relationship for quantity approver
    public function quantityApprover()
    {
        return $this->belongsTo(User::class, 'quantity_approver_id');
    }

    // Relationship for accountant
    public function accountant()
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    // Relationship for reviewer
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Relationship for final approver
    public function finalApprover()
    {
        return $this->belongsTo(User::class, 'final_approver_id');
    }

    // Relationship for amendment requested by user
    public function amendmentRequestedBy()
    {
        return $this->belongsTo(User::class, 'amendment_requested_by');
    }

    // Relationship for amendment approved by user
    public function amendmentApprovedBy()
    {
        return $this->belongsTo(User::class, 'amendment_approved_by');
    }

    // Relationship for archived by user
    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    // Relationship for journal entries
    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    // Relationship for contract quantities
    public function quantities()
    {
        return $this->hasMany(ContractQuantity::class);
    }

    // Relationship for contract approvals
    public function approvals()
    {
        return $this->hasMany(ContractApproval::class);
    }

    // Relationship for contract amendments
    public function amendments()
    {
        return $this->hasMany(ContractAmendment::class);
    }
}