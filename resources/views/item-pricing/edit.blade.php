@extends('layouts.app')

@section('page_title', get_label('edit_item_pricing', 'Edit Item Pricing'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ get_label('edit_item_pricing', 'Edit Item Pricing') }}</h4>
                        <a href="{{ route('item-pricing.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ get_label('back', 'Back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('item-pricing.update', $itemPricing->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="item_id">{{ get_label('item', 'Item') }} <span class="text-danger">*</span></label>
                                        <select name="item_id" id="item_id" class="form-control select2" required>
                                            <option value="">{{ get_label('select_item', 'Select Item') }}</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $itemPricing->item_id ? 'selected' : '' }}>
                                                    {{ $item->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('item_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="unit_id">{{ get_label('unit', 'Unit') }} <span class="text-danger">*</span></label>
                                        <select name="unit_id" id="unit_id" class="form-control select2" required>
                                            <option value="">{{ get_label('select_unit', 'Select Unit') }}</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->id == $itemPricing->unit_id ? 'selected' : '' }}>
                                                    {{ $unit->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="price">{{ get_label('price', 'Price') }} <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="{{ get_label('enter_price', 'Enter Price') }}" value="{{ old('price', $itemPricing->price) }}" required>
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="description">{{ get_label('description', 'Description') }}</label>
                                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="{{ get_label('enter_description', 'Enter Description') }}">{{ old('description', $itemPricing->description) }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">{{ get_label('update', 'Update') }}</button>
                                <a href="{{ route('item-pricing.index') }}" class="btn btn-secondary">{{ get_label('cancel', 'Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection