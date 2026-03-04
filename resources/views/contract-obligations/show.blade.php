@extends('layout')
@section('title')
    <?= get_label('contract_obligation_details', 'Contract Obligation Details') ?>
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
                            <a href="{{ route('contract-obligations.index') }}"><?= get_label('contract_obligations', 'Contract Obligations') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('details', 'Details') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-obligations.index') }}">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i> <?= get_label('back', 'Back') ?>
                    </button>
                </a>
                <button type="button" class="btn btn-sm btn-outline-dark" id="print-obligation" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('print_obligation', 'Print Obligation') ?>">
                    <i class='bx bx-printer'></i> <?= get_label('print', 'Print') ?>
                </button>
                @if(checkPermission('edit_contract_obligations'))
                <a href="{{ route('contract-obligations.edit', $obligation->id) }}">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('edit', 'Edit') ?>">
                        <i class='bx bx-edit'></i>
                    </button>
                </a>
                @endif
            </div>
        </div>

        <!-- Obligation Header with Title and Key Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="fw-bold">{{ $obligation->title }}
                                    <a href="javascript:void(0);">
                                        <i class='bx {{getFavoriteStatus('obligation_'.$obligation->id) ? "bxs" : "bx"}}-star favorite-icon text-warning' data-id="{{$obligation->id}}" data-type="obligation" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{getFavoriteStatus('obligation_'.$obligation->id) ? get_label('remove_favorite', 'Click to remove from favorite') : get_label('add_favorite', 'Click to mark as favorite')}}" data-favorite="{{getFavoriteStatus('obligation_'.$obligation->id) ? 1 : 0}}"></i>
                                    </a>
                                </h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= get_label('status', 'Status') ?></label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $obligation->status === 'completed' ? 'success' : ($obligation->status === 'overdue' ? 'danger' : ($obligation->status === 'in_progress' ? 'info' : ($obligation->status === 'cancelled' ? 'secondary' : 'warning'))) }}">
                                                {{ ucfirst(str_replace('_', ' ', $obligation->status)) }}
                                                @if($obligation->is_overdue)
                                                    <i class="bx bx-error text-white ms-1" title="<?= get_label('overdue', 'Overdue') ?>"></i>
                                                @elseif($obligation->is_due_soon)
                                                    <i class="bx bx-time-five text-white ms-1" title="<?= get_label('due_soon', 'Due Soon') ?>"></i>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $obligation->priority === 'critical' ? 'danger' : ($obligation->priority === 'high' ? 'warning' : ($obligation->priority === 'medium' ? 'primary' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $obligation->priority)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= get_label('type', 'Type') ?></label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $obligation->obligation_type === 'payment' ? 'success' : ($obligation->obligation_type === 'delivery' ? 'info' : ($obligation->obligation_type === 'performance' ? 'warning' : ($obligation->obligation_type === 'compliance' ? 'primary' : 'secondary'))) }}">
                                                {{ ucfirst(str_replace('_', ' ', $obligation->obligation_type)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= get_label('compliance_status', 'Compliance Status') ?></label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $obligation->compliance_status === 'compliant' ? 'success' : ($obligation->compliance_status === 'non_compliant' ? 'danger' : ($obligation->compliance_status === 'partially_compliant' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', str_replace('_', ' ', $obligation->compliance_status))) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                                <div class="card overflow-hidden mb-4 statisticsDiv">
                                    <div class="card-header pt-3 pb-1">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2"><?= get_label('obligation_statistics', 'Obligation Statistics') ?></h5>
                                        </div>
                                        <div class="my-3">
                                            <div id="obligationStatisticsChart"></div>
                                        </div>
                                    </div>
                                    <div class="card-body" id="obligation-statistics">
                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-{{ $obligation->status === 'completed' ? 'success' : ($obligation->status === 'overdue' ? 'danger' : ($obligation->status === 'in_progress' ? 'info' : 'warning')) }}"><i class="bx bx-task"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0"><?= get_label('status', 'Status') ?></h6>
                                                    </div>
                                                    <div class="user-progress">
                                                        <div class="status-count">
                                                            <small class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $obligation->status)) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-{{ $obligation->priority === 'critical' ? 'danger' : ($obligation->priority === 'high' ? 'warning' : ($obligation->priority === 'medium' ? 'primary' : 'secondary')) }}"><i class="bx bx-flag"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0"><?= get_label('priority', 'Priority') ?></h6>
                                                    </div>
                                                    <div class="user-progress">
                                                        <div class="status-count">
                                                            <small class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $obligation->priority)) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-{{ $obligation->obligation_type === 'payment' ? 'success' : ($obligation->obligation_type === 'delivery' ? 'info' : ($obligation->obligation_type === 'performance' ? 'warning' : 'primary')) }}"><i class="bx bx-detail"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0"><?= get_label('type', 'Type') ?></h6>
                                                    </div>
                                                    <div class="user-progress">
                                                        <div class="status-count">
                                                            <small class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $obligation->obligation_type)) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-6 mb-4">
                                <!-- Due Date card -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-calendar-check bx-md text-success"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('due_date', 'Due Date') ?></span>
                                        <h3 class="card-title mb-2">{{ $obligation->due_date ? format_date($obligation->due_date) : '-' }}</h3>
                                    </div>
                                </div>
                                
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-time bx-md text-primary"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('days_remaining', 'Days Remaining') ?></span>
                                        <h3 class="card-title mb-2">
                                            @if($obligation->due_date)
                                                @if($obligation->status === 'completed')
                                                    <span class="text-success">Completed</span>
                                                @elseif($obligation->is_overdue)
                                                    <span class="text-danger">{{ $obligation->due_date->diffInDays(now()) }} days overdue</span>
                                                @elseif($obligation->is_due_soon)
                                                    <span class="text-warning">{{ $obligation->due_date->diffInDays(now()) }} days remaining</span>
                                                @else
                                                    {{ $obligation->due_date->diffInDays(now()) }} days
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-6 mb-4">
                                <!-- Completed Date card -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-calendar-check bx-md text-info"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('completed_date', 'Completed Date') ?></span>
                                        <h3 class="card-title mb-2">{{ $obligation->completed_date ? format_date($obligation->completed_date) : '-' }}</h3>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-user bx-md text-warning"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('assigned_to', 'Assigned To') ?></span>
                                        <h3 class="card-title mb-2">{{ $obligation->assignedTo->first_name ?? 'Unassigned' }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h5><?= get_label('description', 'Description') ?></h5>
                                        </div>
                                        <p>
                                            <?= filled($obligation->description) ? $obligation->description : '-' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Information Tabs -->
        @if(auth()->user()->can('manage_contracts') || auth()->user()->can('view_contracts') || auth()->user()->can('manage_contract_obligations'))
        <!-- Tabs -->
        <div class="nav-align-top mt-2">
            <ul class="nav nav-tabs" role="tablist">
                @php
                $activeTab = 'general';
                @endphp
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'general' ? 'active' : 'active' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-general" aria-controls="navs-top-general">
                        <i class="menu-icon tf-icons bx bx-info-circle text-primary"></i><?= get_label('general_info', 'General Info') ?>
                    </button>
                </li>
                
                @if(auth()->user()->can('manage_contracts'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'contract' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-contract" aria-controls="navs-top-contract">
                        <i class="menu-icon tf-icons bx bx-file text-info"></i><?= get_label('contract', 'Contract') ?>
                    </button>
                </li>
                @endif
                
                @if(auth()->user()->can('manage_contract_obligations'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'related' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-related" aria-controls="navs-top-related">
                        <i class="menu-icon tf-icons bx bx-link text-warning"></i><?= get_label('related_obligations', 'Related Obligations') ?>
                    </button>
                </li>
                @endif
                
                @if(auth()->user()->can('manage_activity_log'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'activity_log' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-activity-log" aria-controls="navs-top-activity-log">
                        <i class="menu-icon tf-icons bx bx-history text-secondary"></i><?= get_label('activity_log', 'Activity Log') ?>
                    </button>
                </li>
                @endif
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade {{ $activeTab == 'general' ? 'active show' : 'active show' }}" id="navs-top-general" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= get_label('basic_information', 'Basic Information') ?></h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong><?= get_label('id', 'ID') ?>:</strong></td>
                                            <td>{{ $obligation->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('title', 'Title') ?>:</strong></td>
                                            <td>{{ $obligation->title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('type', 'Type') ?>:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $obligation->obligation_type === 'payment' ? 'success' : ($obligation->obligation_type === 'delivery' ? 'info' : ($obligation->obligation_type === 'performance' ? 'warning' : 'primary')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $obligation->obligation_type)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('priority', 'Priority') ?>:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $obligation->priority === 'critical' ? 'danger' : ($obligation->priority === 'high' ? 'warning' : ($obligation->priority === 'medium' ? 'primary' : 'secondary')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $obligation->priority)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('status', 'Status') ?>:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $obligation->status === 'completed' ? 'success' : ($obligation->status === 'overdue' ? 'danger' : ($obligation->status === 'in_progress' ? 'info' : 'warning')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $obligation->status)) }}
                                                    @if($obligation->is_overdue)
                                                        <i class="bx bx-error text-white ms-1" title="<?= get_label('overdue', 'Overdue') ?>"></i>
                                                    @elseif($obligation->is_due_soon)
                                                        <i class="bx bx-time-five text-white ms-1" title="<?= get_label('due_soon', 'Due Soon') ?>"></i>
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= get_label('party_and_assignment', 'Party & Assignment') ?></h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong><?= get_label('party', 'Party') ?>:</strong></td>
                                            <td>{{ $obligation->party->first_name ?? 'N/A' }} {{ $obligation->party->last_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('party_type', 'Party Type') ?>:</strong></td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $obligation->party_type)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('assigned_to', 'Assigned To') ?>:</strong></td>
                                            <td>{{ $obligation->assignedTo->first_name ?? 'Unassigned' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('due_date', 'Due Date') ?>:</strong></td>
                                            <td>{{ $obligation->due_date ? format_date($obligation->due_date) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?= get_label('completed_date', 'Completed Date') ?>:</strong></td>
                                            <td>{{ $obligation->completed_date ? format_date($obligation->completed_date) : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= get_label('compliance_information', 'Compliance Information') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong><?= get_label('compliance_status', 'Compliance Status') ?>:</strong></td>
                                                    <td>
                                                        <span class="badge bg-{{ $obligation->compliance_status === 'compliant' ? 'success' : ($obligation->compliance_status === 'non_compliant' ? 'danger' : ($obligation->compliance_status === 'partially_compliant' ? 'warning' : 'secondary')) }}">
                                                            {{ ucfirst(str_replace('_', ' ', str_replace('_', ' ', $obligation->compliance_status))) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('checked_by', 'Checked By') ?>:</strong></td>
                                                    <td>{{ $obligation->complianceCheckedBy->first_name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('checked_at', 'Checked At') ?>:</strong></td>
                                                    <td>{{ $obligation->compliance_checked_at ? format_date($obligation->compliance_checked_at, true) : 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <strong><?= get_label('compliance_notes', 'Compliance Notes') ?>:</strong>
                                                <p>{{ $obligation->compliance_notes ?: 'No compliance notes' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($obligation->notes || $obligation->supporting_documents)
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= get_label('additional_information', 'Additional Information') ?></h5>
                                </div>
                                <div class="card-body">
                                    @if($obligation->notes)
                                    <div class="mb-3">
                                        <strong><?= get_label('notes', 'Notes') ?>:</strong>
                                        <p>{{ $obligation->notes }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($obligation->supporting_documents && count($obligation->supporting_documents) > 0)
                                    <div>
                                        <strong><?= get_label('supporting_documents', 'Supporting Documents') ?>:</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($obligation->supporting_documents as $document)
                                                <a href="{{ asset('storage/' . $document) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="bx bx-file"></i> {{ basename($document) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if(auth()->user()->can('manage_contracts'))
                <div class="tab-pane fade {{ $activeTab == 'contract' ? 'active show' : '' }}" id="navs-top-contract" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0"><?= get_label('related_contract', 'Related Contract') ?></h5>
                                    <a href="{{ route('contracts.show', $obligation->contract->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-show"></i> <?= get_label('view_contract', 'View Contract') ?>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong><?= get_label('contract_id', 'Contract ID') ?>:</strong></td>
                                                    <td>{{ $obligation->contract->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('contract_title', 'Contract Title') ?>:</strong></td>
                                                    <td>{{ $obligation->contract->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('contract_value', 'Contract Value') ?>:</strong></td>
                                                    <td>{{ format_currency($obligation->contract->value) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('contract_start_date', 'Start Date') ?>:</strong></td>
                                                    <td>{{ format_date($obligation->contract->start_date) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('contract_end_date', 'End Date') ?>:</strong></td>
                                                    <td>{{ format_date($obligation->contract->end_date) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong><?= get_label('contract_status', 'Status') ?>:</strong></td>
                                                    <td>
                                                        <span class="badge bg-{{ $obligation->contract->workflow_status === 'approved' ? 'success' : 'primary' }}">
                                                            {{ ucfirst(str_replace('_', ' ', $obligation->contract->workflow_status)) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('client', 'Client') ?>:</strong></td>
                                                    <td>{{ $obligation->contract->client->first_name ?? 'N/A' }} {{ $obligation->contract->client->last_name ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('project', 'Project') ?>:</strong></td>
                                                    <td>{{ $obligation->contract->project->title ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('created_at', 'Created At') ?>:</strong></td>
                                                    <td>{{ format_date($obligation->contract->created_at, true) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= get_label('updated_at', 'Updated At') ?>:</strong></td>
                                                    <td>{{ format_date($obligation->contract->updated_at, true) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(auth()->user()->can('manage_contract_obligations'))
                <div class="tab-pane fade {{ $activeTab == 'related' ? 'active show' : '' }}" id="navs-top-related" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0"><?= get_label('related_obligations', 'Related Obligations') ?></h5>
                                    <a href="{{ route('contract-obligations.create') }}?contract_id={{ $obligation->contract_id }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-plus"></i> <?= get_label('create_obligation', 'Create Obligation') ?>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?= get_label('id', 'ID') ?></th>
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
                                                @forelse($obligation->contract->obligations as $relatedObligation)
                                                <tr>
                                                    <td>{{ $relatedObligation->id }}</td>
                                                    <td>{{ $relatedObligation->title }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $relatedObligation->obligation_type === 'payment' ? 'success' : ($relatedObligation->obligation_type === 'delivery' ? 'info' : ($relatedObligation->obligation_type === 'performance' ? 'warning' : 'primary')) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $relatedObligation->obligation_type)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $relatedObligation->priority === 'critical' ? 'danger' : ($relatedObligation->priority === 'high' ? 'warning' : ($relatedObligation->priority === 'medium' ? 'primary' : 'secondary')) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $relatedObligation->priority)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $relatedObligation->status === 'completed' ? 'success' : ($relatedObligation->status === 'in_progress' ? 'info' : ($relatedObligation->status === 'overdue' ? 'danger' : 'warning')) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $relatedObligation->status)) }}
                                                            @if($relatedObligation->is_overdue)
                                                                <i class="bx bx-error text-white ms-1" title="<?= get_label('overdue', 'Overdue') ?>"></i>
                                                            @elseif($relatedObligation->is_due_soon)
                                                                <i class="bx bx-time-five text-white ms-1" title="<?= get_label('due_soon', 'Due Soon') ?>"></i>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>{{ $relatedObligation->due_date ? format_date($relatedObligation->due_date) : 'N/A' }}</td>
                                                    <td>{{ $relatedObligation->assignedTo->first_name ?? 'Unassigned' }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('contract-obligations.show', $relatedObligation->id) }}">
                                                                    <i class="bx bx-show-alt me-1"></i> <?= get_label('view', 'View') ?>
                                                                </a>
                                                                <a class="dropdown-item" href="{{ route('contract-obligations.edit', $relatedObligation->id) }}">
                                                                    <i class="bx bx-edit-alt me-1"></i> <?= get_label('edit', 'Edit') ?>
                                                                </a>
                                                                <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $relatedObligation->id }}" data-type="contract_obligations">
                                                                    <i class="bx bx-trash me-1"></i> <?= get_label('delete', 'Delete') ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center"><?= get_label('no_related_obligations', 'No related obligations found') ?></td>
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
                @endif
                
                @if(auth()->user()->can('manage_activity_log'))
                <div class="tab-pane fade {{ $activeTab == 'activity_log' ? 'active show' : '' }}" id="navs-top-activity-log" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= get_label('activity_log', 'Activity Log') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-4 mb-3">
                                            <div class="input-group input-group-merge">
                                                <input type="text" id="activity_log_between_date" class="form-control" placeholder="<?= get_label('date_between', 'Date between') ?>" autocomplete="off">
                                            </div>
                                        </div>
                                        @if(auth()->user()->can('manage_users'))
                                        <div class="col-md-4 mb-3">
                                            <select class="form-select users_select" id="user_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_actioned_by_users', 'Select Actioned By Users') ?>" multiple>
                                            </select>
                                        </div>
                                        @endif
                                        <div class="col-md-4 mb-3">
                                            <select class="form-select js-example-basic-multiple" id="activity_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_activities', 'Select Activities') ?>" data-allow-clear="true" multiple>
                                                <option value="created"><?= get_label('created', 'Created') ?></option>
                                                <option value="updated"><?= get_label('updated', 'Updated') ?></option>
                                                <option value="deleted"><?= get_label('deleted', 'Deleted') ?></option>
                                                <option value="completed"><?= get_label('completed', 'Completed') ?></option>
                                                <option value="compliance_updated"><?= get_label('compliance_updated', 'Compliance Updated') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    @php
                                    $visibleColumns = getUserPreferences('activity_log');
                                    @endphp
                                    <div class="table-responsive text-nowrap">
                                        <input type="hidden" id="activity_log_between_date_from">
                                        <input type="hidden" id="activity_log_between_date_to">
                                        <input type="hidden" id="data_type" value="activity-log">
                                        <input type="hidden" id="data_table" value="obligation_activity_log_table">
                                        <input type="hidden" id="type_id" value="{{$obligation->id}}">
                                        <input type="hidden" id="save_column_visibility">
                                        <input type="hidden" id="multi_select">
                                        <table id="obligation_activity_log_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="{{ url('/activity-log/list') }}" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="obligationQueryParams">
                                            <thead>
                                                <tr>
                                                    <th data-checkbox="true"></th>
                                                    <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                                                    <th data-field="actor_name" data-visible="{{ (in_array('actor_name', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('actioned_by', 'Actioned By') ?></th>
                                                    <th data-field="activity" data-visible="{{ (in_array('activity', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('activity', 'Activity') ?></th>
                                                    <th data-field="type" data-visible="{{ (in_array('type', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('type', 'Type') ?></th>
                                                    <th data-field="message" data-visible="{{ (in_array('message', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('message', 'Message') ?></th>
                                                    <th data-field="created_at" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('created_at', 'Created at') ?></th>
                                                    <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }"><?= get_label('actions', 'Actions') ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('contract-obligations.index') }}" class="btn btn-secondary"><?= get_label('back_to_list', 'Back to List') ?></a>
                            
                            @if(checkPermission('edit_contract_obligations'))
                            <div class="btn-group">
                                <a href="{{ route('contract-obligations.edit', $obligation->id) }}" class="btn btn-primary"><?= get_label('edit', 'Edit') ?></a>
                                
                                @if($obligation->status !== 'completed')
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= get_label('actions', 'Actions') ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="markAsCompleted({{ $obligation->id }})">
                                                <?= get_label('mark_completed', 'Mark as Completed') ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#complianceModal">
                                                <?= get_label('update_compliance', 'Update Compliance') ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Updating Compliance -->
    <div class="modal fade" id="complianceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= get_label('update_compliance_status', 'Update Compliance Status') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="complianceForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"><?= get_label('compliance_status', 'Compliance Status') ?></label>
                            <select class="form-select" id="compliance_status" name="compliance_status" required>
                                <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                                <option value="compliant" {{ $obligation->compliance_status == 'compliant' ? 'selected' : '' }}><?= get_label('compliant', 'Compliant') ?></option>
                                <option value="non_compliant" {{ $obligation->compliance_status == 'non_compliant' ? 'selected' : '' }}><?= get_label('non_compliant', 'Non-Compliant') ?></option>
                                <option value="partially_compliant" {{ $obligation->compliance_status == 'partially_compliant' ? 'selected' : '' }}><?= get_label('partially_compliant', 'Partially Compliant') ?></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= get_label('compliance_notes', 'Compliance Notes') ?></label>
                            <textarea class="form-control" name="compliance_notes" rows="3">{{ $obligation->compliance_notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                        <button type="submit" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-point {
            position: absolute;
            left: -20px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 2;
        }
        .timeline-content {
            padding-left: 20px;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .statisticsDiv {
            transition: all 0.3s ease;
        }
        .statisticsDiv:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        var obligation_labels = ['Status', 'Priority', 'Type'];
        var obligation_data = [1, 1, 1]; // We'll calculate actual counts
        var obligation_bg_colors = ['#007bff', '#28a745', '#ffc107'];
        
        // Function to handle obligation-specific query parameters
        function obligationQueryParams(params) {
            var userIds = $('#user_filter').val();
            var activities = $('#activity_filter').val();
            var dateFrom = $('#activity_log_between_date_from').val();
            var dateTo = $('#activity_log_between_date_to').val();
            var obligationId = $('#type_id').val();
            
            var queryParams = {
                user_ids: userIds,
                activities: activities,
                date_from: dateFrom,
                date_to: dateTo,
                type: 'contract_obligation',
                type_id: obligationId,
                search: params.search,
                sort: params.sort,
                order: params.order,
                offset: params.offset,
                limit: params.limit
            };
            
            return queryParams;
        }

        $(document).ready(function() {
            // Initialize multi-select dropdowns
            $('.users_select').select2({
                placeholder: '<?= get_label('select_actioned_by_users', 'Select Actioned By Users') ?>',
                allowClear: true
            });
            
            $('.js-example-basic-multiple').select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true
            });
            
            // Initialize date range picker
            if(typeof $.fn.daterangepicker !== 'undefined') {
                $('#activity_log_between_date').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'YYYY-MM-DD'
                    }
                });
                
                $('#activity_log_between_date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                    $('#activity_log_between_date_from').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#activity_log_between_date_to').val(picker.endDate.format('YYYY-MM-DD'));
                    $('#obligation_activity_log_table').bootstrapTable('refresh');
                });
                
                $('#activity_log_between_date').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#activity_log_between_date_from').val('');
                    $('#activity_log_between_date_to').val('');
                    $('#obligation_activity_log_table').bootstrapTable('refresh');
                });
            }
            
            // Add change event handlers for filters
            $('#user_filter, #activity_filter').on('change', function() {
                $('#obligation_activity_log_table').bootstrapTable('refresh');
            });
            
            // Initialize ApexCharts for obligation statistics
            var obligationOptions = {
                series: [{
                    name: 'Obligations',
                    data: obligation_data
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: obligation_labels,
                },
                yaxis: {
                    title: {
                        text: 'Count'
                    }
                },
                fill: {
                    colors: obligation_bg_colors,
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " Items"
                        }
                    }
                }
            };
            
            if(document.querySelector("#obligationStatisticsChart")) {
                var obligationChart = new ApexCharts(document.querySelector("#obligationStatisticsChart"), obligationOptions);
                obligationChart.render();
            }
            
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
                    
            // Handle archive/unarchive actions
            $(document).on('click', '.archive-obligation', function() {
                var obligationId = $(this).data('id');
                if (confirm('<?= get_label('confirm_archive_obligation', 'Are you sure you want to archive this obligation?') ?>')) {
                    $.ajax({
                        url: '/contract-obligations/' + obligationId + '/archive',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (!response.error) {
                                toastr.success('<?= get_label('obligation_archived_success', 'Obligation archived successfully') ?>');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                        }
                    });
                }
            });

            // Handle delete action
            $(document).on('click', '.delete', function() {
                var obligationId = $(this).data('id');
                var obligationType = $(this).data('type');
                
                if (confirm('<?= get_label('confirm_delete_obligation', 'Are you sure you want to delete this obligation?') ?>')) {
                    $.ajax({
                        url: '{{ url('contract-obligations/destroy/') }}' + obligationId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (!response.error) {
                                toastr.success('<?= get_label('obligation_deleted_success', 'Obligation deleted successfully') ?>');
                                setTimeout(() => window.location.href = '{{ route('contract-obligations.index') }}', 1500);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                        }
                    });
                }
            });

            // Add smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 80
                    }, 1000);
                }
            });

            // Add animation to cards on scroll
            $(window).on('scroll', function() {
                $('.card').each(function() {
                    var elementTop = $(this).offset().top;
                    var elementBottom = elementTop + $(this).outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();
                    
                    if (elementBottom > viewportTop && elementTop < viewportBottom) {
                        $(this).addClass('animate__animated animate__fadeInUp');
                    }
                });
            });

            // Trigger initial scroll check
            $(window).trigger('scroll');
            
            // Add hover effects to cards
            $('.card').addClass('card-hover');
            
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Add animation to timeline items
            $('.timeline-item').each(function(index) {
                $(this).delay(index * 200).queue(function() {
                    $(this).addClass('animate__animated animate__fadeInLeft');
                    $(this).dequeue();
                });
            });
            
            // Smooth scroll to sections
            $('.scroll-to-section').on('click', function(e) {
                e.preventDefault();
                var target = $($(this).attr('href'));
                if (target.length) {
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });
            
            // Print obligation details
            $('#print-obligation').on('click', function() {
                window.print();
            });
        });
        
        function markAsCompleted(id) {
            if (confirm('<?= get_label('confirm_mark_completed', 'Are you sure you want to mark this obligation as completed?') ?>')) {
                $.ajax({
                    url: `/contract-obligations/${id}/mark-completed`,
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (!response.error) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                    }
                });
            }
        }
        
        $('#complianceForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                '_token': '{{ csrf_token() }}',
                'compliance_status': $('#compliance_status').val(),
                'compliance_notes': $('textarea[name="compliance_notes"]').val()
            };
            
            $.ajax({
                url: `/contract-obligations/{{ $obligation->id }}/update-compliance`,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (!response.error) {
                        toastr.success(response.message);
                        $('#complianceModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                }
            });
        });
    </script>
    @endpush
@endsection