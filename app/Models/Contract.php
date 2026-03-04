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
        'profession_id',
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
        'workflow_notes',
        // Financial integration
        'financial_status',
        'invoice_reference',
        // Value calculation method
        'is_calculated_from_extracts'
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
    
    public function profession()
    {
        return $this->belongsTo(Profession::class, 'profession_id');
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

    // Relationship for contract obligations
    public function obligations()
    {
        return $this->hasMany(ContractObligation::class);
    }

    // Method to get pending obligations
    public function getPendingObligations()
    {
        return $this->obligations()->where('status', 'pending');
    }

    // Method to get in-progress obligations
    public function getInProgressObligations()
    {
        return $this->obligations()->where('status', 'in_progress');
    }

    // Method to get completed obligations
    public function getCompletedObligations()
    {
        return $this->obligations()->where('status', 'completed');
    }

    // Method to get overdue obligations
    public function getOverdueObligations()
    {
        return $this->obligations()->overdue();
    }

    // Method to get obligations by type
    public function getObligationsByType($type)
    {
        return $this->obligations()->where('obligation_type', $type);
    }

    // Method to get obligations by party
    public function getObligationsByParty($partyId)
    {
        return $this->obligations()->where('party_id', $partyId);
    }

    // Relationship for contract quantities
    public function quantities()
    {
        return $this->hasMany(ContractQuantity::class);
    }
    
    // Method to get pending quantities for approval
    public function getPendingQuantities()
    {
        return $this->quantities()->where('status', 'pending');
    }
    
    // Method to check if all quantities are approved
    public function areAllQuantitiesApproved()
    {
        return $this->quantities()->where('status', 'pending')->count() === 0;
    }
    
    // Method to get approved quantities
    public function getApprovedQuantities()
    {
        return $this->quantities()->where('status', 'approved');
    }
    
    // Method to get rejected quantities
    public function getRejectedQuantities()
    {
        return $this->quantities()->where('status', 'rejected');
    }
    
    // Method to get modified quantities
    public function getModifiedQuantities()
    {
        return $this->quantities()->where('status', 'modified');
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
    
    // Relationship for created by user
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Relationship for invoice
    public function invoice()
    {
        return $this->belongsTo(EstimatesInvoice::class, 'invoice_reference');
    }
    
    // Relationship for estimates - directly linked to contract
    public function estimates()
    {
        return $this->hasMany(EstimatesInvoice::class, 'contract_id', 'id')
                    ->where('type', 'estimate')
                    ->orderBy('created_at', 'desc');
    }
    
    // Relationship for invoices - directly linked to contract
    public function invoices()
    {
        return $this->hasMany(EstimatesInvoice::class, 'contract_id', 'id')
                    ->where('type', 'invoice')
                    ->orderBy('created_at', 'desc');
    }
    
    // Combined relationship for all estimates and invoices related to this contract
    public function estimatesInvoices()
    {
        return $this->hasMany(EstimatesInvoice::class, 'contract_id', 'id')
                    ->orderBy('created_at', 'desc');
    }
    
    // Relationship for extracts (المسـتـخـلـصـات)
    public function extracts()
    {
        return $this->hasMany(EstimatesInvoice::class, 'contract_id', 'id')
                    ->orderBy('created_at', 'desc');
    }
    
    // Enhanced relationship to get all tasks related to this contract
    public function tasks()
    {
        return $this->hasMany(Task::class, 'contract_id', 'id')
                    ->where('tasks.workspace_id', getWorkspaceId());
    }
    
    // Enhanced relationship to get all clients related to this contract through project
    public function contractClients()
    {
        return $this->project ? $this->project->clients : collect();
    }
    
    // Method to calculate total value from associated extracts (estimates and invoices)
    public function getTotalExtractValueAttribute()
    {
        return $this->estimatesInvoices->sum('final_total');
    }
    
    // Method to calculate progress percentage based on extracts vs contract value
    public function getProgressPercentageAttribute()
    {
        if ($this->value <= 0) {
            return 0;
        }
        
        $extractValue = $this->total_extract_value;
        $percentage = ($extractValue / $this->value) * 100;
        return round($percentage, 2);
    }
    
    // Method to check if contract value is determined by extracts
    public function isValueBasedOnExtracts()
    {
        // In our system, contract value should be calculated from extracts
        return true;
    }
    
    // Mutator to ensure value is properly formatted
    public function setValueAttribute($value)
    {
        $cleanValue = preg_replace('/[^\d.]/', '', $value); // Remove non-numeric characters except decimal point
        $this->attributes['value'] = floatval($cleanValue);
    }
    
    // Accessor to get value formatted properly
    public function getValueAttribute($value)
    {
        return floatval($value);
    }
}