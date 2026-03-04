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
                        <i class='bx bx-plus'></i> <?= get_label('create', 'Create') ?>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('contract_quantities_list', 'Contract Quantities List') ?></h5>
                        <div class="dt-plugin-buttons d-flex align-items-center gap-2">
                            <input type="text" id="searchInput" class="form-control" style="width: 200px;" placeholder="<?= get_label('search', 'Search') ?>...">
                            <select id="statusFilter" class="form-control" style="width: 150px;">
                                <option value=""><?= get_label('all_statuses', 'All Statuses') ?></option>
                                <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                                <option value="approved"><?= get_label('approved', 'Approved') ?></option>
                                <option value="rejected"><?= get_label('rejected', 'Rejected') ?></option>
                                <option value="modified"><?= get_label('modified', 'Modified') ?></option>
                            </select>
                            <button class="btn btn-primary" id="filterBtn"><?= get_label('filter', 'Filter') ?></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="contract_quantities_table" data-toggle="table" data-url="{{ route('contract-quantities.list') }}" data-query-params="queryParamsContractQuantities" data-pagination="true" data-side-pagination="server" data-page-list="[10, 25, 50, 100]" data-search="false">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="contract" data-sortable="true"><?= get_label('contract', 'Contract') ?></th>
                                        <th data-field="item_description" data-sortable="true"><?= get_label('item_description', 'Item Description') ?></th>
                                        <th data-field="requested_quantity" data-sortable="true"><?= get_label('requested_quantity', 'Requested Quantity') ?></th>
                                        <th data-field="approved_quantity" data-sortable="true"><?= get_label('approved_quantity', 'Approved Quantity') ?></th>
                                        <th data-field="unit" data-sortable="true"><?= get_label('unit', 'Unit') ?></th>
                                        <th data-field="unit_price" data-sortable="true"><?= get_label('unit_price', 'Unit Price') ?></th>
                                        <th data-field="total_amount" data-sortable="true"><?= get_label('total_amount', 'Total Amount') ?></th>
                                        <th data-field="status" data-sortable="true"><?= get_label('status', 'Status') ?></th>
                                        <th data-field="submitted_by" data-sortable="true"><?= get_label('submitted_by', 'Submitted By') ?></th>
                                        <th data-field="submitted_at" data-sortable="true"><?= get_label('submitted_at', 'Submitted At') ?></th>
                                        <th data-field="actions"><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-quantities.js') }}"></script>
    <script>
        function queryParamsContractQuantities(params) {
            params.status = $('#statusFilter').val();
            params.search = $('#searchInput').val();
            return params;
        }

        $(document).ready(function() {
            $('#filterBtn').on('click', function() {
                $('#contract_quantities_table').bootstrapTable('refresh', {
                    query: queryParamsContractQuantities({})
                });
            });

            $('#searchInput').on('keyup', function() {
                if ($(this).val().length > 2 || $(this).val() === '') {
                    $('#contract_quantities_table').bootstrapTable('refresh', {
                        query: queryParamsContractQuantities({})
                    });
                }
            });

            $('#statusFilter').on('change', function() {
                $('#contract_quantities_table').bootstrapTable('refresh', {
                    query: queryParamsContractQuantities({})
                });
            });
        });
    </script>
    @endpush
@endsection