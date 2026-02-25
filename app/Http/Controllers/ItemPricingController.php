<?php

namespace App\Http\Controllers;

use App\Models\ItemPricing;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Services\ItemPricingIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ItemPricingController extends Controller
{
    protected $integrationService;

    public function __construct(ItemPricingIntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    public function index(Request $request)
    {
        $itemPricings = ItemPricing::with(['item', 'unit', 'creator'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('item', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })->orWhere('price', 'like', "%{$search}%");
            })
            ->when($request->item_id, function ($query, $itemId) {
                $query->where('item_id', $itemId);
            })
            ->when($request->is_active !== null, function ($query, $isActive) {
                $query->where('is_active', $isActive);
            })
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'error' => false,
                'data' => $itemPricings,
                'message' => 'Item pricings retrieved successfully.'
            ]);
        }

        $items = Item::all();
        return view('items.pricing.index', compact('itemPricings', 'items'));
    }

    public function create()
    {
        $items = Item::all();
        $units = Unit::all();
        $users = User::all();
        return view('items.pricing.create', compact('items', 'units', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'min_selling_price' => 'nullable|numeric|min:0',
            'max_selling_price' => 'nullable|numeric|min:0',
            'pricing_tiers' => 'nullable|array',
            'pricing_tiers.*.min_quantity' => 'required_with:pricing_tiers|numeric|min:0',
            'pricing_tiers.*.price' => 'required_with:pricing_tiers|numeric|min:0',
            'discounts' => 'nullable|array',
            'discounts.*.type' => 'required_with:discounts|string|in:percentage,fixed',
            'discounts.*.value' => 'required_with:discounts|numeric|min:0',
            'discounts.*.min_quantity' => 'required_with:discounts|numeric|min:0',
            'taxes' => 'nullable|array',
            'taxes.*.name' => 'required_with:taxes|string',
            'taxes.*.rate' => 'required_with:taxes|numeric|min:0',
            'taxes.*.min_quantity' => 'required_with:taxes|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'created_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();
        
        // Process arrays for JSON storage
        $validatedData['pricing_tiers'] = $validatedData['pricing_tiers'] ?? [];
        $validatedData['discounts'] = $validatedData['discounts'] ?? [];
        $validatedData['taxes'] = $validatedData['taxes'] ?? [];

        $itemPricing = ItemPricing::create($validatedData);

        // Sync with basic pricing table
        $this->integrationService->syncPricingTables($itemPricing->item_id);

        if ($request->ajax()) {
            return response()->json([
                'error' => false,
                'data' => ['item_pricing' => $itemPricing],
                'message' => 'Item pricing created successfully.'
            ]);
        }
        return redirect()->route('item-pricing.index')->with('message', 'Item pricing created successfully.');
    }

    public function show(ItemPricing $itemPricing)
    {
        $itemPricing->load(['item', 'unit', 'creator']);
        return view('items.pricing.show', compact('itemPricing'));
    }

    public function edit(ItemPricing $itemPricing)
    {
        $itemPricing->load(['item', 'unit', 'creator']);
        $items = Item::all();
        $units = Unit::all();
        $users = User::all();
        return view('items.pricing.edit', compact('itemPricing', 'items', 'units', 'users'));
    }

    public function update(Request $request, ItemPricing $itemPricing)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'sometimes|required|exists:items,id',
            'unit_id' => 'sometimes|required|exists:units,id',
            'price' => 'sometimes|required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'min_selling_price' => 'nullable|numeric|min:0',
            'max_selling_price' => 'nullable|numeric|min:0',
            'pricing_tiers' => 'nullable|array',
            'pricing_tiers.*.min_quantity' => 'required_with:pricing_tiers|numeric|min:0',
            'pricing_tiers.*.price' => 'required_with:pricing_tiers|numeric|min:0',
            'discounts' => 'nullable|array',
            'discounts.*.type' => 'required_with:discounts|string|in:percentage,fixed',
            'discounts.*.value' => 'required_with:discounts|numeric|min:0',
            'discounts.*.min_quantity' => 'required_with:discounts|numeric|min:0',
            'taxes' => 'nullable|array',
            'taxes.*.name' => 'required_with:taxes|string',
            'taxes.*.rate' => 'required_with:taxes|numeric|min:0',
            'taxes.*.min_quantity' => 'required_with:taxes|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'created_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();
        
        // Process arrays for JSON storage
        $validatedData['pricing_tiers'] = $validatedData['pricing_tiers'] ?? $itemPricing->pricing_tiers;
        $validatedData['discounts'] = $validatedData['discounts'] ?? $itemPricing->discounts;
        $validatedData['taxes'] = $validatedData['taxes'] ?? $itemPricing->taxes;

        $itemPricing->update($validatedData);

        // Sync with basic pricing table
        $this->integrationService->syncPricingTables($itemPricing->item_id);

        if ($request->ajax()) {
            return response()->json([
                'error' => false,
                'data' => ['item_pricing' => $itemPricing],
                'message' => 'Item pricing updated successfully.'
            ]);
        }
        return redirect()->route('item-pricing.index')->with('message', 'Item pricing updated successfully.');
    }

    public function destroy(ItemPricing $itemPricing, Request $request)
    {
        $itemId = $itemPricing->item_id;
        $itemPricing->delete();

        if ($request->ajax()) {
            return response()->json([
                'error' => false,
                'message' => 'Item pricing deleted successfully.'
            ]);
        }
        return redirect()->route('item-pricing.index')->with('message', 'Item pricing deleted successfully.');
    }

    // Toggle the active status of an item pricing
    public function toggleStatus(ItemPricing $itemPricing, Request $request)
    {
        $itemPricing->update(['is_active' => !$itemPricing->is_active]);

        return response()->json([
            'error' => false,
            'data' => ['is_active' => $itemPricing->is_active],
            'message' => 'Status updated successfully.'
        ]);
    }

    // Get pricing for a specific item and quantity
    public function getPricingForItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        
        $result = $this->integrationService->getItemPricing($validated['item_id'], $validated['quantity']);
        
        if (!$result) {
            return response()->json([
                'error' => true,
                'message' => 'Item not found.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'data' => [
                'item_id' => $validated['item_id'],
                'quantity' => $validated['quantity'],
                'pricing_type' => $result['pricing_type'],
                'unit_price' => $result['calculated_price'],
                'total_price' => $result['calculated_price'] * $validated['quantity'],
                'pricing_available' => true
            ],
            'message' => 'Pricing calculated successfully.'
        ]);
    }
    
    // Get all pricings related to contract obligations for a specific item
    public function getPricingWithContractObligations(Request $request, $itemPricing)
    {
        $item = Item::find($itemPricing->item_id);
        
        if (!$item) {
            return response()->json([
                'error' => true,
                'message' => 'Item not found.'
            ], 404);
        }

        $result = $this->integrationService->getPricingWithContractObligations($item->id);

        if (!$result) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve pricing with contract obligations.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'data' => $result,
            'message' => 'Pricing and contract obligations retrieved successfully.'
        ]);
    }
    
    // Get pricing with contract obligations for an item (alternative method)
    public function getPricingWithContractObligationsForItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->integrationService->getPricingWithContractObligations($request->item_id);

        if (!$result) {
            return response()->json([
                'error' => true,
                'message' => 'Item not found.'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'data' => $result,
            'message' => 'Pricing and contract obligations retrieved successfully.'
        ]);
    }
}