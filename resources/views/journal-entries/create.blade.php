@extends('layout')
@section('title')
    <?= get_label('create_journal_entry', 'Create Journal Entry') ?>
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
                            <a href="{{ route('journal-entries.index') }}"><?= get_label('journal_entries', 'Journal Entries') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('create', 'Create') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('journal-entries.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('add_new_journal_entry', 'Add New Journal Entry') ?></h5>
                    </div>
                    <div class="card-body">
                        <form id="journalEntryForm">
                            @csrf

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('entry_number', 'Entry Number') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="entry_number" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('entry_type', 'Entry Type') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="entry_type" class="form-control" required>
                                        <option value=""><?= get_label('select_type', 'Select Type') ?></option>
                                        <option value="journal"><?= get_label('journal', 'Journal') ?></option>
                                        <option value="invoice"><?= get_label('invoice', 'Invoice') ?></option>
                                        <option value="payment"><?= get_label('payment', 'Payment') ?></option>
                                        <option value="receipt"><?= get_label('receipt', 'Receipt') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('entry_date', 'Entry Date') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="date" name="entry_date" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('reference_number', 'Reference Number') ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="reference_number" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('contract', 'Contract') ?></label>
                                <div class="col-sm-10">
                                    <select name="contract_id" class="form-control">
                                        <option value=""><?= get_label('select_contract_optional', 'Select Contract (Optional)') ?></option>
                                        @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}">{{ $contract->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('invoice', 'Invoice') ?></label>
                                <div class="col-sm-10">
                                    <select name="invoice_id" class="form-control">
                                        <option value=""><?= get_label('select_invoice_optional', 'Select Invoice (Optional)') ?></option>
                                        @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}">{{ $invoice->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('description', 'Description') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="description" class="form-control" required></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('debit_amount', 'Debit Amount') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="number" name="debit_amount" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('credit_amount', 'Credit Amount') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="number" name="credit_amount" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('account_code', 'Account Code') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="account_code" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('account_name', 'Account Name') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="account_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('status', 'Status') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-control" required>
                                        <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                                        <option value="posted"><?= get_label('posted', 'Posted') ?></option>
                                        <option value="reversed"><?= get_label('reversed', 'Reversed') ?></option>
                                        <option value="cancelled"><?= get_label('cancelled', 'Cancelled') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary"><?= get_label('create_journal_entry', 'Create Journal Entry') ?></button>
                                    <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary"><?= get_label('cancel', 'Cancel') ?></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/journal-entries.js') }}"></script>
    @endpush
@endsection