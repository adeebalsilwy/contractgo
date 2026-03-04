<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'unit_id',
        'title',
        'price',
        'description'
    ];

    public function estimatesInvoices()
    {
        return $this->belongsToMany(EstimatesInvoice::class)
            ->withPivot('quantity'); // Include 'quantity' in the pivot table
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    // Relationship with ItemPricing
    public function itemPricings()
    {
        return $this->hasMany(ItemPricing::class);
    }

    public function getCurrentPricingAttribute()
    {
        return $this->itemPricings()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            })
            ->orderByDesc('created_at')
            ->first();
    }

    public function getEffectivePriceAttribute()
    {
        $currentPricing = $this->currentPricing;
        return $currentPricing ? $currentPricing->price : $this->price;
    }
    
    // Relationship with contract quantities (obligations/commitments)
    public function contractQuantities()
    {
        return $this->hasMany(ContractQuantity::class, 'item_description', 'title');
    }
    
    // Get all contract quantities related to this item
    public function getRelatedContractQuantities()
    {
        return ContractQuantity::where('item_description', $this->title)
                             ->orWhere('item_description', 'LIKE', '%' . $this->title . '%')
                             ->get();
    }
    
    // Get total quantities committed in contracts for this item
    public function getTotalContractQuantities()
    {
        $quantities = $this->getRelatedContractQuantities();
        $total = 0;
        
        foreach ($quantities as $quantity) {
            // Use approved quantity if available, otherwise use requested
            $total += $quantity->approved_quantity ?? $quantity->requested_quantity;
        }
        
        return $total;
    }
}