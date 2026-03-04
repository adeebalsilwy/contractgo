@extends('layout')
@section('title')
    <?= get_label('contract_dashboard', 'Contract Dashboard') ?>
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
                        <li class="breadcrumb-item active">
                            <?= get_label('contract_dashboard', 'Contract Dashboard') ?>
                        </li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contracts.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view_all_contracts', 'View All Contracts') ?>">
                        <i class='bx bx-list-ul'></i> <?= get_label('contracts', 'Contracts') ?>
                    </button>
                </a>
                @if (checkPermission('create_contracts'))
                    <a href="{{ route('contracts.create') }}">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_contract', 'Create Contract') ?>">
                            <i class='bx bx-plus'></i> <?= get_label('create', 'Create') ?>
                        </button>
                    </a>
                @endif
                <a href="{{ route('contract-quantities.create') }}">
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_extract', 'Create Extract') ?>">
                        <i class='bx bx-calculator'></i> <?= get_label('create_extract', 'Create Extract') ?>
                    </button>
                </a>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded p-3 mb-3">
                            <i class="bx bx-file fs-1 text-primary"></i>
                        </div>
                        <h3 class="mb-1">{{ $stats['total_contracts'] }}</h3>
                        <p class="text-muted mb-0"><?= get_label('total_contracts', 'Total Contracts') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 rounded p-3 mb-3">
                            <i class="bx bx-check-circle fs-1 text-success"></i>
                        </div>
                        <h3 class="mb-1">{{ $stats['approved_contracts'] }}</h3>
                        <p class="text-muted mb-0"><?= get_label('approved_contracts', 'Approved Contracts') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="bg-warning bg-opacity-10 rounded p-3 mb-3">
                            <i class="bx bx-time fs-1 text-warning"></i>
                        </div>
                        <h3 class="mb-1">{{ $stats['pending_contracts'] }}</h3>
                        <p class="text-muted mb-0"><?= get_label('pending_contracts', 'Pending Contracts') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="bg-info bg-opacity-10 rounded p-3 mb-3">
                            <i class="bx bx-archive fs-1 text-info"></i>
                        </div>
                        <h3 class="mb-1">{{ $stats['archived_contracts'] }}</h3>
                        <p class="text-muted mb-0"><?= get_label('archived_contracts', 'Archived Contracts') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Workflow Status Distribution -->
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-bar-chart"></i> <?= get_label('workflow_status_distribution', 'Workflow Status Distribution') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($workflowStats as $status => $count)
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="badge bg-{{ $status === 'approved' ? 'success' : ($status === 'draft' ? 'secondary' : 'primary') }} rounded-pill" style="width: 12px; height: 12px;"></span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $status)) }}</h6>
                                            <p class="mb-0 text-muted">{{ $count }} <?= get_label('contracts', 'contracts') ?></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-history"></i> <?= get_label('recent_activity', 'Recent Activity') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentActivity->count() > 0)
                            <div class="timeline">
                                @foreach($recentActivity as $activity)
                                    <div class="timeline-item">
                                        <div class="timeline-point">
                                            <i class="bx bx-{{ $activity['icon'] }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                            <p class="text-muted small mb-1">{{ $activity['description'] }}</p>
                                            <small class="text-muted">{{ format_date($activity['created_at'], true) }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-info-circle fs-1 text-muted"></i>
                                <p class="mt-2 mb-0 text-muted"><?= get_label('no_recent_activity', 'No recent activity') ?></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions and Modules Overview -->
            <div class="col-md-4 mb-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-bolt"></i> <?= get_label('quick_actions', 'Quick Actions') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (checkPermission('create_contracts'))
                            <a href="{{ route('contracts.create') }}" class="btn btn-primary w-100 mb-2">
                                <i class="bx bx-plus"></i> <?= get_label('create_contract', 'Create Contract') ?>
                            </a>
                        @endif
                        <a href="{{ route('contract-quantities.pending-approval') }}" class="btn btn-outline-warning w-100 mb-2">
                            <i class="bx bx-list-check"></i> <?= get_label('pending_quantities', 'Pending Quantities') ?> ({{ $stats['pending_quantities'] }})
                        </a>
                        <a href="{{ route('contract-approvals.pending') }}" class="btn btn-outline-info w-100 mb-2">
                            <i class="bx bx-check-shield"></i> <?= get_label('pending_approvals', 'Pending Approvals') ?> ({{ $stats['pending_approvals'] }})
                        </a>
                        <a href="{{ route('contract-amendments.index') }}?status=pending" class="btn btn-outline-warning w-100 mb-2">
                            <i class="bx bx-edit"></i> <?= get_label('pending_amendments', 'Pending Amendments') ?> ({{ $stats['pending_amendments'] }})
                        </a>
                    </div>
                </div>

                <!-- Modules Overview -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-link"></i> <?= get_label('related_modules', 'Related Modules') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('contract-quantities.index') }}" class="btn btn-outline-primary">
                                <i class="bx bx-list-check"></i> <?= get_label('contract_quantities', 'Contract Quantities') ?>
                                <span class="badge bg-primary float-end">{{ $stats['total_quantities'] }}</span>
                            </a>
                            <a href="{{ route('contract-approvals.index') }}" class="btn btn-outline-success">
                                <i class="bx bx-check-circle"></i> <?= get_label('contract_approvals', 'Contract Approvals') ?>
                                <span class="badge bg-success float-end">{{ $stats['total_approvals'] }}</span>
                            </a>
                            <a href="{{ route('contract-amendments.index') }}" class="btn btn-outline-warning">
                                <i class="bx bx-edit"></i> <?= get_label('contract_amendments', 'Contract Amendments') ?>
                                <span class="badge bg-warning float-end">{{ $stats['total_amendments'] }}</span>
                            </a>
                            <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-info">
                                <i class="bx bx-receipt"></i> <?= get_label('journal_entries', 'Journal Entries') ?>
                                <span class="badge bg-info float-end">{{ $stats['total_journal_entries'] }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Contracts -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-calendar"></i> <?= get_label('upcoming_contracts', 'Upcoming Contracts') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($upcomingContracts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?= get_label('contract', 'Contract') ?></th>
                                            <th><?= get_label('client', 'Client') ?></th>
                                            <th><?= get_label('start_date', 'Start Date') ?></th>
                                            <th><?= get_label('end_date', 'End Date') ?></th>
                                            <th><?= get_label('days_remaining', 'Days Remaining') ?></th>
                                            <th><?= get_label('actions', 'Actions') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingContracts as $contract)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('contracts.show', $contract->id) }}">{{ $contract->title }}</a>
                                                </td>
                                                <td>{{ $contract->client ? $contract->client->first_name . ' ' . $contract->client->last_name : '-' }}</td>
                                                <td>{{ format_date($contract->start_date) }}</td>
                                                <td>{{ format_date($contract->end_date) }}</td>
                                                <td>
                                                    @php
                                                        $daysRemaining = \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::now(), false);
                                                    @endphp
                                                    @if($daysRemaining > 0)
                                                        <span class="badge bg-success">{{ abs($daysRemaining) }} <?= get_label('days_ago', 'days ago') ?></span>
                                                    @elseif($daysRemaining < 0)
                                                        <span class="badge bg-warning">{{ abs($daysRemaining) }} <?= get_label('days_ahead', 'days ahead') ?></span>
                                                    @else
                                                        <span class="badge bg-primary"><?= get_label('today', 'Today') ?></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    @if (checkPermission('edit_contracts'))
                                                        <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-outline-warning">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-calendar fs-1 text-muted"></i>
                                <p class="mt-2 mb-0 text-muted"><?= get_label('no_upcoming_contracts', 'No upcoming contracts') ?></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection