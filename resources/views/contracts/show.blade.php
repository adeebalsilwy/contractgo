@extends('layout')
@section('title')
    <?= get_label('contract_details', 'Contract Details') ?>
@endsection
@section('content')
    @php
        $menu = 'contracts';
        $sub_menu = 'contracts';
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
                        <li class="breadcrumb-item active"><?= get_label('contract_details', 'Contract Details') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contracts.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
                @if (checkPermission('edit_contracts'))
                    <a href="{{ route('contracts.edit', $contract->id) }}">
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('edit_contract', 'Edit Contract') ?>">
                            <i class='bx bx-edit'></i>
                        </button>
                    </a>
                @endif
                @if (checkPermission('delete_contracts') && $contract->workflow_status === 'draft')
                    <button type="button" class="btn btn-sm btn-danger delete" data-id="{{ $contract->id }}" data-type="contracts" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('delete_contract', 'Delete Contract') ?>">
                        <i class='bx bx-trash'></i>
                    </button>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Contract Basic Information -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-file"></i> <?= get_label('contract_information', 'Contract Information') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('title', 'Title') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ $contract->title }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('value', 'Value') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ format_currency($contract->value) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('start_date', 'Start Date') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ format_date($contract->start_date) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('end_date', 'End Date') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ format_date($contract->end_date) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('workflow_status', 'Workflow Status') ?>:</strong></label>
                                    <p class="form-control-plaintext">
                                        @if ($contract->is_archived)
                                            <span class="badge bg-secondary"><?= get_label('archived', 'Archived') ?></span>
                                        @else
                                            <span class="badge bg-{{ $contract->workflow_status === 'approved' ? 'success' : 'primary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $contract->workflow_status)) }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('status', 'Status') ?>:</strong></label>
                                    <p class="form-control-plaintext">
                                        @if ($contract->promisor_sign && $contract->promisee_sign)
                                            <span class="badge bg-success"><?= get_label('signed', 'Signed') ?></span>
                                        @elseif ($contract->promisor_sign || $contract->promisee_sign)
                                            <span class="badge bg-warning"><?= get_label('partially_signed', 'Partially Signed') ?></span>
                                        @else
                                            <span class="badge bg-secondary"><?= get_label('not_signed', 'Not Signed') ?></span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('description', 'Description') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ $contract->description ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Workflow Assignments -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-user-check"></i> <?= get_label('workflow_assignments', 'Workflow Assignments') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('site_supervisor', 'Site Supervisor') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ $contract->siteSupervisor ? $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('quantity_approver', 'Quantity Approver') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ $contract->quantityApprover ? $contract->quantityApprover->first_name . ' ' . $contract->quantityApprover->last_name : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('accountant', 'Accountant') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ $contract->accountant ? $contract->accountant->first_name . ' ' . $contract->accountant->last_name : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('reviewer', 'Reviewer') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ $contract->reviewer ? $contract->reviewer->first_name . ' ' . $contract->reviewer->last_name : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><strong><?= get_label('final_approver', 'Final Approver') ?>:</strong></label>
                                    <p class="form-control-plaintext">{{ $contract->finalApprover ? $contract->finalApprover->first_name . ' ' . $contract->finalApprover->last_name : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Modules Overview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-link"></i> <?= get_label('related_modules', 'Related Modules') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <a href="{{ route('contract-quantities.index') }}?contract_id={{ $contract->id }}" class="text-decoration-none">
                                        <div class="bg-primary bg-opacity-10 rounded p-3 mb-2">
                                            <i class="bx bx-list-check fs-1 text-primary"></i>
                                            <h5 class="mt-2 mb-0">{{ $contract->quantities_count }}</h5>
                                            <p class="mb-0 small"><?= get_label('quantities', 'Quantities') ?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <a href="{{ route('contract-approvals.history', $contract->id) }}" class="text-decoration-none">
                                        <div class="bg-success bg-opacity-10 rounded p-3 mb-2">
                                            <i class="bx bx-check-circle fs-1 text-success"></i>
                                            <h5 class="mt-2 mb-0">{{ $contract->approvals_count }}</h5>
                                            <p class="mb-0 small"><?= get_label('approvals', 'Approvals') ?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <a href="{{ route('contract-amendments.index') }}?contract_id={{ $contract->id }}" class="text-decoration-none">
                                        <div class="bg-warning bg-opacity-10 rounded p-3 mb-2">
                                            <i class="bx bx-edit fs-1 text-warning"></i>
                                            <h5 class="mt-2 mb-0">{{ $contract->amendments_count }}</h5>
                                            <p class="mb-0 small"><?= get_label('amendments', 'Amendments') ?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <a href="{{ route('journal-entries.index') }}?contract_id={{ $contract->id }}" class="text-decoration-none">
                                        <div class="bg-info bg-opacity-10 rounded p-3 mb-2">
                                            <i class="bx bx-receipt fs-1 text-info"></i>
                                            <h5 class="mt-2 mb-0">{{ $contract->journal_entries_count }}</h5>
                                            <p class="mb-0 small"><?= get_label('journal_entries', 'Journal Entries') ?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contract Metadata and Actions -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-info-circle"></i> <?= get_label('contract_metadata', 'Contract Metadata') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><strong><?= get_label('contract_id', 'Contract ID') ?>:</strong></label>
                            <p class="form-control-plaintext">{{ get_label('contract_id_prefix', 'CTR-') }}{{ $contract->id }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong><?= get_label('contract_type', 'Contract Type') ?>:</strong></label>
                            <p class="form-control-plaintext">{{ $contract->contract_type->type ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong><?= get_label('client', 'Client') ?>:</strong></label>
                            <p class="form-control-plaintext">{{ $contract->client ? $contract->client->first_name . ' ' . $contract->client->last_name : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong><?= get_label('project', 'Project') ?>:</strong></label>
                            <p class="form-control-plaintext">{{ $contract->project ? $contract->project->title : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong><?= get_label('created_by', 'Created By') ?>:</strong></label>
                            <p class="form-control-plaintext">{{ $contract->createdBy ? $contract->createdBy->first_name . ' ' . $contract->createdBy->last_name : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong><?= get_label('created_at', 'Created At') ?>:</strong></label>
                            <p class="form-control-plaintext">{{ format_date($contract->created_at, true) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong><?= get_label('updated_at', 'Updated At') ?>:</strong></label>
                            <p class="form-control-plaintext">{{ format_date($contract->updated_at, true) }}</p>
                        </div>
                        @if ($contract->is_archived)
                            <div class="mb-3">
                                <label class="form-label"><strong><?= get_label('archived_at', 'Archived At') ?>:</strong></label>
                                <p class="form-control-plaintext">{{ format_date($contract->archived_at, true) }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong><?= get_label('archived_by', 'Archived By') ?>:</strong></label>
                                <p class="form-control-plaintext">{{ $contract->archivedBy ? $contract->archivedBy->first_name . ' ' . $contract->archivedBy->last_name : '-' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-bolt"></i> <?= get_label('quick_actions', 'Quick Actions') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (checkPermission('manage_contracts'))
                            @if (!$contract->is_archived && $contract->workflow_status === 'approved')
                                <button type="button" class="btn btn-sm btn-outline-info w-100 mb-2 archive-contract" data-id="{{ $contract->id }}">
                                    <i class="bx bx-archive"></i> <?= get_label('archive_contract', 'Archive Contract') ?>
                                </button>
                            @elseif ($contract->is_archived)
                                <button type="button" class="btn btn-sm btn-outline-warning w-100 mb-2 unarchive-contract" data-id="{{ $contract->id }}">
                                    <i class="bx bx-unarchive"></i> <?= get_label('unarchive_contract', 'Unarchive Contract') ?>
                                </button>
                            @endif
                        @endif
                        
                        @if ($contract->signed_pdf && Storage::disk('public')->exists('contracts/' . $contract->signed_pdf))
                            <a href="{{ Storage::url('contracts/' . $contract->signed_pdf) }}" target="_blank" class="btn btn-sm btn-outline-success w-100 mb-2">
                                <i class="bx bx-download"></i> <?= get_label('download_contract', 'Download Contract') ?>
                            </a>
                        @endif
                        
                        <a href="{{ route('contracts.duplicate', $contract->id) }}" class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="bx bx-copy"></i> <?= get_label('duplicate_contract', 'Duplicate Contract') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle archive/unarchive actions
        $(document).on('click', '.archive-contract', function() {
            var contractId = $(this).data('id');
            if (confirm('<?= get_label('confirm_archive_contract', 'Are you sure you want to archive this contract?') ?>')) {
                $.ajax({
                    url: '/contracts/' + contractId + '/archive',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (!response.error) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        });

        $(document).on('click', '.unarchive-contract', function() {
            var contractId = $(this).data('id');
            if (confirm('<?= get_label('confirm_unarchive_contract', 'Are you sure you want to unarchive this contract?') ?>')) {
                $.ajax({
                    url: '/contracts/' + contractId + '/unarchive',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (!response.error) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        });
    </script>
    @endpush
@endsection