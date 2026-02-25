@extends('layout')
@section('title', 'Item Pricing Details')

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
                    <li class="breadcrumb-item active">Details</li>
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
            <h5 class="card-title mb-0">Item Pricing Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>Item:</th>
                            <td>{{ $itemPricing->item->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td>{{ $itemPricing->unit->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Base Price:</th>
                            <td>{{ format_currency($itemPricing->price) }}</td>
                        </tr>
                        <tr>
                            <th>Cost Price:</th>
                            <td>{{ format_currency($itemPricing->cost_price ?? 0) }}</td>
                        </tr>
                        <tr>
                            <th>Min Selling Price:</th>
                            <td>{{ format_currency($itemPricing->min_selling_price ?? 0) }}</td>
                        </tr>
                        <tr>
                            <th>Max Selling Price:</th>
                            <td>{{ format_currency($itemPricing->max_selling_price ?? 0) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $itemPricing->is_active ? 'success' : 'danger' }}">
                                    {{ $itemPricing->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Valid From:</th>
                            <td>{{ $itemPricing->valid_from ? format_date($itemPricing->valid_from) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Valid Until:</th>
                            <td>{{ $itemPricing->valid_until ? format_date($itemPricing->valid_until) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created By:</th>
                            <td>{{ $itemPricing->creator->first_name ?? 'N/A' }} {{ $itemPricing->creator->last_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ format_date($itemPricing->created_at, true) }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <!-- Tiered Pricing -->
                    @if(!empty($itemPricing->pricing_tiers))
                    <div class="mb-4">
                        <h6>Tiered Pricing</h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Min Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemPricing->pricing_tiers as $tier)
                                <tr>
                                    <td>{{ $tier['min_quantity'] }}</td>
                                    <td>{{ format_currency($tier['price']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    
                    <!-- Discounts -->
                    @if(!empty($itemPricing->discounts))
                    <div class="mb-4">
                        <h6>Discounts</h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Min Quantity</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemPricing->discounts as $discount)
                                <tr>
                                    <td>{{ $discount['min_quantity'] }}</td>
                                    <td>{{ ucfirst($discount['type']) }}</td>
                                    <td>{{ $discount['type'] === 'percentage' ? $discount['value'].'%' : format_currency($discount['value']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    
                    <!-- Taxes -->
                    @if(!empty($itemPricing->taxes))
                    <div class="mb-4">
                        <h6>Taxes</h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Min Quantity</th>
                                    <th>Name</th>
                                    <th>Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemPricing->taxes as $tax)
                                <tr>
                                    <td>{{ $tax['min_quantity'] }}</td>
                                    <td>{{ $tax['name'] }}</td>
                                    <td>{{ $tax['rate'] }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Contract Obligations Section -->
            <div class="mt-5">
                <h5>Connected Contract Obligations</h5>
                <p class="text-muted">Quantities related to contracts for this item:</p>
                
                @php
                    $contractQuantities = $itemPricing->getRelatedContractQuantities();
                    $totalContracted = 0;
                    foreach($contractQuantities as $cq) {
                        $totalContracted += $cq->approved_quantity ?? $cq->requested_quantity;
                    }
                @endphp
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $contractQuantities->count() }}</h5>
                                <p class="card-text">Total Contract Records</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $totalContracted }}</h5>
                                <p class="card-text">Total Quantity Committed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ format_currency($itemPricing->calculateTotalPrice($totalContracted)) }}</h5>
                                <p class="card-text">Estimated Total Value</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($contractQuantities->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Contract ID</th>
                            <th>Description</th>
                            <th>Requested Qty</th>
                            <th>Approved Qty</th>
                            <th>Unit Price</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contractQuantities as $cq)
                        <tr>
                            <td>{{ $cq->contract->id ?? 'N/A' }}</td>
                            <td>{{ $cq->item_description }}</td>
                            <td>{{ $cq->requested_quantity }}</td>
                            <td>{{ $cq->approved_quantity ?? 'Pending' }}</td>
                            <td>{{ format_currency($cq->unit_price ?? 0) }}</td>
                            <td>{{ format_currency($cq->total_amount ?? 0) }}</td>
                            <td>
                                <span class="badge bg-{{ $cq->status === 'approved' ? 'success' : ($cq->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($cq->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-info">
                    <i class="bx bx-info-circle"></i> No contract obligations found for this item.
                </div>
                @endif
            </div>
        </div>
        
        <div class="card-footer">
            <a href="{{route('item-pricing.edit', $itemPricing)}}" class="btn btn-primary">
                <i class="bx bx-edit"></i> Edit Pricing
            </a>
            <a href="{{route('item-pricing.index')}}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection