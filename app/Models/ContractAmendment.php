<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractAmendment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'requested_by_user_id',
        'approved_by_user_id',
        'amendment_type',
        'request_reason',
        'details',
        'original_price',
        'new_price',
        'original_quantity',
        'new_quantity',
        'original_unit',
        'new_unit',
        'original_description',
        'new_description',
        'status',
        'approval_comments',
        'approved_at',
        'rejected_at',
        'digital_signature_path',
        'signed_at',
        'signed_by_user_id',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'original_quantity' => 'decimal:2',
        'new_quantity' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'signed_at' => 'datetime',
    ];

    // Relationships
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by_user_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('amendment_type', $type);
    }
}