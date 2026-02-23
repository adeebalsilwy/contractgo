@extends('layout')
@section('title')
    <?= get_label('journal_entries', 'Journal Entries') ?>
@endsection
@section('content')
    @php
    $menu = 'finance';
    $sub_menu = 'journal-entries';
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
                            <a href="{{ url('finance') }}"><?= get_label('finance', 'Finance') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('journal_entries', 'Journal Entries') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('journal-entries.create') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_journal_entry', 'Create Journal Entry') ?>">
                        <i class='bx bx-plus'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('journal_entries_list', 'Journal Entries List') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="dt-plugin-buttons">
                            <div class="form-group d-flex align-items-center">
                                <input type="text" id="searchInput" class="form-control mr-2" placeholder="<?= get_label('search', 'Search') ?>...">
                                <select id="statusFilter" class="form-control mr-2">
                                    <option value=""><?= get_label('all_statuses', 'All Statuses') ?></option>
                                    <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                                    <option value="posted"><?= get_label('posted', 'Posted') ?></option>
                                    <option value="reversed"><?= get_label('reversed', 'Reversed') ?></option>
                                    <option value="cancelled"><?= get_label('cancelled', 'Cancelled') ?></option>
                                </select>
                                <select id="entryTypeFilter" class="form-control mr-2">
                                    <option value=""><?= get_label('all_types', 'All Types') ?></option>
                                    <option value="journal"><?= get_label('journal', 'Journal') ?></option>
                                    <option value="invoice"><?= get_label('invoice', 'Invoice') ?></option>
                                    <option value="payment"><?= get_label('payment', 'Payment') ?></option>
                                    <option value="receipt"><?= get_label('receipt', 'Receipt') ?></option>
                                </select>
                                <button class="btn btn-primary ml-2" id="filterBtn"><?= get_label('filter', 'Filter') ?></button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th><?= get_label('entry_number', 'Entry Number') ?></th>
                                        <th><?= get_label('type', 'Type') ?></th>
                                        <th><?= get_label('date', 'Date') ?></th>
                                        <th><?= get_label('reference', 'Reference') ?></th>
                                        <th><?= get_label('description', 'Description') ?></th>
                                        <th><?= get_label('debit', 'Debit') ?></th>
                                        <th><?= get_label('credit', 'Credit') ?></th>
                                        <th><?= get_label('account_code', 'Account Code') ?></th>
                                        <th><?= get_label('account_name', 'Account Name') ?></th>
                                        <th><?= get_label('contract', 'Contract') ?></th>
                                        <th><?= get_label('invoice', 'Invoice') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('created_by', 'Created By') ?></th>
                                        <th><?= get_label('created_at', 'Created At') ?></th>
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
    <script src="{{ asset('assets/js/pages/journal-entries.js') }}"></script>
    @endpush
@endsection