@extends('layout')
@section('title')
    <?= get_label('contract_obligations', 'Contract Obligations') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-obligations';
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
                        <li class="breadcrumb-item active"><?= get_label('contract_obligations', 'Contract Obligations') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-obligations.create') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_contract_obligation', 'Create Contract Obligation') ?>">
                        <i class='bx bx-plus'></i> <?= get_label('create', 'Create') ?>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('contract_obligations_list', 'Contract Obligations List') ?></h5>
                        <div class="dt-plugin-buttons d-flex align-items-center gap-2">
                            <select id="contractFilter" class="form-control" style="width: 200px;">
                                <option value=""><?= get_label('all_contracts', 'All Contracts') ?></option>
                                @foreach($contracts as $contract)
                                    <option value="{{ $contract->id }}">{{ $contract->title }}</option>
                                @endforeach
                            </select>
                            <select id="statusFilter" class="form-control" style="width: 150px;">
                                <option value=""><?= get_label('all_statuses', 'All Statuses') ?></option>
                                <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                                <option value="in_progress"><?= get_label('in_progress', 'In Progress') ?></option>
                                <option value="completed"><?= get_label('completed', 'Completed') ?></option>
                                <option value="overdue"><?= get_label('overdue', 'Overdue') ?></option>
                            </select>
                            <button class="btn btn-primary" id="filterBtn"><?= get_label('filter', 'Filter') ?></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('id', 'ID') ?></th>
                                        <th><?= get_label('contract', 'Contract') ?></th>
                                        <th><?= get_label('party', 'Party') ?></th>
                                        <th><?= get_label('title', 'Title') ?></th>
                                        <th><?= get_label('type', 'Type') ?></th>
                                        <th><?= get_label('priority', 'Priority') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('due_date', 'Due Date') ?></th>
                                        <th><?= get_label('assigned_to', 'Assigned To') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($obligations as $obligation)
                                    <tr>
                                        <td>{{ $obligation->id }}</td>
                                        <td>
                                            <a href="{{ route('contracts.show', $obligation->contract->id) }}">
                                                {{ $obligation->contract->title }}
                                            </a>
                                        </td>
                                        <td>{{ $obligation->party->first_name ?? 'N/A' }} {{ $obligation->party->last_name ?? '' }}</td>
                                        <td>{{ $obligation->title }}</td>
                                        <td>
                                            <span class="badge bg-{{ $obligation->obligation_type === 'payment' ? 'success' : ($obligation->obligation_type === 'delivery' ? 'info' : ($obligation->obligation_type === 'performance' ? 'warning' : 'primary')) }}">
                                                {{ ucfirst($obligation->obligation_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $obligation->priority === 'critical' ? 'danger' : ($obligation->priority === 'high' ? 'warning' : ($obligation->priority === 'medium' ? 'primary' : 'secondary')) }}">
                                                {{ ucfirst($obligation->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $obligation->status === 'completed' ? 'success' : ($obligation->status === 'in_progress' ? 'info' : ($obligation->status === 'overdue' ? 'danger' : 'warning')) }}">
                                                {{ ucfirst($obligation->status) }}
                                                @if($obligation->is_overdue)
                                                    <i class="bx bx-error text-danger" title="<?= get_label('overdue', 'Overdue') ?>"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            {{ $obligation->due_date ? format_date($obligation->due_date) : 'N/A' }}
                                            @if($obligation->is_due_soon)
                                                <i class="bx bx-time-five text-warning" title="<?= get_label('due_soon', 'Due Soon') ?>"></i>
                                            @endif
                                        </td>
                                        <td>{{ $obligation->assignedTo->first_name ?? 'Unassigned' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('contract-obligations.show', $obligation->id) }}">
                                                        <i class="bx bx-show-alt me-1"></i> <?= get_label('view', 'View') ?>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('contract-obligations.edit', $obligation->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> <?= get_label('edit', 'Edit') ?>
                                                    </a>
                                                    <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $obligation->id }}" data-type="contract_obligations">
                                                        <i class="bx bx-trash me-1"></i> <?= get_label('delete', 'Delete') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center"><?= get_label('no_obligations_found', 'No obligations found') ?></td>
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
    <script>
        $(document).ready(function() {
            $('#filterBtn').on('click', function() {
                const contractId = $('#contractFilter').val();
                const status = $('#statusFilter').val();
                
                let url = "{{ route('contract-obligations.index') }}";
                const params = [];
                
                if (contractId) params.push('contract_id=' + contractId);
                if (status) params.push('status=' + status);
                
                if (params.length > 0) {
                    url += '?' + params.join('&');
                }
                
                window.location.href = url;
            });
        });
    </script>
    @endpush
@endsection