<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;

class WorkflowAuditService
{
    /**
     * Log workflow stage transition
     * 
     * @param Contract $contract
     * @param string $fromStage
     * @param string $toStage
     * @param array $metadata
     * @return ActivityLog
     */
    public function logStageTransition(Contract $contract, string $fromStage, string $toStage, array $metadata = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'workflow_stage_transition',
            'entity_type' => 'contract',
            'entity_id' => $contract->id,
            'description' => "Contract workflow transitioned from '{$fromStage}' to '{$toStage}'",
            'metadata' => array_merge([
                'from_stage' => $fromStage,
                'to_stage' => $toStage,
                'contract_title' => $contract->title,
                'contract_id' => $contract->id,
            ], $metadata),
        ]);
    }
    
    /**
     * Log electronic signature application
     * 
     * @param Contract $contract
     * @param string $stage
     * @param string $signatureType
     * @param string $signatureData
     * @return ActivityLog
     */
    public function logSignature(Contract $contract, string $stage, string $signatureType, string $signatureData): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'electronic_signature_applied',
            'entity_type' => 'contract',
            'entity_id' => $contract->id,
            'description' => "Electronic signature ({$signatureType}) applied at '{$stage}' stage",
            'metadata' => [
                'stage' => $stage,
                'signature_type' => $signatureType,
                'signer_name' => Auth::user()->full_name ?? 'Unknown',
                'signer_email' => Auth::user()->email ?? 'Unknown',
                'signed_at' => now()->toIso8601String(),
                'signature_hash' => hash('sha256', $signatureData),
            ],
        ]);
    }
    
    /**
     * Log quantity submission
     * 
     * @param Contract $contract
     * @param \App\Models\ContractQuantity $quantity
     * @return ActivityLog
     */
    public function logQuantitySubmission(Contract $contract, $quantity): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'quantity_submitted',
            'entity_type' => 'contract_quantity',
            'entity_id' => $quantity->id,
            'description' => "Quantity submitted for '{$contract->title}' - Item: {$quantity->item_description}, Qty: {$quantity->requested_quantity}",
            'metadata' => [
                'quantity_id' => $quantity->id,
                'item_description' => $quantity->item_description,
                'requested_quantity' => $quantity->requested_quantity,
                'unit' => $quantity->unit,
                'submitted_by' => Auth::user()->full_name ?? 'Unknown',
            ],
        ]);
    }
    
    /**
     * Log quantity approval/rejection
     * 
     * @param Contract $contract
     * @param \App\Models\ContractQuantity $quantity
     * @param string $action (approved/rejected)
     * @param string|null $reason
     * @return ActivityLog
     */
    public function logQuantityDecision(Contract $contract, $quantity, string $action, ?string $reason = null): ActivityLog
    {
        $description = "Quantity {$action} for '{$contract->title}'";
        if ($reason) {
            $description .= " - Reason: {$reason}";
        }
        
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'quantity_' . $action,
            'entity_type' => 'contract_quantity',
            'entity_id' => $quantity->id,
            'description' => $description,
            'metadata' => [
                'quantity_id' => $quantity->id,
                'action' => $action,
                'reason' => $reason,
                'decided_by' => Auth::user()->full_name ?? 'Unknown',
                'approved_quantity' => $quantity->approved_quantity ?? null,
            ],
        ]);
    }
    
    /**
     * Log amendment request
     * 
     * @param Contract $contract
     * @param \App\Models\ContractAmendment $amendment
     * @return ActivityLog
     */
    public function logAmendmentRequest(Contract $contract, $amendment): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'amendment_requested',
            'entity_type' => 'contract_amendment',
            'entity_id' => $amendment->id,
            'description' => "Amendment requested for '{$contract->title}' - Type: {$amendment->amendment_type}",
            'metadata' => [
                'amendment_id' => $amendment->id,
                'amendment_type' => $amendment->amendment_type,
                'request_reason' => $amendment->request_reason,
                'requested_by' => Auth::user()->full_name ?? 'Unknown',
            ],
        ]);
    }
    
    /**
     * Log amendment approval/rejection
     * 
     * @param Contract $contract
     * @param \App\Models\ContractAmendment $amendment
     * @param string $action (approved/rejected)
     * @param string|null $comments
     * @return ActivityLog
     */
    public function logAmendmentDecision(Contract $contract, $amendment, string $action, ?string $comments = null): ActivityLog
    {
        $description = "Amendment {$action} for '{$contract->title}'";
        if ($comments) {
            $description .= " - Comments: {$comments}";
        }
        
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'amendment_' . $action,
            'entity_type' => 'contract_amendment',
            'entity_id' => $amendment->id,
            'description' => $description,
            'metadata' => [
                'amendment_id' => $amendment->id,
                'action' => $action,
                'comments' => $comments,
                'decided_by' => Auth::user()->full_name ?? 'Unknown',
            ],
        ]);
    }
    
    /**
     * Log journal entry creation
     * 
     * @param Contract $contract
     * @param \App\Models\JournalEntry $journalEntry
     * @return ActivityLog
     */
    public function logJournalEntry(Contract $contract, $journalEntry): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'journal_entry_created',
            'entity_type' => 'journal_entry',
            'entity_id' => $journalEntry->id,
            'description' => "Journal entry created for '{$contract->title}' - Entry #: {$journalEntry->entry_number}",
            'metadata' => [
                'journal_entry_id' => $journalEntry->id,
                'entry_number' => $journalEntry->entry_number,
                'entry_type' => $journalEntry->entry_type,
                'debit_amount' => $journalEntry->debit_amount,
                'credit_amount' => $journalEntry->credit_amount,
            ],
        ]);
    }
    
    /**
     * Log archival
     * 
     * @param Contract $contract
     * @return ActivityLog
     */
    public function logArchival(Contract $contract): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'contract_archived',
            'entity_type' => 'contract',
            'entity_id' => $contract->id,
            'description' => "Contract '{$contract->title}' archived",
            'metadata' => [
                'contract_id' => $contract->id,
                'archived_by' => Auth::user()->full_name ?? 'Unknown',
                'workflow_status' => $contract->workflow_status,
            ],
        ]);
    }
    
    /**
     * Get complete audit trail for contract
     * 
     * @param Contract $contract
     * @return \Illuminate\Support\Collection
     */
    public function getAuditTrail(Contract $contract)
    {
        return ActivityLog::where('entity_type', 'contract')
            ->where('entity_id', $contract->id)
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
    }
    
    /**
     * Get audit trail with pagination
     * 
     * @param Contract $contract
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAuditTrailPaginated(Contract $contract, int $perPage = 20)
    {
        return ActivityLog::where('entity_type', 'contract')
            ->where('entity_id', $contract->id)
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->paginate($perPage);
    }
    
    /**
     * Get specific action logs
     * 
     * @param Contract $contract
     * @param string $action
     * @return \Illuminate\Support\Collection
     */
    public function getActionLogs(Contract $contract, string $action)
    {
        return ActivityLog::where('entity_type', 'contract')
            ->where('entity_id', $contract->id)
            ->where('action', $action)
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
    }
}
