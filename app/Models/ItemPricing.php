<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ItemPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'unit_id',
        'price',
        'cost_price',
        'min_selling_price',
        'max_selling_price',
        'pricing_tiers',
        'discounts',
        'taxes',
        'is_active',
        'valid_from',
        'valid_until',
        'created_by',
        'description'
    ];

    protected $casts = [
        'pricing_tiers' => 'array',
        'discounts' => 'array',
        'taxes' => 'array',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'min_selling_price' => 'decimal:2',
        'max_selling_price' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope to get active pricing
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Scope to get valid pricing for a specific date
    public function scopeValidOn(Builder $query, $date = null): Builder
    {
        $date = $date ?: now();
        
        return $query->where(function ($q) use ($date) {
                $q->whereNull('valid_from')
                  ->orWhere('valid_from', '<=', $date);
            })
            ->where(function ($q) use ($date) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', $date);
            });
    }

    // Get price based on quantity considering tiered pricing
    public function getPriceForQuantity(float $quantity): float
    {
        $basePrice = $this->price;

        // Check if we have tiered pricing
        if (!empty($this->pricing_tiers) && is_array($this->pricing_tiers)) {
            // Sort tiers by quantity descending to find the applicable tier
            $sortedTiers = collect($this->pricing_tiers)->sortByDesc('min_quantity')->values();
            
            foreach ($sortedTiers as $tier) {
                if ($quantity >= $tier['min_quantity']) {
                    return $tier['price'];
                }
            }
        }

        return $basePrice;
    }

    // Calculate final price with discounts and taxes applied
    public function calculateFinalPrice(float $quantity, float $unitPrice = null): float
    {
        $unitPrice = $unitPrice ?: $this->getPriceForQuantity($quantity);

        // Apply discounts
        $discountedPrice = $unitPrice;
        if (!empty($this->discounts) && is_array($this->discounts)) {
            foreach ($this->discounts as $discount) {
                if ($quantity >= $discount['min_quantity']) {
                    if ($discount['type'] === 'percentage') {
                        $discountedPrice = $discountedPrice * (1 - $discount['value'] / 100);
                    } elseif ($discount['type'] === 'fixed') {
                        $discountedPrice = max(0, $discountedPrice - $discount['value']);
                    }
                }
            }
        }

        // Apply taxes
        $finalPrice = $discountedPrice;
        if (!empty($this->taxes) && is_array($this->taxes)) {
            foreach ($this->taxes as $tax) {
                if ($quantity >= $tax['min_quantity']) {
                    $finalPrice = $finalPrice * (1 + $tax['rate'] / 100);
                }
            }
        }

        return $finalPrice;
    }

    // Calculate total price for a given quantity
    public function calculateTotalPrice(float $quantity): float
    {
        $unitPrice = $this->calculateFinalPrice($quantity);
        return $unitPrice * $quantity;
    }

    // Relationship to connect with contract quantities (obligations)
    public function contractQuantities()
    {
        // Since ContractQuantity uses item_description as text field, we can relate by item_id
        // This connects item pricing to contract obligations (quantities)
        return $this->hasManyThrough(
            ContractQuantity::class,
            Item::class,
            'id',           // Foreign key on items table
            'item_description', // Foreign key on contract_quantities table
            'item_id',      // Local key on item_pricings table
            'id'            // Local key on items table
        )->whereColumn('contract_quantities.item_description', 'items.title');
    }

    // Get all contract quantities that might use this pricing
    public function getRelatedContractQuantities()
    {
        return ContractQuantity::where('item_description', $this->item->title)
                             ->orWhere('item_description', 'LIKE', '%' . $this->item->title . '%')
                             ->get();
    }
    
    // Scope to get pricing that is currently valid (active and within date range)
    public function scopeCurrentlyValid(Builder $query): Builder
    {
        return $query->active()->validOn(now());
    }
}