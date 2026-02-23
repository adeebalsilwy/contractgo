@extends('layout')
@section('title')
    <?= get_label('pending_quantity_approvals', 'Pending Quantity Approvals') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-approvals';
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
                            <a href="{{ route('contract-approvals.index') }}"><?= get_label('contract_approvals', 'Contract Approvals') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('pending_quantity_approvals', 'Pending Quantity Approvals') ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('quantities_awaiting_your_approval', 'Quantities Awaiting Your Approval') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th><?= get_label('contract', 'Contract') ?></th>
                                        <th><?= get_label('item_description', 'Item Description') ?></th>
                                        <th><?= get_label('requested_quantity', 'Requested Quantity') ?></th>
                                        <th><?= get_label('unit', 'Unit') ?></th>
                                        <th><?= get_label('unit_price', 'Unit Price') ?></th>
                                        <th><?= get_label('submitted_by', 'Submitted By') ?></th>
                                        <th><?= get_label('submitted_at', 'Submitted At') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contractQuantities as $quantity)
                                    <tr>
                                        <td>{{ $quantity->id }}</td>
                                        <td>{{ $quantity->contract->title }}</td>
                                        <td>{{ $quantity->item_description }}</td>
                                        <td>{{ $quantity->requested_quantity }}</td>
                                        <td>{{ $quantity->unit }}</td>
                                        <td>{{ $quantity->unit_price ? format_currency($quantity->unit_price) : 'N/A' }}</td>
                                        <td>{{ $quantity->user->first_name }} {{ $quantity->user->last_name }}</td>
                                        <td>{{ format_date($quantity->submitted_at, true) }}</td>
                                        <td>
                                            <a href="{{ route('contract-quantities.show', $quantity->id) }}" class="btn btn-info btn-sm"><?= get_label('view', 'View') ?></a>
                                            <a href="{{ route('contract-quantities.edit', $quantity->id) }}" class="btn btn-warning btn-sm"><?= get_label('modify', 'Modify') ?></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center"><?= get_label('no_pending_quantity_approvals_found', 'No pending quantity approvals found.') ?></td>
                                    </tr>
                                    @endforelse
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