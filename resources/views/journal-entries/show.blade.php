@extends('layout')
@section('title')
    <?= get_label('journal_entry_details', 'Journal Entry Details') ?>
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
                        <li class="breadcrumb-item active"><?= get_label('details', 'Details') ?></li>
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
                        <h5><?= get_label('journal_entry_information', 'Journal Entry Information') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><?= get_label('id', 'ID') ?>:</strong></td>
                                    <td>{{ $journalEntry->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('entry_number', 'Entry Number') ?>:</strong></td>
                                    <td>{{ $journalEntry->entry_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('entry_type', 'Entry Type') ?>:</strong></td>
                                    <td>{{ ucfirst(get_label($journalEntry->entry_type, $journalEntry->entry_type)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('entry_date', 'Entry Date') ?>:</strong></td>
                                    <td>{{ format_date($journalEntry->entry_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('reference_number', 'Reference Number') ?>:</strong></td>
                                    <td>{{ $journalEntry->reference_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('contract', 'Contract') ?>:</strong></td>
                                    <td>{{ $journalEntry->contract->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('invoice', 'Invoice') ?>:</strong></td>
                                    <td>{{ $journalEntry->invoice->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('description', 'Description') ?>:</strong></td>
                                    <td>{{ $journalEntry->description }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('debit_amount', 'Debit Amount') ?>:</strong></td>
                                    <td>{{ format_currency($journalEntry->debit_amount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('credit_amount', 'Credit Amount') ?>:</strong></td>
                                    <td>{{ format_currency($journalEntry->credit_amount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('account_code', 'Account Code') ?>:</strong></td>
                                    <td>{{ $journalEntry->account_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('account_name', 'Account Name') ?>:</strong></td>
                                    <td>{{ $journalEntry->account_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('status', 'Status') ?>:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $journalEntry->status === 'posted' ? 'success' : ($journalEntry->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(get_label($journalEntry->status, $journalEntry->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('created_by', 'Created By') ?>:</strong></td>
                                    <td>{{ $journalEntry->createdBy->first_name ?? 'N/A' }} {{ $journalEntry->createdBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('created_at', 'Created At') ?>:</strong></td>
                                    <td>{{ format_date($journalEntry->created_at, true) }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary"><?= get_label('back_to_list', 'Back to List') ?></a>
                            <a href="{{ route('journal-entries.edit', $journalEntry->id) }}" class="btn btn-primary"><?= get_label('edit', 'Edit') ?></a>
                            
                            @if($journalEntry->status !== 'posted')
                            <button class="btn btn-success" id="postToAccountingBtn" data-id="{{ $journalEntry->id }}">
                                <?= get_label('post_to_accounting', 'Post to Accounting') ?>
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