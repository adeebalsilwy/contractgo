@extends('layout')
@section('title', 'Request Contract Amendment')
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-amendments';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Request Contract Amendment</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Contracts</a></li>
                                <li class="breadcrumb-item active">Request Amendment</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-secondary float-right">Back to Contract</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Request Amendment for Contract: {{ $contract->title }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="contractAmendmentForm">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Contract Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $contract->title }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Amendment Type<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="amendment_type" class="form-control" required>
                                        <option value="">Select Amendment Type</option>
                                        <option value="price">Price Change</option>
                                        <option value="quantity">Quantity Change</option>
                                        <option value="specification">Specification Change</option>
                                        <option value="duration">Duration Change</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Request Reason<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="request_reason" class="form-control" required></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Details</label>
                                <div class="col-sm-10">
                                    <textarea name="details" class="form-control"></textarea>
                                </div>
                            </div>

                            <!-- Dynamic fields based on amendment type -->
                            <div class="form-group row amendment-field" id="original-price-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">Original Price</label>
                                <div class="col-sm-10">
                                    <input type="number" name="original_price" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-price-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">New Price</label>
                                <div class="col-sm-10">
                                    <input type="number" name="new_price" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="original-quantity-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">Original Quantity</label>
                                <div class="col-sm-10">
                                    <input type="number" name="original_quantity" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-quantity-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">New Quantity</label>
                                <div class="col-sm-10">
                                    <input type="number" name="new_quantity" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="original-unit-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">Original Unit</label>
                                <div class="col-sm-10">
                                    <input type="text" name="original_unit" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-unit-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">New Unit</label>
                                <div class="col-sm-10">
                                    <input type="text" name="new_unit" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="original-description-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">Original Description</label>
                                <div class="col-sm-10">
                                    <textarea name="original_description" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-description-field" style="display:none;">
                                <label class="col-sm-2 col-form-label">New Description</label>
                                <div class="col-sm-10">
                                    <textarea name="new_description" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Submit Amendment Request</button>
                                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-amendments.js') }}"></script>
    @endpush
@endsection