@extends('layout')
@section('title', 'Journal Entry Details')
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
                            <h4>Journal Entry Details</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('journal-entries.index') }}">Journal Entries</a></li>
                                <li class="breadcrumb-item active">Details</li>
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
                        <h5>Journal Entry Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $journalEntry->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Entry Number:</strong></td>
                                    <td>{{ $journalEntry->entry_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Entry Type:</strong></td>
                                    <td>{{ ucfirst($journalEntry->entry_type) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Entry Date:</strong></td>
                                    <td>{{ format_date($journalEntry->entry_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Reference Number:</strong></td>
                                    <td>{{ $journalEntry->reference_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contract:</strong></td>
                                    <td>{{ $journalEntry->contract->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice:</strong></td>
                                    <td>{{ $journalEntry->invoice->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $journalEntry->description }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Debit Amount:</strong></td>
                                    <td>{{ format_currency($journalEntry->debit_amount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Credit Amount:</strong></td>
                                    <td>{{ format_currency($journalEntry->credit_amount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Code:</strong></td>
                                    <td>{{ $journalEntry->account_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Name:</strong></td>
                                    <td>{{ $journalEntry->account_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $journalEntry->status === 'posted' ? 'success' : ($journalEntry->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($journalEntry->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created By:</strong></td>
                                    <td>{{ $journalEntry->createdBy->first_name ?? 'N/A' }} {{ $journalEntry->createdBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ format_date($journalEntry->created_at, true) }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">Back to List</a>
                            <a href="{{ route('journal-entries.edit', $journalEntry->id) }}" class="btn btn-primary">Edit</a>
                            
                            @if($journalEntry->status !== 'posted')
                            <button class="btn btn-success" id="postToAccountingBtn" data-id="{{ $journalEntry->id }}">
                                Post to Accounting
                            </button>
                            @endif
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