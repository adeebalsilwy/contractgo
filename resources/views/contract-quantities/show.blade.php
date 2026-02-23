@extends('layout')
@section('title')
    <?= get_label('contract_quantity_details', 'Contract Quantity Details') ?>
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
                            <a href="{{ route('contract-quantities.index') }}"><?= get_label('contract_quantities', 'Contract Quantities') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('details', 'Details') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-quantities.index') }}">
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
                        <h5><?= get_label('contract_quantity_information', 'Contract Quantity Information') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><?= get_label('id', 'ID') ?>:</strong></td>
                                    <td>{{ $contractQuantity->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('contract', 'Contract') ?>:</strong></td>
                                    <td>{{ $contractQuantity->contract->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('item_description', 'Item Description') ?>:</strong></td>
                                    <td>{{ $contractQuantity->item_description }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('requested_quantity', 'Requested Quantity') ?>:</strong></td>
                                    <td>{{ $contractQuantity->requested_quantity }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('approved_quantity', 'Approved Quantity') ?>:</strong></td>
                                    <td>{{ $contractQuantity->approved_quantity ?? get_label('not_yet_approved', 'Not Yet Approved') }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('unit', 'Unit') ?>:</strong></td>
                                    <td>{{ $contractQuantity->unit }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('unit_price', 'Unit Price') ?>:</strong></td>
                                    <td>{{ $contractQuantity->unit_price ? format_currency($contractQuantity->unit_price) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('total_amount', 'Total Amount') ?>:</strong></td>
                                    <td>{{ $contractQuantity->total_amount ? format_currency($contractQuantity->total_amount) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('status', 'Status') ?>:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $contractQuantity->status === 'approved' ? 'success' : ($contractQuantity->status === 'rejected' ? 'danger' : ($contractQuantity->status === 'modified' ? 'warning' : 'primary')) }}">
                                            {{ ucfirst(get_label($contractQuantity->status, $contractQuantity->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('submitted_by', 'Submitted By') ?>:</strong></td>
                                    <td>{{ $contractQuantity->user->first_name ?? 'N/A' }} {{ $contractQuantity->user->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('submitted_at', 'Submitted At') ?>:</strong></td>
                                    <td>{{ format_date($contractQuantity->submitted_at, true) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('notes', 'Notes') ?>:</strong></td>
                                    <td>{{ $contractQuantity->notes ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('contract-quantities.index') }}" class="btn btn-secondary"><?= get_label('back_to_list', 'Back to List') ?></a>
                            <a href="{{ route('contract-quantities.edit', $contractQuantity->id) }}" class="btn btn-primary"><?= get_label('edit', 'Edit') ?></a>
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