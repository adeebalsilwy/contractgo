<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'approval_stage',
        'approver_id',
        'status',
        'comments',
        'approved_rejected_at',
        'approval_signature',
        'rejection_reason'
    ];

    protected $casts = [
        'approved_rejected_at' => 'datetime',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}