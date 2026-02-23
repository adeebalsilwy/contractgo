<?php

namespace App\Http\Controllers;

use App\Models\ItemPricing;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemPricingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemPricings = ItemPricing::with(['item', 'unit'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('item', function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%' . $search . '%');
                })->orWhereHas('unit', function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10);

        return view('item-pricing.index', compact('itemPricings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::all();
        $units = Unit::all();
        
        return view('item-pricing.create', compact('items', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        // Check if this item-unit combination already exists
        $existing = ItemPricing::where('item_id', $request->item_id)
            ->where('unit_id', $request->unit_id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['item_id' => 'This item-unit combination already exists.']);
        }

        ItemPricing::create($request->all());

        return redirect()->route('item-pricing.index')
            ->with('message', __('lang.successfully_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemPricing  $itemPricing
     * @return \Illuminate\Http\Response
     */
    public function show(ItemPricing $itemPricing)
    {
        $itemPricing->load(['item', 'unit']);
        
        return view('item-pricing.show', compact('itemPricing'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemPricing  $itemPricing
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemPricing $itemPricing)
    {
        $items = Item::all();
        $units = Unit::all();
        
        return view('item-pricing.edit', compact('itemPricing', 'items', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemPricing  $itemPricing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemPricing $itemPricing)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        // Check if another record has the same item-unit combination (excluding current record)
        $existing = ItemPricing::where('item_id', $request->item_id)
            ->where('unit_id', $request->unit_id)
            ->where('id', '!=', $itemPricing->id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['item_id' => 'This item-unit combination already exists.']);
        }

        $itemPricing->update($request->all());

        return redirect()->route('item-pricing.index')
            ->with('message', __('lang.successfully_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemPricing  $itemPricing
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemPricing $itemPricing)
    {
        $itemPricing->delete();

        return redirect()->route('item-pricing.index')
            ->with('message', __('lang.successfully_deleted'));
    }

    /**
     * Get price for specific item-unit combination via AJAX
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrice(Request $request)
    {
        $itemId = $request->input('item_id');
        $unitId = $request->input('unit_id');

        if (!$itemId || !$unitId) {
            return response()->json(['price' => null]);
        }

        $itemPricing = ItemPricing::where('item_id', $itemId)
            ->where('unit_id', $unitId)
            ->first();

        if ($itemPricing) {
            return response()->json(['price' => $itemPricing->price]);
        }

        return response()->json(['price' => null]);
    }
}