<?php

namespace App\Services;

use App\Models\ItemPricing;
use App\Models\Item;
use App\Models\ContractQuantity;
use Illuminate\Support\Facades\DB;

class ItemPricingIntegrationService
{
    /**
     * Get the most appropriate pricing for an item considering both tables
     */
    public function getItemPricing($itemId, $quantity = 1)
    {
        // First try to get from the enhanced item_pricings table
        $enhancedPricing = ItemPricing::where('item_id', $itemId)
            ->currentlyValid()
            ->first();
            
        if ($enhancedPricing) {
            return [
                'pricing_type' => 'enhanced',
                'pricing' => $enhancedPricing,
                'calculated_price' => $enhancedPricing->calculateFinalPrice($quantity, $enhancedPricing->getPriceForQuantity($quantity))
            ];
        }
        
        // Fallback to the basic item_pricing table
        $basicPricing = DB::table('item_pricing')
            ->where('item_id', $itemId)
            ->first();
            
        if ($basicPricing) {
            return [
                'pricing_type' => 'basic',
                'pricing' => $basicPricing,
                'calculated_price' => $basicPricing->price
            ];
        }
        
        // Fallback to item base price
        $item = Item::find($itemId);
        if ($item) {
            return [
                'pricing_type' => 'item_base',
                'pricing' => $item,
                'calculated_price' => $item->price
            ];
        }
        
        return null;
    }
    
    /**
     * Get pricing with contract obligations information
     */
    public function getPricingWithContractObligations($itemId)
    {
        $item = Item::with(['itemPricings', 'contractQuantities'])->find($itemId);
        
        if (!$item) {
            return null;
        }

        // Get contract quantities (obligations) for this item
        $contractQuantities = $item->getRelatedContractQuantities();
        $totalContractedQuantity = $item->getTotalContractQuantities();
        
        // Get current active pricing
        $currentPricing = $item->currentPricing;

        return [
            'item' => $item,
            'current_pricing' => $currentPricing,
            'contract_obligations' => $contractQuantities,
            'total_contracted_quantity' => $totalContractedQuantity,
            'pricing_for_obligations' => $currentPricing ? [
                'unit_price' => $currentPricing->price,
                'total_value' => $currentPricing->calculateTotalPrice($totalContractedQuantity)
            ] : null
        ];
    }
    
    /**
     * Sync pricing between the two tables if needed
     */
    public function syncPricingTables($itemId)
    {
        // Get basic pricing
        $basicPricing = DB::table('item_pricing')->where('item_id', $itemId)->first();
        
        // Get enhanced pricing
        $enhancedPricing = ItemPricing::where('item_id', $itemId)->first();
        
        // If basic exists but enhanced doesn't, create enhanced based on basic
        if ($basicPricing && !$enhancedPricing) {
            ItemPricing::create([
                'item_id' => $basicPricing->item_id,
                'unit_id' => $basicPricing->unit_id,
                'price' => $basicPricing->price,
                'cost_price' => $basicPricing->price * 0.8, // Assume 80% of selling price as cost
                'description' => $basicPricing->description ?? '',
                'is_active' => true,
                'valid_from' => now(),
                'valid_until' => null,
            ]);
        }
        // If enhanced exists but basic doesn't, create basic based on enhanced
        elseif ($enhancedPricing && !$basicPricing) {
            DB::table('item_pricing')->insert([
                'item_id' => $enhancedPricing->item_id,
                'unit_id' => $enhancedPricing->unit_id,
                'price' => $enhancedPricing->price,
                'description' => 'Auto-synced from enhanced pricing',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    /**
     * Calculate total contract obligation value for an item
     */
    public function calculateContractObligationValue($itemId)
    {
        $result = $this->getPricingWithContractObligations($itemId);
        
        if (!$result) {
            return 0;
        }
        
        $totalValue = 0;
        $unitPrice = $result['current_pricing'] ? $result['current_pricing']->price : $result['item']->price;
        
        foreach ($result['contract_obligations'] as $obligation) {
            $quantity = $obligation->approved_quantity ?? $obligation->requested_quantity;
            $totalValue += $unitPrice * $quantity;
        }
        
        return $totalValue;
    }
}