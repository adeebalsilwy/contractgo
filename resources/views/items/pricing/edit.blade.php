@extends('layout')
@section('title', 'Edit Item Pricing')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('home')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('items')}}">Items</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('item-pricing.index')}}">Item Pricing</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{route('item-pricing.index')}}" class="btn btn-sm btn-primary">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Item Pricing</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('item-pricing.update', $itemPricing) }}" id="itemPricingForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="item_id" class="form-label">Item <span class="asterisk">*</span></label>
                        <select class="form-control" id="item_id" name="item_id" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id', $itemPricing->item_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unit_id" class="form-label">Unit <span class="asterisk">*</span></label>
                        <select class="form-control" id="unit_id" name="unit_id" required>
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $itemPricing->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Base Price <span class="asterisk">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" 
                               value="{{ old('price', $itemPricing->price) }}" required>
                        @error('price')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" class="form-control" id="cost_price" name="cost_price" step="0.01" 
                               value="{{ old('cost_price', $itemPricing->cost_price) }}">
                        @error('cost_price')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="min_selling_price" class="form-label">Minimum Selling Price</label>
                        <input type="number" class="form-control" id="min_selling_price" name="min_selling_price" step="0.01" 
                               value="{{ old('min_selling_price', $itemPricing->min_selling_price) }}">
                        @error('min_selling_price')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="max_selling_price" class="form-label">Maximum Selling Price</label>
                        <input type="number" class="form-control" id="max_selling_price" name="max_selling_price" step="0.01" 
                               value="{{ old('max_selling_price', $itemPricing->max_selling_price) }}">
                        @error('max_selling_price')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="valid_from" class="form-label">Valid From</label>
                        <input type="date" class="form-control" id="valid_from" name="valid_from" 
                               value="{{ old('valid_from', $itemPricing->valid_from) }}">
                        @error('valid_from')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="valid_until" class="form-label">Valid Until</label>
                        <input type="date" class="form-control" id="valid_until" name="valid_until" 
                               value="{{ old('valid_until', $itemPricing->valid_until) }}">
                        @error('valid_until')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="created_by" class="form-label">Created By</label>
                    <select class="form-control" id="created_by" name="created_by">
                        <option value="">Select Creator</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('created_by', $itemPricing->created_by) == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('created_by')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $itemPricing->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                <!-- Tiered Pricing Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                            <span>Tiered Pricing</span>
                            <button type="button" class="btn btn-sm btn-primary" id="addTierRow">
                                <i class="bx bx-plus"></i> Add Tier
                            </button>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="tiersContainer">
                            @if($itemPricing->pricing_tiers && is_array($itemPricing->pricing_tiers))
                                @foreach($itemPricing->pricing_tiers as $index => $tier)
                                    <div class="row tier-row g-2 mb-2">
                                        <div class="col-md-5">
                                            <label class="form-label">Minimum Quantity</label>
                                            <input type="number" name="pricing_tiers[{{ $index }}][min_quantity]" 
                                                   class="form-control" min="0" step="0.01" 
                                                   value="{{ $tier['min_quantity'] ?? '' }}">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Price</label>
                                            <input type="number" name="pricing_tiers[{{ $index }}][price]" 
                                                   class="form-control" min="0" step="0.01" 
                                                   value="{{ $tier['price'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger w-100 remove-tier">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row tier-row g-2 mb-2" style="display: none;">
                                <div class="col-md-5">
                                    <label class="form-label">Minimum Quantity</label>
                                    <input type="number" name="pricing_tiers[][min_quantity]" class="form-control" min="0" step="0.01">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Price</label>
                                    <input type="number" name="pricing_tiers[][price]" class="form-control" min="0" step="0.01">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger w-100 remove-tier">
                                        <i class="bx bx-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Define different prices for different quantity thresholds</small>
                    </div>
                </div>

                <!-- Discounts Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                            <span>Discounts</span>
                            <button type="button" class="btn btn-sm btn-primary" id="addDiscountRow">
                                <i class="bx bx-plus"></i> Add Discount
                            </button>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="discountsContainer">
                            @if($itemPricing->discounts && is_array($itemPricing->discounts))
                                @foreach($itemPricing->discounts as $index => $discount)
                                    <div class="row discount-row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Type</label>
                                            <select name="discounts[{{ $index }}][type]" class="form-control discount-type">
                                                <option value="percentage" {{ (isset($discount['type']) && $discount['type'] === 'percentage') ? 'selected' : '' }}>Percentage</option>
                                                <option value="fixed" {{ (isset($discount['type']) && $discount['type'] === 'fixed') ? 'selected' : '' }}>Fixed Amount</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Value</label>
                                            <input type="number" name="discounts[{{ $index }}][value]" class="form-control" min="0" step="0.01" 
                                                   value="{{ $discount['value'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Min Quantity</label>
                                            <input type="number" name="discounts[{{ $index }}][min_quantity]" class="form-control" min="0" step="0.01" 
                                                   value="{{ $discount['min_quantity'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger w-100 remove-discount">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row discount-row g-2 mb-2" style="display: none;">
                                <div class="col-md-4">
                                    <label class="form-label">Type</label>
                                    <select name="discounts[][type]" class="form-control discount-type">
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Value</label>
                                    <input type="number" name="discounts[][value]" class="form-control" min="0" step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Min Quantity</label>
                                    <input type="number" name="discounts[][min_quantity]" class="form-control" min="0" step="0.01">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger w-100 remove-discount">
                                        <i class="bx bx-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Apply discounts based on quantity thresholds</small>
                    </div>
                </div>

                <!-- Taxes Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0 d-flex justify-content-between align-items-center">
                            <span>Taxes</span>
                            <button type="button" class="btn btn-sm btn-primary" id="addTaxRow">
                                <i class="bx bx-plus"></i> Add Tax
                            </button>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="taxesContainer">
                            @if($itemPricing->taxes && is_array($itemPricing->taxes))
                                @foreach($itemPricing->taxes as $index => $tax)
                                    <div class="row tax-row g-2 mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="taxes[{{ $index }}][name]" class="form-control" 
                                                   placeholder="VAT, Sales Tax, etc." value="{{ $tax['name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Rate (%)</label>
                                            <input type="number" name="taxes[{{ $index }}][rate]" class="form-control" min="0" step="0.01" 
                                                   value="{{ $tax['rate'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Min Quantity</label>
                                            <input type="number" name="taxes[{{ $index }}][min_quantity]" class="form-control" min="0" step="0.01" 
                                                   value="{{ $tax['min_quantity'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger w-100 remove-tax">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row tax-row g-2 mb-2" style="display: none;">
                                <div class="col-md-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="taxes[]" class="form-control" placeholder="VAT, Sales Tax, etc.">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rate (%)</label>
                                    <input type="number" name="taxes[][rate]" class="form-control" min="0" step="0.01">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Min Quantity</label>
                                    <input type="number" name="taxes[][min_quantity]" class="form-control" min="0" step="0.01">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger w-100 remove-tax">
                                        <i class="bx bx-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Apply taxes based on quantity thresholds</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Item Pricing</button>
                    <a href="{{route('item-pricing.index')}}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tiered pricing management
    let tierIndex = {{ count($itemPricing->pricing_tiers ?? []) }};
    document.getElementById('addTierRow').addEventListener('click', function() {
        const container = document.getElementById('tiersContainer');
        const newRow = document.querySelector('.tier-row:last-child').cloneNode(true);
        newRow.style.display = 'flex';
        newRow.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace('[]', '[' + tierIndex + ']');
            input.value = '';
        });
        container.insertBefore(newRow, document.querySelector('.tier-row:last-child'));
        tierIndex++;
    });

    // Discount management
    let discountIndex = {{ count($itemPricing->discounts ?? []) }};
    document.getElementById('addDiscountRow').addEventListener('click', function() {
        const container = document.getElementById('discountsContainer');
        const newRow = document.querySelector('.discount-row:last-child').cloneNode(true);
        newRow.style.display = 'flex';
        newRow.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace('[]', '[' + discountIndex + ']');
            if (el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
        });
        container.insertBefore(newRow, document.querySelector('.discount-row:last-child'));
        discountIndex++;
    });

    // Tax management
    let taxIndex = {{ count($itemPricing->taxes ?? []) }};
    document.getElementById('addTaxRow').addEventListener('click', function() {
        const container = document.getElementById('taxesContainer');
        const newRow = document.querySelector('.tax-row:last-child').cloneNode(true);
        newRow.style.display = 'flex';
        newRow.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace('[]', '[' + taxIndex + ']');
            if (el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
        });
        container.insertBefore(newRow, document.querySelector('.tax-row:last-child'));
        taxIndex++;
    });

    // Remove row events (using event delegation)
    document.getElementById('tiersContainer').addEventListener('click', function(e) {
        if (e.target.closest('.remove-tier') && document.querySelectorAll('#tiersContainer .tier-row:not(:last-child)').length > 1) {
            e.target.closest('.tier-row').remove();
        }
    });

    document.getElementById('discountsContainer').addEventListener('click', function(e) {
        if (e.target.closest('.remove-discount') && document.querySelectorAll('#discountsContainer .discount-row:not(:last-child)').length > 1) {
            e.target.closest('.discount-row').remove();
        }
    });

    document.getElementById('taxesContainer').addEventListener('click', function(e) {
        if (e.target.closest('.remove-tax') && document.querySelectorAll('#taxesContainer .tax-row:not(:last-child)').length > 1) {
            e.target.closest('.tax-row').remove();
        }
    });

    // Form submission handler
    document.getElementById('itemPricingForm').addEventListener('submit', function(e) {
        // Validate that at least base price is provided
        const price = parseFloat(document.getElementById('price').value);
        if (isNaN(price) || price < 0) {
            e.preventDefault();
            alert('Please enter a valid base price.');
            return;
        }
    });
});
</script>
@endsection