@extends('layout')
@section('title', 'Contract Amendments')
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
                            <h4>Contract Amendments</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item">Contracts</li>
                                <li class="breadcrumb-item active">Contract Amendments</li>
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
                        <h5>Contract Amendments List</h5>
                    </div>
                    <div class="card-body">
                        <div class="dt-plugin-buttons">
                            <div class="form-group d-flex align-items-center">
                                <input type="text" id="searchInput" class="form-control mr-2" placeholder="Search...">
                                <select id="statusFilter" class="form-control mr-2">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <select id="typeFilter" class="form-control mr-2">
                                    <option value="">All Types</option>
                                    <option value="price">Price</option>
                                    <option value="quantity">Quantity</option>
                                    <option value="specification">Specification</option>
                                </select>
                                <button class="btn btn-primary ml-2" id="filterBtn">Filter</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Contract</th>
                                        <th>Amendment Type</th>
                                        <th>Request Reason</th>
                                        <th>Original Value</th>
                                        <th>New Value</th>
                                        <th>Requested By</th>
                                        <th>Status</th>
                                        <th>Requested At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated by AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-amendments.js') }}"></script>
    @endpush
@endsection