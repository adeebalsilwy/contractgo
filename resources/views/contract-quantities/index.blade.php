@extends('layout')
@section('title')
    <?= get_label('contract_quantities', 'Contract Quantities') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-quantities';
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
                        <li class="breadcrumb-item active"><?= get_label('contract_quantities', 'Contract Quantities') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-quantities.create') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_contract_quantity', 'Create Contract Quantity') ?>">
                        <i class='bx bx-plus'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('contract_quantities_list', 'Contract Quantities List') ?></h5>
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
                                    <option value="modified"><?= get_label('modified', 'Modified') ?></option>
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
                                        <th><?= get_label('item_description', 'Item Description') ?></th>
                                        <th><?= get_label('requested_quantity', 'Requested Quantity') ?></th>
                                        <th><?= get_label('approved_quantity', 'Approved Quantity') ?></th>
                                        <th><?= get_label('unit', 'Unit') ?></th>
                                        <th><?= get_label('unit_price', 'Unit Price') ?></th>
                                        <th><?= get_label('total_amount', 'Total Amount') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('submitted_by', 'Submitted By') ?></th>
                                        <th><?= get_label('submitted_at', 'Submitted At') ?></th>
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
    <script src="{{ asset('assets/js/pages/contract-quantities.js') }}"></script>
    @endpush
@endsection