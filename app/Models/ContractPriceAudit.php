<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractPriceAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'workspace_id',
        'item_description',
        'old_unit_price',
        'new_unit_price',
        'quantity',
        'old_total_amount',
        'new_total_amount',
        'change_reason',
        'change_type'
    ];

    protected $casts = [
        'old_unit_price' => 'decimal:2',
        'new_unit_price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'old_total_amount' => 'decimal:2',
        'new_total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}