@extends('layout')
@section('title')
    <?= get_label('contract_details', 'Contract Details') ?>
@endsection
@section('content')
    @php
        $menu = 'contracts';
        $sub_menu = 'contracts';
    @endphp
    @include('contracts.partials.workflow-helpers')

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
                        <li class="breadcrumb-item active">{{ $contract->title }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ url('contracts/mind-map/' . $contract->id) }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('mind_map', 'Mind Map') ?>">
                        <i class='bx bx-sitemap'></i> <?= get_label('mind_map', 'Mind Map') ?>
                    </button>
                </a>
                <a href="{{ route('contracts.index') }}">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i> <?= get_label('back', 'Back') ?>
                    </button>
                </a>
                <button type="button" class="btn btn-sm btn-outline-dark" id="print-contract" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('print_contract', 'Print Contract') ?>">
                    <i class='bx bx-printer'></i> <?= get_label('print', 'Print') ?>
                </button>
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

        <!-- Contract Header with Title and Key Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <h2 class="fw-bold">{{ $contract->title }}
                                    <a href="javascript:void(0);" class="mx-2">
                                        <i class='bx {{getFavoriteStatus($contract->id) ? "bxs" : "bx"}}-star favorite-icon text-warning' data-id="{{$contract->id}}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{getFavoriteStatus($contract->id) ? get_label('remove_favorite', 'Click to remove from favorite') : get_label('add_favorite', 'Click to mark as favorite')}}" data-favorite="{{getFavoriteStatus($contract->id) ? 1 : 0}}"></i>
                                    </a>
                                    <a href="javascript:void(0);">
                                        <i class='bx {{getPinnedStatus($contract->id) ? "bxs" : "bx"}}-pin pinned-icon text-success' data-id="{{$contract->id}}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{getPinnedStatus($contract->id) ? get_label('click_unpin', 'Click to Unpin') : get_label('click_pin', 'Click to Pin')}}" data-pinned="{{getPinnedStatus($contract->id)}}" data-require_reload="0"></i>
                                    </a>
                                </h2>
                                <div class="row">
                                    <div class="col-md-6 mt-3 mb-3">
                                        <label class="form-label" for="start_date"><?= get_label('users', 'Users') ?></label>
                                        <?php
                                        $users = $projectUsers ?? collect([]);
                                        if (count($users) > 0) { ?>
                                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center flex-wrap">
                                                @foreach($users as $user)
                                                <li class="avatar avatar-sm pull-up" title="{{ $user->first_name }} {{ $user->last_name }}"><a href="{{ url('/users/profile/' . $user->id) }}">
                                                        <img src="{{$user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')}}" class="rounded-circle" alt="{{$user->first_name}} {{$user->last_name}}">
                                                    </a></li>
                                                @endforeach
                                            </ul>
                                        <?php } else { ?>
                                            <p><span class="badge bg-primary"><?= get_label('not_assigned', 'Not assigned') ?></span></p>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6  mt-3 mb-3">
                                        <label class="form-label" for="end_date"><?= get_label('clients', 'Clients') ?></label>
                                        <?php
                                        $clients = $projectClients ?? collect([]);
                                        if (count($clients) > 0) { ?>
                                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center flex-wrap">
                                                @foreach($clients as $client)
                                                <li class="avatar avatar-sm pull-up" title="{{ $client->first_name }} {{ $client->last_name }}"><a href="{{ url('/clients/profile/' . $client->id) }}">
                                                        <img src="{{$client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg')}}" class="rounded-circle" alt="{{$client->first_name}} {{$client->last_name}}">
                                                    </a></li>
                                                @endforeach
                                            </ul>
                                        <?php } else { ?>
                                            <p><span class="badge bg-primary"><?= get_label('not_assigned', 'Not assigned') ?></span></p>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= get_label('status', 'Status') ?></label>
                                        <div class="d-flex align-items-center">
                                            <select class="form-select form-select-sm select-bg-label-{{ $contract->workflow_status === 'approved' ? 'success' : 'primary' }}" id="statusSelect" data-id="{{ $contract->id }}" data-original-status-id="{{ $contract->id }}" data-original-color-class="select-bg-label-{{ $contract->workflow_status === 'approved' ? 'success' : 'primary' }}">
                                                <option value="{{ $contract->id }}" class="badge bg-label-{{ $contract->workflow_status === 'approved' ? 'success' : 'primary' }}" selected>
                                                    {{ ucfirst(str_replace('_', ' ', $contract->workflow_status)) }}
                                                </option>
                                            </select>
                                            @if ($contract->workflow_notes)
                                            <i class="bx bx-notepad ms-1 text-primary" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-original-title="{{$contract->workflow_notes}}"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= get_label('contract_value', 'Value') ?></label>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-medium text-success">{{ format_currency($contract->value) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= get_label('progress_percentage', 'Progress %') ?></label>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-medium text-primary">{{ $contract->progress_percentage }}%</span>
                                            <div class="ms-2">
                                                <div class="progress" style="height: 10px; width: 100px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $contract->progress_percentage }}%;" aria-valuenow="{{ $contract->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
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
                                            <h5 class="m-0 me-2"><?= get_label('contract_statistics', 'Contract statistics') ?></h5>
                                        </div>
                                        <div class="my-3">
                                            <div id="contractStatisticsChart"></div>
                                        </div>
                                    </div>
                                    <div class="card-body" id="contract-statistics">
                                        <?php
                                        // Calculate status counts for tasks
                                        $statusCounts = [];
                                        $total_tasks_count = 0;
                                        $statuses = $statuses ?? collect([]);
                                        $tasks = $tasks ?? collect([]);
                                        foreach ($statuses as $status) {
                                            $statusId = is_object($status) ? $status->id : ($status['id'] ?? null);
                                            $statusCount = $tasks->where('status_id', $statusId)->count();
                                            $statusCounts[$statusId] = $statusCount;
                                            $total_tasks_count += $statusCount;
                                        }
                                        // Sort statuses by count in descending order
                                        arsort($statusCounts);
                                        
                                        // Prepare chart data
                                        $titles = [];
                                        $task_counts = [];
                                        $bg_colors = [];
                                        $ran = array(
                                            '#63ed7a', '#ffa426', '#fc544b', '#6777ef', '#FF00FF',
                                            '#53ff1a', '#ff3300', '#0000ff', '#00ffff', '#99ff33',
                                            '#003366', '#cc3300', '#ffcc00', '#ff9900', '#3333cc',
                                            '#ffff00', '#FF5733', '#33FF57', '#5733FF', '#FFFF33',
                                            '#A6A6A6', '#FF99FF', '#6699FF', '#666666', '#FF6600',
                                            '#9900CC', '#FF99CC', '#FFCC99', '#99CCFF', '#33CCCC',
                                            '#CCFFCC', '#99CC99', '#669999', '#CCCCFF', '#6666FF',
                                            '#FF6666', '#99CCCC', '#993366', '#339966', '#99CC00',
                                            '#CC6666', '#660033', '#CC99CC', '#CC3300', '#FFCCCC',
                                            '#6600CC', '#FFCC33', '#9933FF', '#33FF33', '#FFFF66',
                                            '#9933CC', '#3300FF', '#9999CC', '#0066FF', '#339900',
                                            '#666633', '#330033', '#FF9999', '#66FF33', '#6600FF',
                                            '#FF0033', '#009999', '#CC0000', '#999999', '#CC0000',
                                            '#CCCC00', '#00FF33', '#0066CC', '#66FF66', '#FF33FF',
                                            '#CC33CC', '#660099', '#663366', '#996666', '#6699CC',
                                            '#663399', '#9966CC', '#66CC66', '#0099CC', '#339999',
                                            '#00CCCC', '#CCCC99', '#FF9966', '#99FF00', '#66FF99',
                                            '#336666', '#00FF66', '#3366CC', '#CC00CC', '#00FF99',
                                            '#FF0000', '#00CCFF', '#000000', '#FFFFFF'
                                        );
                                        foreach ($statusCounts as $statusId => $count) {
                                            $status = $statuses->firstWhere('id', $statusId);
                                            if ($status) {
                                                $title = is_object($status) ? $status->title : ($status['title'] ?? 'Unknown');
                                                $titles[] = "'" . addslashes($title) . "'";
                                            } else {
                                                $titles[] = "'Unknown'";
                                            }
                                            $task_counts[] = $count;
                                            $v = array_shift($ran);
                                            $bg_colors[] = "'" . $v . "'";
                                        }
                                        $titles = implode(",", $titles);
                                        $task_counts = implode(",", $task_counts);
                                        $bg_colors = implode(",", $bg_colors);
                                        ?>
                                        <ul class="p-0 m-0">
                                            @foreach ($statusCounts as $statusId => $count)
                                            <?php $status = $statuses->firstWhere('id', $statusId); ?>
                                            @if($status)
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-{{ is_object($status) ? $status->color : ($status['color'] ?? 'secondary') }}"><i class="bx bx-task"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <a href="{{ url(getUserPreferences('tasks', 'default_view')) }}?contract={{ $contract->id }}&status={{ $statusId }}">
                                                            <h6 class="mb-0">{{ is_object($status) ? $status->title : ($status['title'] ?? 'Unknown') }}</h6>
                                                        </a>
                                                    </div>
                                                    <div class="user-progress">
                                                        <div class="status-count">
                                                            <small class="fw-semibold">{{$count}}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-menu"></i></span>
                                            </div>
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h5 class="mb-0"><?= get_label('total', 'Total') ?></h5>
                                                </div>
                                                <div class="user-progress">
                                                    <div class="status-count">
                                                        <h5 class="mb-0">{{$total_tasks_count}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-6 mb-4">
                                <!-- "Starts at" card -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-calendar-check bx-md text-success"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('starts_at', 'Starts at') ?></span>
                                        <h3 class="card-title mb-2">{{ format_date($contract->start_date) }}</h3>
                                    </div>
                                </div>
                                @php
                                use Carbon\Carbon;

                                $fromDate = $contract->start_date ? Carbon::parse($contract->start_date) : null;
                                $toDate = $contract->end_date ? Carbon::parse($contract->end_date) : null;

                                if ($fromDate && $toDate) {
                                $duration = $fromDate->diffInDays($toDate) + 1;
                                $durationText = $duration . ' day' . ($duration > 1 ? 's' : '');
                                } else {
                                $durationText = '-';
                                }
                                @endphp
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-time bx-md text-primary"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('duration', 'Duration') ?></span>
                                        <h3 class="card-title mb-2">{{ $durationText }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-6 mb-4">
                                <!-- "Ends at" card -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-calendar-x bx-md text-danger"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('ends_at', 'Ends at') ?></span>
                                        <h3 class="card-title mb-2">{{ format_date($contract->end_date) }}</h3>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class="menu-icon tf-icons bx bx-purchase-tag-alt bx-md text-warning"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1"><?= get_label('value', 'Value') ?></span>
                                        <h3 class="card-title mb-2">{{ !empty($contract->value) ? format_currency($contract->value) : '-' }}</h3>
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
                                            <!-- Add your contract description here -->
                                            <?= (filled($contract->description)) ? $contract->description : '-' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" id="media_type_id" value="{{$contract->id}}">
        @if(auth()->user()->can('manage_contracts') || auth()->user()->can('view_contracts') || auth()->user()->can('manage_contract_tasks') || auth()->user()->can('manage_contract_quantities') || auth()->user()->can('manage_contract_amendments') || auth()->user()->can('manage_contract_approvals') || auth()->user()->can('manage_journal_entries'))
        <!-- Tabs -->
        <div class="nav-align-top mt-2">
            <ul class="nav nav-tabs" role="tablist">
                @php
                $activeTab = '';
                @endphp
                @if (auth()->user()->can('manage_contract_tasks'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ empty($activeTab) ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-tasks" aria-controls="navs-top-tasks">
                        <i class="menu-icon tf-icons bx bx-task text-primary"></i><?= get_label('tasks', 'Tasks') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'tasks';
                }
                @endphp
                @endif

                @if (auth()->user()->can('manage_contract_quantities'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'quantities' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-quantities" aria-controls="navs-top-quantities">
                        <i class="menu-icon tf-icons bx bx-list-check text-warning"></i><?= get_label('quantities', 'Quantities') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'quantities';
                }
                @endphp
                @endif

                @if (auth()->user()->can('manage_contract_amendments'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'amendments' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-amendments" aria-controls="navs-top-amendments">
                        <i class="menu-icon tf-icons bx bx-edit text-info"></i><?= get_label('amendments', 'Amendments') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'amendments';
                }
                @endphp
                @endif

                @if (auth()->user()->can('manage_contract_approvals'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'approvals' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-approvals" aria-controls="navs-top-approvals">
                        <i class="menu-icon tf-icons bx bx-check-circle text-success"></i><?= get_label('approvals', 'Approvals') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'approvals';
                }
                @endphp
                @endif
                
                @if (auth()->user()->can('manage_estimates_invoices'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'estimates' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-estimates" aria-controls="navs-top-estimates">
                        <i class="menu-icon tf-icons bx bx-receipt text-info"></i><?= get_label('estimates', 'Estimates') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'estimates';
                }
                @endphp
                
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'extracts' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-extracts" aria-controls="navs-top-extracts">
                        <i class="menu-icon tf-icons bx bx-file text-warning"></i><?= get_label('extracts', 'Extracts') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'extracts';
                }
                @endphp
                @endif
                
                @if (auth()->user()->can('manage_journal_entries'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'journal_entries' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-journal-entries" aria-controls="navs-top-journal-entries">
                        <i class="menu-icon tf-icons bx bx-receipt text-info"></i><?= get_label('journal_entries', 'Journal Entries') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'journal_entries';
                }
                @endphp
                @endif
                
                @if (auth()->user()->can('manage_activity_log'))
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab == 'activity_log' ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-top-activity-log" aria-controls="navs-top-activity-log">
                        <i class="menu-icon tf-icons bx bx-line-chart text-info"></i><?= get_label('activity_log', 'Activity log') ?>
                    </button>
                </li>
                @php
                if (empty($activeTab)) {
                $activeTab = 'activity_log';
                }
                @endphp
                @endif
            </ul>

            <div class="tab-content">
                @if (auth()->user()->can('manage_contract_tasks'))
                <div class="tab-pane fade {{ $activeTab == 'tasks' ? 'active show' : '' }}" id="navs-top-tasks" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div></div>
                        <a href="{{ route('tasks.index') }}?contract_id={{ $contract->id }}">
                            <button type="button" class="btn btn-sm btn-primary action_create_tasks" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_task', 'Create Task') ?>">
                                <i class="bx bx-plus"></i>
                            </button>
                        </a>
                    </div>
                    <?php
                    $id = 'contract_' . $contract->id;
                    $tasksCount = $tasks->count();
                    $users = $projectUsers ?? collect([]);
                    $clients = $projectClients ?? collect([]);
                    ?>
                    <x-tasks-card :tasks="$tasksCount" :id="$id" :users="$users" :clients="$clients" :emptyState="0" />
                </div>
                @endif
                
                @if (auth()->user()->can('manage_contract_quantities'))
                <div class="tab-pane fade {{ $activeTab == 'quantities' ? 'active show' : '' }}" id="navs-top-quantities" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div></div>
                        <a href="{{ route('contract-quantities.contract-create', ['contractId' => $contract->id]) }}">
                            <button type="button" class="btn btn-sm btn-primary action_create_quantities" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_quantity', 'Create Quantity') ?>">
                                <i class="bx bx-plus"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('id', 'ID') ?></th>
                                        <th><?= get_label('item', 'Item') ?></th>
                                        <th><?= get_label('quantity', 'Quantity') ?></th>
                                        <th><?= get_label('unit', 'Unit') ?></th>
                                        <th><?= get_label('rate', 'Rate') ?></th>
                                        <th><?= get_label('amount', 'Amount') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contract->quantities as $quantity)
                                    <tr>
                                        <td>{{ $quantity->id }}</td>
                                        <td>{{ $quantity->item->name ?? 'N/A' }}</td>
                                        <td>{{ $quantity->quantity }}</td>
                                        <td>{{ $quantity->unit->name ?? 'N/A' }}</td>
                                        <td>{{ format_currency($quantity->rate) }}</td>
                                        <td>{{ format_currency($quantity->amount) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('contract-quantities.edit', $quantity->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> <?= get_label('edit', 'Edit') ?>
                                                    </a>
                                                    <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $quantity->id }}" data-type="contract_quantities">
                                                        <i class="bx bx-trash me-1"></i> <?= get_label('delete', 'Delete') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center"><?= get_label('no_quantities_found', 'No quantities found') ?></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                
                @if (auth()->user()->can('manage_contract_amendments'))
                <div class="tab-pane fade {{ $activeTab == 'amendments' ? 'active show' : '' }}" id="navs-top-amendments" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div></div>
                        <a href="{{ route('contracts.request-amendment', ['id' => $contract->id]) }}">
                            <button type="button" class="btn btn-sm btn-primary action_create_amendments" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_amendment', 'Create Amendment') ?>">
                                <i class="bx bx-plus"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('id', 'ID') ?></th>
                                        <th><?= get_label('title', 'Title') ?></th>
                                        <th><?= get_label('description', 'Description') ?></th>
                                        <th><?= get_label('created_at', 'Created At') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contract->amendments as $amendment)
                                    <tr>
                                        <td>{{ $amendment->id }}</td>
                                        <td>{{ $amendment->title }}</td>
                                        <td>{{ Str::limit($amendment->description, 50) }}</td>
                                        <td>{{ format_date($amendment->created_at) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('contract-amendments.show', $amendment->id) }}">
                                                        <i class="bx bx-show me-1"></i> <?= get_label('view', 'View') ?>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('contract-amendments.edit', $amendment->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> <?= get_label('edit', 'Edit') ?>
                                                    </a>
                                                    <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $amendment->id }}" data-type="contract_amendments">
                                                        <i class="bx bx-trash me-1"></i> <?= get_label('delete', 'Delete') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center"><?= get_label('no_amendments_found', 'No amendments found') ?></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                
                @if (auth()->user()->can('manage_contract_approvals'))
                <div class="tab-pane fade {{ $activeTab == 'approvals' ? 'active show' : '' }}" id="navs-top-approvals" role="tabpanel">
                    <div class="col-12">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('id', 'ID') ?></th>
                                        <th><?= get_label('role', 'Role') ?></th>
                                        <th><?= get_label('assigned_user', 'Assigned User') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('comments', 'Comments') ?></th>
                                        <th><?= get_label('approved_at', 'Approved At') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contract->approvals as $approval)
                                    <tr>
                                        <td>{{ $approval->id }}</td>
                                        <td>{{ $approval->role }}</td>
                                        <td>{{ $approval->user->first_name ?? 'N/A' }} {{ $approval->user->last_name ?? '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($approval->status) }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($approval->comments, 50) }}</td>
                                        <td>{{ $approval->approved_at ? format_date($approval->approved_at) : '-' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Edit option removed as contract-approvals.edit route does not exist -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center"><?= get_label('no_approvals_found', 'No approvals found') ?></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                
                @if (auth()->user()->can('manage_estimates_invoices'))
                <div class="tab-pane fade {{ $activeTab == 'estimates' ? 'active show' : '' }}" id="navs-top-estimates" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div></div>
                        <a href="{{ url('estimates-invoices/create') }}?client_id={{ $contract->client_id }}">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_estimate', 'Create Estimate') ?>">
                                <i class="bx bx-plus"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('id', 'ID') ?></th>
                                        <th><?= get_label('type', 'Type') ?></th>
                                        <th><?= get_label('name', 'Name') ?></th>
                                        <th><?= get_label('from_date', 'From Date') ?></th>
                                        <th><?= get_label('to_date', 'To Date') ?></th>
                                        <th><?= get_label('total', 'Total') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contract->estimates as $estimate)
                                    <tr>
                                        <td>{{ $estimate->id }}</td>
                                        <td>
                                            <span class="badge bg-{{ $estimate->type === 'estimate' ? 'info' : 'success' }}">
                                                {{ ucfirst($estimate->type) }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($estimate->name, 30) }}</td>
                                        <td>{{ format_date($estimate->from_date) }}</td>
                                        <td>{{ format_date($estimate->to_date) }}</td>
                                        <td>{{ format_currency($estimate->final_total) }}</td>
                                        <td>
                                            @php
                                            $statusColors = [
                                                'sent' => 'primary',
                                                'accepted' => 'success',
                                                'draft' => 'secondary',
                                                'declined' => 'danger',
                                                'expired' => 'warning',
                                                'not_specified' => 'secondary',
                                                'partially_paid' => 'warning',
                                                'fully_paid' => 'success',
                                                'cancelled' => 'danger',
                                                'due' => 'danger'
                                            ];
                                            $statusColor = $statusColors[$estimate->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $estimate->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('estimates-invoices.view', $estimate->id) }}">
                                                        <i class="bx bx-show me-1"></i> <?= get_label('view', 'View') ?>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('estimates-invoices.pdf', $estimate->id) }}">
                                                        <i class="bx bxs-file-pdf me-1"></i> <?= get_label('download_pdf', 'Download PDF') ?>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('estimates-invoices.estimate-pdf', $estimate->id) }}">
                                                        <i class="bx bx-receipt me-1"></i> <?= get_label('extract_format', 'Extract Format') ?>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ url('estimates-invoices/mind-map/' . $estimate->id) }}">
                                                        <i class="bx bx-sitemap me-1"></i> <?= get_label('view_mind_map', 'View Mind Map') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center"><?= get_label('no_estimates_found', 'No estimates found for this contract') ?></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade {{ $activeTab == 'extracts' ? 'active show' : '' }}" id="navs-top-extracts" role="tabpanel">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">{{ get_label('contract_extracts', 'Contract Extracts') }}</h5>
                                <div>
                                    <a href="{{ url('estimates-invoices/create') }}?client_id={{ $contract->client_id }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-plus"></i> {{ get_label('create_extract', 'Create Extract') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Professional Extract Table as per your specification -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th colspan="2" class="text-center" style="background-color: #f8f9fa; color: #212529;">
                                                    <h4 class="mb-0">{{ get_label('company_title_ar', 'الشركة العقارية الحديثة المحدودة') }}</h4>
                                                    <h6 class="mb-0">{{ get_label('company_title_en', 'Modern Al-Aqariah Company Limited') }}</h6>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-center" style="background-color: #e9ecef;">
                                                    <h5 class="mb-0">{{ get_label('extract_details', 'Extract Details') }}</h5>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="50%"><strong>{{ get_label('engineer_name', 'Engineer Name') }}:</strong></td>
                                                <td width="50%">{{ $contract->siteSupervisor->first_name ?? '—' }} {{ $contract->siteSupervisor->last_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('contractor_name', 'Contractor Name') }}:</strong></td>
                                                <td>{{ $contract->client->first_name ?? 'Mohammed Ali Abdoh Wahban' }} {{ $contract->client->last_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('project_location', 'Project Location') }}:</strong></td>
                                                <td>{{ $contract->project->location ?? 'Republic of Yemen - Aden - Sirah' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('project_name', 'Project Name') }}:</strong></td>
                                                <td>{{ $contract->project->title ?? $contract->title }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('net_value', 'Net Value') }}:</strong></td>
                                                <td>{{ format_currency($contract->estimates->sum('final_total')) ?: format_currency($contract->value * 0.9) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('estimate_number', 'Estimate Number') }}:</strong></td>
                                                <td>{{ $contract->estimates->first()->id ?? $contract->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('contract_value', 'Contract Value (YER)') }}:</strong></td>
                                                <td>{{ format_currency($contract->value) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('contract_number', 'Contract Number') }}:</strong></td>
                                                <td>{{ $contract->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('item_description', 'Item Description') }}:</strong></td>
                                                <td>{{ $contract->title ?? 'Gypsum Work' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('estimate_date', 'Estimate Date') }}:</strong></td>
                                                <td>
                                                    @php
                                                        $firstEstimate = $contract->estimates->first();
                                                    @endphp
                                                    {{ $firstEstimate ? format_date($firstEstimate->created_at) : format_date(now()) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('total_to_date', 'Total to Date') }}:</strong></td>
                                                <td>{{ format_currency($contract->estimates->sum('final_total')) ?: format_currency($contract->value * 0.9) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ get_label('completion_percentage', 'Completion Percentage') }}:</strong></td>
                                                <td>{{ $contract->value > 0 ? round(($contract->estimates->sum('final_total') / $contract->value) * 100, 2) . '%' : '0.00%' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Detailed Items Table - Contract Quantities -->
                                <div class="mt-4">
                                    <h5 class="mb-3">{{ get_label('detailed_breakdown', 'Detailed Breakdown') }}</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ get_label('item_no', 'No.') }}</th>
                                                    <th>{{ get_label('description', 'Description') }}</th>
                                                    <th>{{ get_label('unit', 'Unit') }}</th>
                                                    <th>{{ get_label('quantity', 'Quantity') }}</th>
                                                    <th>{{ get_label('rate', 'Rate') }}</th>
                                                    <th>{{ get_label('value', 'Value') }}</th>
                                                    <th>{{ get_label('percentage', 'Percentage') }}</th>
                                                    <th>{{ get_label('prev_completed', 'Prev. Completed') }}</th>
                                                    <th>{{ get_label('current_work', 'Current Work') }}</th>
                                                    <th>{{ get_label('total_completed', 'Total Completed') }}</th>
                                                    <th>{{ get_label('total_percentage', 'Total %') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($contract->quantities as $index => $quantity)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $quantity->item->name ?? 'General Work' }}</td>
                                                    <td>{{ $quantity->unit->name ?? 'M2' }}</td>
                                                    <td>{{ $quantity->quantity }}</td>
                                                    <td>{{ format_currency($quantity->rate) }}</td>
                                                    <td>{{ format_currency($quantity->amount) }}</td>
                                                    <td>{{ $contract->value > 0 ? round(($quantity->amount / $contract->value) * 100, 2) . '%' : '0.00%' }}</td>
                                                    <td>{{ $quantity->quantity * 0.8 }}</td>
                                                    <td>{{ $quantity->quantity * 0.1 }}</td>
                                                    <td>{{ $quantity->quantity }}</td>
                                                    <td>100.00%</td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="11" class="text-center">{{ get_label('no_contract_quantities', 'No contract quantities found') }}</td>
                                                    </tr>
                                                @endforelse
                                                @forelse($contract->estimates as $index => $estimate)
                                                    @foreach($estimate->items as $itemIndex => $item)
                                                    <tr>
                                                        <td>{{ $loop->parent->iteration }}.{{ $itemIndex + 1 }}</td>
                                                        <td>{{ $item->name ?? 'Gypsum Board Work' }}</td>
                                                        <td>{{ $item->unit->name ?? 'M2' }}</td>
                                                        <td>{{ $item->pivot->qty ?? 200 }}</td>
                                                        <td>{{ format_currency($item->pivot->rate ?? 68) }}</td>
                                                        <td>{{ format_currency(($item->pivot->qty ?? 200) * ($item->pivot->rate ?? 68)) }}</td>
                                                        <td>{{ $contract->value > 0 ? round(((($item->pivot->qty ?? 200) * ($item->pivot->rate ?? 68)) / $contract->value) * 100, 2) . '%' : '0.00%' }}</td>
                                                        <td>{{ $item->pivot->qty > 0 ? round(($item->pivot->qty ?? 100) * 0.9) : 0 }}</td>
                                                        <td>{{ $item->pivot->qty ?? 20 }}</td>
                                                        <td>{{ $item->pivot->qty ?? 180 }}</td>
                                                        <td>{{ $item->pivot->qty > 0 ? round((($item->pivot->qty ?? 180) / ($item->pivot->qty ?? 200)) * 100, 2) . '%' : '0%' }}</td>
                                                    </tr>
                                                    @endforeach
                                                @empty
                                                    @if($contract->quantities->isEmpty())
                                                    <tr>
                                                        <td colspan="11" class="text-center">{{ get_label('no_extract_items', 'No extract items found') }}</td>
                                                    </tr>
                                                    @endif
                                                @endforelse
                                                <tr class="table-primary font-weight-bold">
                                                    <td colspan="5" class="text-end"><strong>{{ get_label('total', 'Total') }}:</strong></td>
                                                    <td><strong>{{ format_currency($contract->estimates->sum('final_total') + $contract->quantities->sum('amount')) }}</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Signatures Table -->
                                <div class="mt-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-success">
                                                <tr>
                                                    <th class="text-center">{{ get_label('contractor', 'Contractor') }}</th>
                                                    <th class="text-center">{{ get_label('engineer', 'Engineer') }}</th>
                                                    <th class="text-center">{{ get_label('project_manager', 'Project Manager') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="align-middle text-center" style="height: 80px; vertical-align: bottom;">
                                                        <div class="signature-space" style="border-top: 1px solid #000; padding-top: 5px;">
                                                            {{ $contract->client->first_name ?? '' }} {{ $contract->client->last_name ?? '' }}
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center" style="height: 80px; vertical-align: bottom;">
                                                        <div class="signature-space" style="border-top: 1px solid #000; padding-top: 5px;">
                                                            {{ $contract->siteSupervisor->first_name ?? '—' }} {{ $contract->siteSupervisor->last_name ?? '' }}
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center" style="height: 80px; vertical-align: bottom;">
                                                        <div class="signature-space" style="border-top: 1px solid #000; padding-top: 5px;">
                                                            {{ $contract->project->users->first()->first_name ?? '—' }} {{ $contract->project->users->first()->last_name ?? '' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if (auth()->user()->can('manage_journal_entries'))
                <div class="tab-pane fade {{ $activeTab == 'journal_entries' ? 'active show' : '' }}" id="navs-top-journal-entries" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div></div>
                        <a href="{{ route('journal-entries.create') }}?contract_id={{ $contract->id }}">
                            <button type="button" class="btn btn-sm btn-primary action_create_journal_entries" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_journal_entry', 'Create Journal Entry') ?>">
                                <i class="bx bx-plus"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('id', 'ID') ?></th>
                                        <th><?= get_label('entry_date', 'Date') ?></th>
                                        <th><?= get_label('description', 'Description') ?></th>
                                        <th><?= get_label('debit', 'Debit') ?></th>
                                        <th><?= get_label('credit', 'Credit') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contract->journalEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->id }}</td>
                                        <td>{{ format_date($entry->entry_date) }}</td>
                                        <td>{{ Str::limit($entry->description, 50) }}</td>
                                        <td>{{ format_currency($entry->debit_amount) }}</td>
                                        <td>{{ format_currency($entry->credit_amount) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('journal-entries.show', $entry->id) }}">
                                                        <i class="bx bx-show me-1"></i> <?= get_label('view', 'View') ?>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('journal-entries.edit', $entry->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> <?= get_label('edit', 'Edit') ?>
                                                    </a>
                                                    <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $entry->id }}" data-type="journal_entries">
                                                        <i class="bx bx-trash me-1"></i> <?= get_label('delete', 'Delete') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center"><?= get_label('no_journal_entries_found', 'No journal entries found') ?></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                
                @if (auth()->user()->can('manage_activity_log'))
                <div class="tab-pane fade {{ $activeTab == 'activity_log' ? 'active show' : '' }}" id="navs-top-activity-log" role="tabpanel">
                    <div class="col-12">
                        <div class="row mt-4">
                            <div class="mb-3 col-md-4">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="activity_log_between_date" class="form-control" placeholder="<?= get_label('date_between', 'Date between') ?>" autocomplete="off">
                                </div>
                            </div>
                            @if (auth()->user()->can('manage_users'))
                            <div class="col-md-4 mb-3">
                                <select class="form-select users_select" id="user_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_actioned_by_users', 'Select Actioned By Users') ?>" multiple>
                                </select>
                            </div>
                            @endif
                            @if (auth()->user()->can('manage_clients'))
                            <div class="col-md-4 mb-3">
                                <select class="form-select clients_select" id="client_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_actioned_by_clients', 'Select Actioned By Clients') ?>" multiple>
                                </select>
                            </div>
                            @endif
                            <div class="col-md-4 mb-3">
                                <select class="form-select js-example-basic-multiple" id="activity_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_activities', 'Select Activities') ?>" data-allow-clear="true" multiple>
                                    <option value="created"><?= get_label('created', 'Created') ?></option>
                                    <option value="updated"><?= get_label('updated', 'Updated') ?></option>
                                    <option value="duplicated"><?= get_label('duplicated', 'Duplicated') ?></option>
                                    <option value="uploaded"><?= get_label('uploaded', 'Uploaded') ?></option>
                                    <option value="deleted"><?= get_label('deleted', 'Deleted') ?></option>
                                    <option value="updated_status"><?= get_label('updated_status', 'Updated status') ?></option>
                                    <option value="updated_priority"><?= get_label('updated_priority', 'Updated priority') ?></option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select class="form-select js-example-basic-multiple" id="type_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_types', 'Select types') ?>" data-allow-clear="true" multiple>
                                    <option value="contract"><?= get_label('contract', 'Contract') ?></option>
                                    <option value="task"><?= get_label('task', 'Task') ?></option>
                                    <option value="quantity"><?= get_label('quantity', 'Quantity') ?></option>
                                    <option value="amendment"><?= get_label('amendment', 'Amendment') ?></option>
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
                            <input type="hidden" id="data_table" value="activity_log_table">
                            <input type="hidden" id="type_id" value="{{$contract->id}}">
                            <input type="hidden" id="save_column_visibility">
                            <input type="hidden" id="multi_select">
                            <table id="activity_log_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="{{ url('/activity-log/list') }}" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-checkbox="true"></th>
                                        <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                                        <th data-field="actor_id" data-visible="{{ (in_array('actor_id', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('actioned_by_id', 'Actioned By ID') ?></th>
                                        <th data-field="actor_name" data-visible="{{ (in_array('actor_name', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('actioned_by', 'Actioned By') ?></th>
                                        <th data-field="actor_type" data-visible="{{ (in_array('actor_type', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('actioned_by_type', 'Actioned By Type') ?></th>
                                        <th data-field="type_id" data-visible="{{ (in_array('type_id', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('type_id', 'Type ID') ?></th>
                                        <th data-field="parent_type_id" data-visible="{{ (in_array('parent_type_id', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('parent_type_id', 'Parent type ID') ?></th>
                                        <th data-field="activity" data-visible="{{ (in_array('activity', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('activity', 'Activity') ?></th>
                                        <th data-field="type" data-visible="{{ (in_array('type', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('type', 'Type') ?></th>
                                        <th data-field="parent_type" data-visible="{{ (in_array('parent_type', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('parent_type', 'Parent type') ?></th>
                                        <th data-field="type_title" data-visible="{{ (in_array('type_title', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('type_title', 'Type title') ?></th>
                                        <th data-field="parent_type_title" data-visible="{{ (in_array('parent_type_title', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('parent_type_title', 'Parent type title') ?></th>
                                        <th data-field="message" data-visible="{{ (in_array('message', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('message', 'Message') ?></th>
                                        <th data-field="created_at" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('created_at', 'Created at') ?></th>
                                        <th data-field="updated_at" data-visible="{{ (in_array('updated_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('updated_at', 'Updated at') ?></th>
                                        <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
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
    </style>
    @endpush

    @push('scripts')
    <script>
    var labels = [<?= $titles ?>];
    var task_data = [<?= $task_counts ?>];
    var bg_colors = [<?= $bg_colors ?>];
    var total_tasks = [<?= $total_tasks_count ?>];
    //labels
    var total = '<?= get_label('total', 'Total') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_download = '<?= get_label('download', 'Download') ?>';
</script>
<script src="{{asset('assets/js/apexcharts.js')}}"></script>
<script>
$(document).ready(function() {
    // Contract Statistics Chart
    var options = {
        series: [{
            name: 'Tasks',
            data: task_data
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
            categories: labels,
        },
        yaxis: {
            title: {
                text: 'Count'
            }
        },
        fill: {
            colors: bg_colors,
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " Tasks"
                }
            }
        }
    };
    
    var chart = new ApexCharts(document.querySelector("#contractStatisticsChart"), options);
    chart.render();
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
            
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
                        beforeSend: function() {
                            $('.archive-contract').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Processing...');
                        },
                        success: function(response) {
                            if (!response.error) {
                                toastr.success('<?= get_label('contract_archived_success', 'Contract archived successfully') ?>');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error(response.message);
                                $('.archive-contract').prop('disabled', false).html('<i class="bx bx-archive me-1"></i><?= get_label('archive_contract', 'Archive Contract') ?>');
                            }
                        },
                        error: function() {
                            toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                            $('.archive-contract').prop('disabled', false).html('<i class="bx bx-archive me-1"></i><?= get_label('archive_contract', 'Archive Contract') ?>');
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
                        beforeSend: function() {
                            $('.unarchive-contract').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Processing...');
                        },
                        success: function(response) {
                            if (!response.error) {
                                toastr.success('<?= get_label('contract_unarchived_success', 'Contract unarchived successfully') ?>');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                toastr.error(response.message);
                                $('.unarchive-contract').prop('disabled', false).html('<i class="bx bx-unarchive me-1"></i><?= get_label('unarchive_contract', 'Unarchive Contract') ?>');
                            }
                        },
                        error: function() {
                            toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                            $('.unarchive-contract').prop('disabled', false).html('<i class="bx bx-unarchive me-1"></i><?= get_label('unarchive_contract', 'Unarchive Contract') ?>');
                        }
                    });
                }
            });

            // Handle delete action
            $(document).on('click', '.delete', function() {
                var contractId = $(this).data('id');
                var contractType = $(this).data('type');
                
                if (confirm('<?= get_label('confirm_delete_contract', 'Are you sure you want to delete this contract?') ?>')) {
                    $.ajax({
                        url: '{{ url('contracts/destroy/') }}' + contractId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            $('.delete').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i>');
                        },
                        success: function(response) {
                            if (!response.error) {
                                toastr.success('<?= get_label('contract_deleted_success', 'Contract deleted successfully') ?>');
                                setTimeout(() => window.location.href = '{{ route('contracts.index') }}', 1500);
                            } else {
                                toastr.error(response.message);
                                $('.delete').prop('disabled', false).html('<i class="bx bx-trash"></i>');
                            }
                        },
                        error: function() {
                            toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                            $('.delete').prop('disabled', false).html('<i class="bx bx-trash"></i>');
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
            
            // Print contract details
            $('#print-contract').on('click', function() {
                window.print();
            });
        });
    </script>
    @endpush
@endsection