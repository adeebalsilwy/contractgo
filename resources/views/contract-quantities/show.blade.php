@extends('layout')
@section('title', 'Contract Quantity Details')
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
                            <h4>Contract Quantity Details</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contract-quantities.index') }}">Contract Quantities</a></li>
                                <li class="breadcrumb-item active">Details</li>
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
                        <h5>Contract Quantity Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $contractQuantity->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contract:</strong></td>
                                    <td>{{ $contractQuantity->contract->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Item Description:</strong></td>
                                    <td>{{ $contractQuantity->item_description }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested Quantity:</strong></td>
                                    <td>{{ $contractQuantity->requested_quantity }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Approved Quantity:</strong></td>
                                    <td>{{ $contractQuantity->approved_quantity ?? 'Not Yet Approved' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unit:</strong></td>
                                    <td>{{ $contractQuantity->unit }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unit Price:</strong></td>
                                    <td>{{ $contractQuantity->unit_price ? format_currency($contractQuantity->unit_price) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>{{ $contractQuantity->total_amount ? format_currency($contractQuantity->total_amount) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $contractQuantity->status === 'approved' ? 'success' : ($contractQuantity->status === 'rejected' ? 'danger' : ($contractQuantity->status === 'modified' ? 'warning' : 'primary')) }}">
                                            {{ ucfirst($contractQuantity->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Submitted By:</strong></td>
                                    <td>{{ $contractQuantity->user->first_name ?? 'N/A' }} {{ $contractQuantity->user->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Submitted At:</strong></td>
                                    <td>{{ format_date($contractQuantity->submitted_at, true) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Notes:</strong></td>
                                    <td>{{ $contractQuantity->notes ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('contract-quantities.index') }}" class="btn btn-secondary">Back to List</a>
                            <a href="{{ route('contract-quantities.edit', $contractQuantity->id) }}" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-quantities.js') }}"></script>
    @endpush
@endsection