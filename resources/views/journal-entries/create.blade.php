@extends('layout')
@section('title', 'Create Journal Entry')
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
                            <h4>Create Journal Entry</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('journal-entries.index') }}">Journal Entries</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary float-right">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Add New Journal Entry</h5>
                    </div>
                    <div class="card-body">
                        <form id="journalEntryForm">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Entry Number<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="entry_number" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Entry Type<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="entry_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="journal">Journal</option>
                                        <option value="invoice">Invoice</option>
                                        <option value="payment">Payment</option>
                                        <option value="receipt">Receipt</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Entry Date<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="date" name="entry_date" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Reference Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="reference_number" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Contract</label>
                                <div class="col-sm-10">
                                    <select name="contract_id" class="form-control">
                                        <option value="">Select Contract (Optional)</option>
                                        @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}">{{ $contract->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Invoice</label>
                                <div class="col-sm-10">
                                    <select name="invoice_id" class="form-control">
                                        <option value="">Select Invoice (Optional)</option>
                                        @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}">{{ $invoice->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Description<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="description" class="form-control" required></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Debit Amount<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="number" name="debit_amount" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Credit Amount<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="number" name="credit_amount" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Account Code<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="account_code" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Account Name<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="account_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Status<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-control" required>
                                        <option value="pending">Pending</option>
                                        <option value="posted">Posted</option>
                                        <option value="reversed">Reversed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Create Journal Entry</button>
                                    <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">Cancel</a>
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