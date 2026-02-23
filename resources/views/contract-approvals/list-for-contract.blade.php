@extends('layout')
@section('title')
    <?= get_label('contract_approval_history', 'Contract Approval History') ?> - {{ $contract->title }}
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
                            <a href="{{ route('contracts.index') }}"><?= get_label('contracts', 'Contracts') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('approval_history', 'Approval History') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contracts.show', $contract->id) }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_contract', 'Back to Contract') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('approval_timeline', 'Approval Timeline') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('stage', 'Stage') ?></th>
                                        <th><?= get_label('approver', 'Approver') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('comments', 'Comments') ?></th>
                                        <th><?= get_label('date', 'Date') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvals as $approval)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', get_label($approval->approval_stage, $approval->approval_stage))) }}</td>
                                        <td>{{ $approval->approver->first_name ?? 'N/A' }} {{ $approval->approver->last_name ?? '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst(get_label($approval->status, $approval->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $approval->comments ?? 'N/A' }}</td>
                                        <td>{{ format_date($approval->approved_rejected_at ?? $approval->created_at, true) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center"><?= get_label('no_approval_records_found_for_this_contract', 'No approval records found for this contract.') ?></td>
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