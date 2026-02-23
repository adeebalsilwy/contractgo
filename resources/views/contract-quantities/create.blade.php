@extends('layout')
@section('title', 'Create Contract Quantity')
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-quantities';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Create Contract Quantity</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contract-quantities.index') }}">Contract Quantities</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('contract-quantities.index') }}" class="btn btn-secondary float-right">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Add New Contract Quantity</h5>
                    </div>
                    <div class="card-body">
                        <form id="contractQuantityForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Contract<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $contract->title }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Item Description<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="item_description" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Requested Quantity<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="number" name="requested_quantity" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Unit<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="unit" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Unit Price</label>
                                <div class="col-sm-10">
                                    <input type="number" name="unit_price" class="form-control" min="0" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Notes</label>
                                <div class="col-sm-10">
                                    <textarea name="notes" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Supporting Documents</label>
                                <div class="col-sm-10">
                                    <input type="file" name="supporting_documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Multiple files allowed (PDF, JPG, PNG, max 10MB each)</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Submit Quantity</button>
                                    <a href="{{ route('contract-quantities.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-quantities.js') }}"></script>
    @endpush
@endsection