@extends('layout')
@section('title', 'Pending Quantity Approvals')
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-approvals';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Pending Quantity Approvals</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contract-approvals.index') }}">Contract Approvals</a></li>
                                <li class="breadcrumb-item active">Pending Quantity Approvals</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <!-- Action buttons will go here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Quantities Awaiting Your Approval</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Contract</th>
                                        <th>Item Description</th>
                                        <th>Requested Quantity</th>
                                        <th>Unit</th>
                                        <th>Unit Price</th>
                                        <th>Submitted By</th>
                                        <th>Submitted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractQuantities as $quantity)
                                    <tr>
                                        <td>{{ $quantity->id }}</td>
                                        <td>{{ $quantity->contract->title }}</td>
                                        <td>{{ $quantity->item_description }}</td>
                                        <td>{{ $quantity->requested_quantity }}</td>
                                        <td>{{ $quantity->unit }}</td>
                                        <td>{{ $quantity->unit_price ? format_currency($quantity->unit_price) : 'N/A' }}</td>
                                        <td>{{ $quantity->user->first_name }} {{ $quantity->user->last_name }}</td>
                                        <td>{{ format_date($quantity->submitted_at, true) }}</td>
                                        <td>
                                            <a href="{{ route('contract-quantities.show', $quantity->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('contract-quantities.edit', $quantity->id) }}" class="btn btn-warning btn-sm">Modify</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No pending quantity approvals found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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