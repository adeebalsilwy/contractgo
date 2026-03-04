<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractQuantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'workspace_id',
        'item_id',
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
    ];

    protected $casts = [
        'requested_quantity' => 'decimal:2',
        'approved_quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_rejected_at' => 'datetime',
        'supporting_documents' => 'array', // Store as JSON array
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Site supervisor who submitted
    }

    public function approvedRejectedBy()
    {
        return $this->belongsTo(User::class, 'approved_rejected_by'); // Manager who approved/rejected
    }
    
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    
    // Relationship to connect ContractQuantity to Item via item_description matching title
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    // Method to check if this quantity can be modified after approval
    public function canBeModified()
    {
        // Allow modification if status is pending or rejected
        return in_array($this->status, ['pending', 'rejected']);
    }
    
    // Method to check if this quantity is bound to contract (cannot be re-bound after approval)
    public function isBoundToContract()
    {
        // Once approved, the quantity is bound to the contract and cannot be re-assigned
        return in_array($this->status, ['approved', 'modified']);
    }
}