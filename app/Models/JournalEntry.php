<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'invoice_id',
        'entry_number',
        'entry_type',
        'entry_date',
        'reference_number',
        'description',
        'debit_amount',
        'credit_amount',
        'account_code',
        'account_name',
        'created_by',
        'status',
        'posted_at',
        'posted_by',
        'posting_notes',
        'integration_data',
        'workspace_id'
    ];

    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'entry_date' => 'date',
        'posted_at' => 'datetime',
        'integration_data' => 'array', // Store as JSON array
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function invoice()
    {
        return $this->belongsTo(EstimatesInvoice::class, 'invoice_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
    
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}