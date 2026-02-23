@extends('layout')
@section('title')
    <?= get_label('contract_amendments', 'Contract Amendments') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-amendments';
    @endphp

    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('home') }}"><?= get_label('home', 'Home') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('contracts') }}"><?= get_label('contracts', 'Contracts') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('contract_amendments', 'Contract Amendments') ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('contract_amendments_list', 'Contract Amendments List') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="dt-plugin-buttons">
                            <div class="form-group d-flex align-items-center">
                                <input type="text" id="searchInput" class="form-control mr-2" placeholder="<?= get_label('search', 'Search') ?>...">
                                <select id="statusFilter" class="form-control mr-2">
                                    <option value=""><?= get_label('all_statuses', 'All Statuses') ?></option>
                                    <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                                    <option value="approved"><?= get_label('approved', 'Approved') ?></option>
                                    <option value="rejected"><?= get_label('rejected', 'Rejected') ?></option>
                                </select>
                                <select id="typeFilter" class="form-control mr-2">
                                    <option value=""><?= get_label('all_types', 'All Types') ?></option>
                                    <option value="price"><?= get_label('price', 'Price') ?></option>
                                    <option value="quantity"><?= get_label('quantity', 'Quantity') ?></option>
                                    <option value="specification"><?= get_label('specification', 'Specification') ?></option>
                                </select>
                                <button class="btn btn-primary ml-2" id="filterBtn"><?= get_label('filter', 'Filter') ?></button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th><?= get_label('contract', 'Contract') ?></th>
                                        <th><?= get_label('amendment_type', 'Amendment Type') ?></th>
                                        <th><?= get_label('request_reason', 'Request Reason') ?></th>
                                        <th><?= get_label('original_value', 'Original Value') ?></th>
                                        <th><?= get_label('new_value', 'New Value') ?></th>
                                        <th><?= get_label('requested_by', 'Requested By') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('requested_at', 'Requested At') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
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