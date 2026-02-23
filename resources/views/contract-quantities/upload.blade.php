@extends('layout')
@section('title', 'Upload Contract Quantities')
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
                            <h4>Upload Contract Quantities</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contract-quantities.index') }}">Contract Quantities</a></li>
                                <li class="breadcrumb-item active">Upload</li>
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
                        <h5>Upload Quantities for Contract: {{ $contract->title }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="bulkUploadForm">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="quantitiesTable">
                                    <thead>
                                        <tr>
                                            <th>Item Description</th>
                                            <th>Requested Quantity</th>
                                            <th>Unit</th>
                                            <th>Unit Price</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="quantities[0][item_description]" class="form-control" required></td>
                                            <td><input type="number" name="quantities[0][requested_quantity]" class="form-control" min="0" step="0.01" required></td>
                                            <td><input type="text" name="quantities[0][unit]" class="form-control" required></td>
                                            <td><input type="number" name="quantities[0][unit_price]" class="form-control" min="0" step="0.01"></td>
                                            <td><textarea name="quantities[0][notes]" class="form-control"></textarea></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <button type="button" class="btn btn-success" id="addRow">Add Row</button>
                                                <button type="submit" class="btn btn-primary">Upload Quantities</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
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