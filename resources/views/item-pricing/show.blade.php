@extends('layouts.app')

@section('page_title', get_label('item_pricing_details', 'Item Pricing Details'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ get_label('item_pricing_details', 'Item Pricing Details') }}</h4>
                        <div>
                            @if(permission('item_pricing_write'))
                                <a href="{{ route('item-pricing.edit', $itemPricing->id) }}" class="btn btn-primary">
                                    <i class="bx bx-edit-alt me-1"></i> {{ get_label('edit', 'Edit') }}
                                </a>
                            @endif
                            <a href="{{ route('item-pricing.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ get_label('back', 'Back') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>{{ get_label('item', 'Item') }}:</strong>
                                    <p>{{ $itemPricing->item->title ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>{{ get_label('unit', 'Unit') }}:</strong>
                                    <p>{{ $itemPricing->unit->title ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>{{ get_label('price', 'Price') }}:</strong>
                                    <p>{{ number_format($itemPricing->price, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <strong>{{ get_label('description', 'Description') }}:</strong>
                                    <p>{{ $itemPricing->description ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>{{ get_label('created_at', 'Created At') }}:</strong>
                                    <p>{{ $itemPricing->created_at->format('d M Y h:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>{{ get_label('updated_at', 'Updated At') }}:</strong>
                                    <p>{{ $itemPricing->updated_at->format('d M Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection