@extends('layout')
@section('title', 'Journal Entries')
@section('content')
    @php
    $menu = 'finance';
    $sub_menu = 'journal-entries';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Journal Entries</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item">Finance</li>
                                <li class="breadcrumb-item active">Journal Entries</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('journal-entries.create') }}" class="btn btn-primary float-right">Create Entry</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Journal Entries List</h5>
                    </div>
                    <div class="card-body">
                        <div class="dt-plugin-buttons">
                            <div class="form-group d-flex align-items-center">
                                <input type="text" id="searchInput" class="form-control mr-2" placeholder="Search...">
                                <select id="statusFilter" class="form-control mr-2">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="posted">Posted</option>
                                    <option value="reversed">Reversed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <select id="entryTypeFilter" class="form-control mr-2">
                                    <option value="">All Types</option>
                                    <option value="journal">Journal</option>
                                    <option value="invoice">Invoice</option>
                                    <option value="payment">Payment</option>
                                    <option value="receipt">Receipt</option>
                                </select>
                                <button class="btn btn-primary ml-2" id="filterBtn">Filter</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Entry Number</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Contract</th>
                                        <th>Invoice</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
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
    <script src="{{ asset('assets/js/pages/journal-entries.js') }}"></script>
    @endpush
@endsection