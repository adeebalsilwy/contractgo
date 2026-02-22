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
}