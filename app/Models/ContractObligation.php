<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractObligation extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'party_id',
        'party_type',
        'title',
        'description',
        'obligation_type',
        'priority',
        'status',
        'due_date',
        'completed_date',
        'assigned_to',
        'notes',
        'supporting_documents',
        'compliance_status',
        'compliance_notes',
        'compliance_checked_by',
        'compliance_checked_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
        'supporting_documents' => 'array',
        'compliance_checked_at' => 'datetime'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function party()
    {
        return $this->belongsTo(User::class, 'party_id'); // Party is a user (client, contractor, etc.)
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function complianceCheckedBy()
    {
        return $this->belongsTo(User::class, 'compliance_checked_by');
    }

    // Scope to get overdue obligations
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'cancelled');
    }

    // Scope to get pending obligations
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope to get in-progress obligations
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // Scope to get completed obligations
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Check if obligation is overdue
    public function getIsOverdueAttribute()
    {
        return $this->status !== 'completed' && 
               $this->due_date && 
               $this->due_date->lt(now()) && 
               $this->status !== 'cancelled';
    }

    // Check if obligation is due soon (within 7 days)
    public function getIsDueSoonAttribute()
    {
        return $this->status !== 'completed' && 
               $this->due_date && 
               $this->due_date->between(now(), now()->addDays(7)) &&
               $this->status !== 'cancelled';
    }
}