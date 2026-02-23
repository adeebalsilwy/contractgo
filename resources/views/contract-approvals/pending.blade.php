@extends('layout')
@section('title')
    <?= get_label('pending_contract_approvals', 'Pending Contract Approvals') ?>
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
                            <a href="{{ url('contracts') }}"><?= get_label('contracts', 'Contracts') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('pending_approvals', 'Pending Approvals') ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('contracts_awaiting_your_approval', 'Contracts Awaiting Your Approval') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th><?= get_label('contract', 'Contract') ?></th>
                                        <th><?= get_label('stage', 'Stage') ?></th>
                                        <th><?= get_label('submitted_by', 'Submitted By') ?></th>
                                        <th><?= get_label('submitted_at', 'Submitted At') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvals as $approval)
                                    <tr>
                                        <td>{{ $approval->id }}</td>
                                        <td>{{ $approval->contract->title }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', get_label($approval->approval_stage, $approval->approval_stage))) }}</td>
                                        <td>{{ $approval->contract->createdBy->first_name ?? 'N/A' }} {{ $approval->contract->createdBy->last_name ?? '' }}</td>
                                        <td>{{ format_date($approval->created_at, true) }}</td>
                                        <td>
                                            <a href="{{ route('contract-approvals.show', [$approval->contract_id, $approval->approval_stage]) }}" class="btn btn-primary btn-sm"><?= get_label('review', 'Review') ?></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center"><?= get_label('no_pending_approvals_found', 'No pending approvals found.') ?></td>
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
    <script src="{{ asset('assets/js/pages/contract-approvals.js') }}"></script>
    @endpush
@endsection