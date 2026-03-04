@extends('layout')
@section('title')
<?= get_label('contracts', 'Contracts') ?>
@endsection
@php
$visibleColumns = getUserPreferences('contracts');
@endphp
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('contracts', 'Contracts') ?>
                    </li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0">
                <i class="menu-icon tf-icons bx bx-file text-primary me-2"></i>
                <?= get_label('contracts_management', 'Contracts Management') ?>
            </h4>
            <p class="text-muted mb-0"><?= get_label('manage_all_contracts', 'Manage all your contracts efficiently') ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('/contracts/create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i>
                <?= get_label('create_contract', 'Create Contract') ?>
            </a>
            <a href="{{url('contracts/contract-types')}}" class="btn btn-outline-primary">
                <i class="bx bx-list-ul me-1"></i>
                <?= get_label('contract_types', 'Contract Types') ?>
            </a>
            <a href="{{ route('contract-quantities.create') }}" class="btn btn-outline-success">
                <i class="bx bx-calculator me-1"></i>
                <?= get_label('create_extract', 'Create Extract') ?>
            </a>
            <button type="button" class="btn btn-outline-secondary" id="refresh-contracts-data">
                <i class="bx bx-refresh"></i>
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4" id="contracts-stats">
        <div class="col-md-3">
            <div class="card border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bx bx-file bx-lg text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0" id="total-contracts">0</h3>
                            <small class="text-muted"><?= get_label('total_contracts', 'Total Contracts') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bx bx-check-circle bx-lg text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0" id="signed-contracts">0</h3>
                            <small class="text-muted"><?= get_label('signed_contracts', 'Signed Contracts') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bx bx-time bx-lg text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0" id="pending-contracts">0</h3>
                            <small class="text-muted"><?= get_label('pending_contracts', 'Pending Contracts') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-info border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bx bx-dollar bx-lg text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0" id="total-value">0</h3>
                            <small class="text-muted"><?= get_label('total_value', 'Total Value') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($contracts > 0)
    <!-- Advanced Filters Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bx bx-filter-alt me-2"></i>
                <?= get_label('advanced_filters', 'Advanced Filters') ?>
            </h5>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filters-collapse" aria-expanded="true" aria-controls="filters-collapse">
                <i class="bx bx-chevron-up"></i>
            </button>
        </div>
        <div class="collapse show" id="filters-collapse">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Date Filters -->
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('contract_date', 'Contract Date') ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                            <input type="text" class="form-control" id="contract_date_between" placeholder="<?= get_label('date_between', 'Date Between') ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('start_date', 'Start Date') ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-calendar-start"></i></span>
                            <input type="text" id="contract_start_date_between" class="form-control" placeholder="<?= get_label('starts_at_between', 'Starts at between') ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('end_date', 'End Date') ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-calendar-end"></i></span>
                            <input type="text" id="contract_end_date_between" class="form-control" placeholder="<?= get_label('ends_at_between', 'Ends at between') ?>" autocomplete="off">
                        </div>
                    </div>
                    
                    <!-- Selection Filters -->
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('projects', 'Projects') ?></label>
                        <select class="form-select projects_select" id="project_filter" data-placeholder="<?= get_label('select_projects', 'Select Projects') ?>" multiple>
                        </select>
                    </div>
                    
                    @if (!isClient() || isAdminOrHasAllDataAccess())
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('clients', 'Clients') ?></label>
                        <select class="form-select clients_select" id="client_filter" data-placeholder="<?= get_label('select_clients', 'Select Clients') ?>" multiple>
                        </select>
                    </div>
                    @endif
                    
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('contract_types', 'Contract Types') ?></label>
                        <select class="form-select contract_types_select" id="type_filter" data-placeholder="<?= get_label('select_types', 'Select Types') ?>" multiple>
                        </select>
                    </div>
                    
                    <!-- Status Filters -->
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('signature_status', 'Signature Status') ?></label>
                        <select class="form-select js-example-basic-multiple" id="status_filter" data-placeholder="<?= get_label('select_statuses', 'Select statuses') ?>" multiple>
                            <option value="signed"><?= get_label('signed', 'Signed') ?></option>
                            <option value="not_signed"><?= get_label('not_signed', 'Not signed') ?></option>
                            <option value="partially_signed"><?= get_label('partially_signed', 'Partially signed') ?></option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('workflow_status', 'Workflow Status') ?></label>
                        <select class="form-select js-example-basic-multiple" id="workflow_status_filter" data-placeholder="<?= get_label('select_workflow_status', 'Select workflow status') ?>" multiple>
                            <option value="draft"><?= get_label('draft', 'Draft') ?></option>
                            <option value="quantity_approval"><?= get_label('quantity_approval', 'Quantity Approval') ?></option>
                            <option value="management_review"><?= get_label('management_review', 'Management Review') ?></option>
                            <option value="accounting_processing"><?= get_label('accounting_review', 'Accounting Review') ?></option>
                            <option value="final_approval"><?= get_label('final_approval', 'Final Approval') ?></option>
                            <option value="approved"><?= get_label('approved', 'Approved') ?></option>
                            <option value="amendment_pending"><?= get_label('amendment_pending', 'Amendment Pending') ?></option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('archive_status', 'Archive Status') ?></label>
                        <select class="form-select" id="archived_filter" data-placeholder="<?= get_label('select_archived_status', 'Select archived status') ?>">
                            <option value=""><?= get_label('all', 'All') ?></option>
                            <option value="active"><?= get_label('active', 'Active') ?></option>
                            <option value="archived"><?= get_label('archived', 'Archived') ?></option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-outline-primary" id="apply-filters">
                                <i class="bx bx-filter me-1"></i>
                                <?= get_label('apply_filters', 'Apply Filters') ?>
                            </button>
                            <button type="button" class="btn btn-outline-secondary clear-contracts-filters">
                                <i class="bx bx-x me-1"></i>
                                <?= get_label('clear_filters', 'Clear Filters') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contracts Table Card -->
    <div class="card">
            <!-- Hidden Inputs -->
            <input type="hidden" id="contract_date_between_from">
            <input type="hidden" id="contract_date_between_to">
            <input type="hidden" name="start_date_from" id="contract_start_date_from">
            <input type="hidden" name="start_date_to" id="contract_start_date_to">
            <input type="hidden" name="end_date_from" id="contract_end_date_from">
            <input type="hidden" name="end_date_to" id="contract_end_date_to">
            <input type="hidden" name="workflow_status" id="workflow_status">
            <input type="hidden" name="is_archived" id="is_archived">
            
            <!-- Table Header -->
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0">
                    <i class="bx bx-table me-2"></i>
                    <?= get_label('contracts_list', 'Contracts List') ?>
                </h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="contracts-search" placeholder="<?= get_label('search_contracts', 'Search contracts...') ?>">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="export-contracts">
                        <i class="bx bx-export me-1"></i>
                        <?= get_label('export', 'Export') ?>
                    </button>
                </div>
            </div>
            
            <!-- Table -->
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="contracts">
                <input type="hidden" id="data_table" value="contracts_table">
                <input type="hidden" id="save_column_visibility">
                <input type="hidden" id="multi_select">
                
                <table id="contracts_table" 
                       class="table table-hover"
                       data-toggle="table"
                       data-loading-template="loadingTemplate"
                       data-url="{{ url('/contracts/list') }}"
                       data-icons-prefix="bx"
                       data-icons="icons"
                       data-show-refresh="false"
                       data-total-field="total"
                       data-trim-on-search="false"
                       data-data-field="rows"
                       data-page-list="[10, 25, 50, 100]"
                       data-search="false"
                       data-side-pagination="server"
                       data-show-columns="true"
                       data-pagination="true"
                       data-sort-name="id"
                       data-sort-order="desc"
                       data-mobile-responsive="true"
                       data-query-params="queryParams"
                       data-show-export="true"
                       data-export-types="['excel', 'csv', 'pdf']"
                       data-export-options='{"fileName": "contracts-report"}'>
                    <thead class="table-light">
                        <tr>
                            <th data-checkbox="true" data-width="40"></th>
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-formatter="idFormatter" data-width="80">
                                <?= get_label('id', 'ID') ?>
                            </th>
                            <th data-field="title" data-visible="{{ (in_array('title', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">
                                <?= get_label('title', 'Title') ?>
                            </th>
                            <th data-field="client" data-visible="{{ (in_array('client', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="false">
                                <?= get_label('client', 'Client') ?>
                            </th>
                            <th data-field="project" data-visible="{{ (in_array('project', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="false">
                                <?= get_label('project', 'Project') ?>
                            </th>
                            <th data-field="contract_type" data-visible="{{ (in_array('contract_type', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="false" data-width="120">
                                <?= get_label('type', 'Type') ?>
                            </th>
                            <th data-field="start_date" data-visible="{{ (in_array('start_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('starts_at', 'Starts at') ?>
                            </th>
                            <th data-field="end_date" data-visible="{{ (in_array('end_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('ends_at', 'Ends at') ?>
                            </th>
                            <th data-field="duration" data-visible="{{ (in_array('duration', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="false" data-width="100">
                                <?= get_label('duration', 'Duration') ?>
                            </th>
                            <th data-field="value" data-visible="{{ (in_array('value', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('value', 'Value') ?>
                            </th>
                            <th data-field="progress_percentage" data-visible="{{ (in_array('progress_percentage', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('progress_percentage', 'Progress %') ?>
                            </th>
                            <th data-field="promisor_sign" data-visible="{{ (in_array('promisor_sign', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('promisor_sign_status', 'Promisor sign status') ?>
                            </th>
                            <th data-field="promisee_sign" data-visible="{{ (in_array('promisee_sign', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('promisee_sign_status', 'Promisee sign status') ?>
                            </th>
                            <th data-field="status" data-visible="{{ (in_array('status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-width="100">
                                <?= get_label('status', 'Status') ?>
                            </th>
                            <th data-field="workflow_status" data-visible="{{ (in_array('workflow_status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="150">
                                <?= get_label('workflow_status', 'Workflow Status') ?>
                            </th>
                            <th data-field="quantities_count" data-visible="{{ (in_array('quantities_count', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="100">
                                <?= get_label('quantities_count', 'Quantities') ?>
                            </th>
                            <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-width="150" data-formatter="actionsFormatter">
                                <?= get_label('actions', 'Actions') ?>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @else
    <?php
    $type = 'Contracts'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<!-- Enhanced CSS for Action Icons -->
<style>
.action-buttons .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    padding: 0;
    margin: 0 2px;
}

.action-buttons .btn i {
    font-size: 16px;
    line-height: 1;
}

.action-buttons .btn:hover {
    transform: scale(1.1);
    transition: all 0.2s ease-in-out;
}

.action-buttons .btn-info {
    background-color: #03c3ec;
    border-color: #03c3ec;
}

.action-buttons .btn-warning {
    background-color: #ffab00;
    border-color: #ffab00;
}

.action-buttons .btn-secondary {
    background-color: #8592a3;
    border-color: #8592a3;
}

.action-buttons .btn-dark {
    background-color: #233446;
    border-color: #233446;
}

.action-buttons .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.action-buttons .btn-danger {
    background-color: #ff3e1d;
    border-color: #ff3e1d;
}

/* Ensure BoxIcons are loaded */
@font-face {
    font-family: 'boxicons';
    font-weight: normal;
    font-style: normal;
}
</style>

<!-- Enhanced JavaScript -->
<script>
    // Global variables
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    var label_view_details = '<?= get_label('view_details', 'View Details') ?>';
    var label_mind_map = '<?= get_label('mind_map', 'Mind Map') ?>';
    var label_contract_id_prefix = '<?= get_label('contract_id_prefix', 'CTR-') ?>';
    var label_loading = '<?= get_label('loading', 'Loading...') ?>';
    var label_please_wait = '<?= get_label('please_wait', 'Please wait...') ?>';
    var label_error_occurred = '<?= get_label('error_occurred', 'An error occurred') ?>';
    var label_success = '<?= get_label('success', 'Success') ?>';
    
    // Professional Data Fetching and Management
    const ContractsManager = {
        // Initialize the contracts manager
        init: function() {
            this.bindEvents();
            this.loadStats();
            this.initializeTable();
            this.initializeSelect2();
            this.initializeDatePickers();
        },
        
        // Bind all event listeners
        bindEvents: function() {
            // Refresh button
            $('#refresh-contracts-data').on('click', () => this.refreshData());
            
            // Apply filters button
            $('#apply-filters').on('click', () => this.applyFilters());
            
            // Search functionality
            let searchTimeout;
            $('#contracts-search').on('keyup', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    $('#contracts_table').bootstrapTable('refresh');
                }, 500);
            });
            
            // Export functionality
            $('#export-contracts').on('click', () => this.exportData());
            
            // Column visibility save
            $('#contracts_table').on('column-switch.bs.table', () => {
                this.saveColumnVisibility();
            });
            
            // Handle action button clicks
            $(document).on('click', '.edit-contract', function(e) {
                e.preventDefault();
                const contractId = $(this).data('id');
                // Handle edit contract logic
                console.log('Edit contract:', contractId);
            });
            
            $(document).on('click', '.duplicate', function(e) {
                e.preventDefault();
                const contractId = $(this).data('id');
                const contractTitle = $(this).data('title');
                // Handle duplicate contract logic
                console.log('Duplicate contract:', contractId, contractTitle);
            });
            
            $(document).on('click', '.archive-contract', function(e) {
                e.preventDefault();
                const contractId = $(this).data('id');
                // Handle archive contract logic
                console.log('Archive contract:', contractId);
            });
            
            $(document).on('click', '.unarchive-contract', function(e) {
                e.preventDefault();
                const contractId = $(this).data('id');
                // Handle unarchive contract logic
                console.log('Unarchive contract:', contractId);
            });
            
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                const contractId = $(this).data('id');
                // Handle delete contract logic
                console.log('Delete contract:', contractId);
            });
        },
        
        // Load statistics data
        loadStats: function() {
            try {
                // Check if bootstrap table exists and is initialized
                if (typeof $('#contracts_table').bootstrapTable === 'function') {
                    const tableData = $('#contracts_table').bootstrapTable('getData');
                    if (tableData && Array.isArray(tableData) && tableData.length > 0) {
                        $('#total-contracts').text(tableData.length);
                        $('#total-value').text(new Intl.NumberFormat().format(tableData.reduce((sum, item) => sum + (parseFloat(item.value) || 0), 0)));
                        // For signed and pending stats, we would need actual data from the API
                        $('#signed-contracts').text('0');
                        $('#pending-contracts').text('0');
                    } else {
                        // Default values if no data
                        $('#total-contracts').text('0');
                        $('#signed-contracts').text('0');
                        $('#pending-contracts').text('0');
                        $('#total-value').text('0');
                    }
                } else {
                    // Bootstrap table not initialized, set default values
                    $('#total-contracts').text('0');
                    $('#signed-contracts').text('0');
                    $('#pending-contracts').text('0');
                    $('#total-value').text('0');
                }
            } catch (error) {
                console.error('Error loading contract stats:', error);
                // Set default values on error
                $('#total-contracts').text('0');
                $('#signed-contracts').text('0');
                $('#pending-contracts').text('0');
                $('#total-value').text('0');
            }
            
            // Add animation effects
            $('.card').find('h3').each(function() {
                $(this).addClass('animate__animated animate__fadeInUp');
            });
        },
        
        // Initialize Bootstrap Table
        initializeTable: function() {
            $('#contracts_table').bootstrapTable({
                onLoadSuccess: (data) => {
                    this.updateTableInfo(data);
                    this.initializeTooltips();
                    this.initializeActionButtons();
                },
                onLoadError: (status, res) => {
                    toastr.error('{{ get_label('error_loading_data', 'Error loading data') }}');
                },
                onPageChange: () => {
                    this.updateTableInfo();
                    this.initializeTooltips();
                    this.initializeActionButtons();
                },
                onSort: () => {
                    this.updateTableInfo();
                    this.initializeTooltips();
                    this.initializeActionButtons();
                }
            });
        },
        
        // Initialize action buttons
        initializeActionButtons: function() {
            // Ensure action buttons have proper styling
            $('.action-buttons .btn').each(function() {
                $(this).addClass('d-flex align-items-center justify-content-center');
            });
            
            // Re-initialize tooltips for dynamically loaded content
            $('.action-buttons .btn').tooltip({
                trigger: 'hover',
                placement: 'top'
            });
        },
        
        // Initialize Select2 dropdowns
        initializeSelect2: function() {
            $('.projects_select').select2({
                placeholder: '{{ get_label('select_projects', 'Select Projects') }}',
                allowClear: true,
                ajax: {
                    url: '{{ url('/projects/search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: (params) => ({
                        q: params.term,
                        page: params.page || 1
                    }),
                    processResults: (data) => ({
                        results: data.items.map(item => ({
                            id: item.id,
                            text: item.title
                        }))
                    }),
                    cache: true
                }
            });
            
            $('.clients_select').select2({
                placeholder: '{{ get_label('select_clients', 'Select Clients') }}',
                allowClear: true,
                ajax: {
                    url: '{{ url('/clients/search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: (params) => ({
                        q: params.term,
                        page: params.page || 1
                    }),
                    processResults: (data) => ({
                        results: data.items.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    }),
                    cache: true
                }
            });
            
            $('.contract_types_select').select2({
                placeholder: '{{ get_label('select_types', 'Select Types') }}',
                allowClear: true,
                ajax: {
                    url: '{{ url('/contracts/contract-types/search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: (params) => ({
                        q: params.term,
                        page: params.page || 1
                    }),
                    processResults: (data) => ({
                        results: data.items.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    }),
                    cache: true
                }
            });
        },
        
        // Initialize date pickers with professional styling
        initializeDatePickers: function() {
            const dateOptions = {
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' to '
                },
                autoApply: false,
                showDropdowns: true,
                linkedCalendars: false,
                startDate: moment().subtract(1, 'year'),
                endDate: moment(),
                ranges: {
                    '{{ get_label('today', 'Today') }}': [moment(), moment()],
                    '{{ get_label('yesterday', 'Yesterday') }}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '{{ get_label('last_7_days', 'Last 7 Days') }}': [moment().subtract(6, 'days'), moment()],
                    '{{ get_label('last_30_days', 'Last 30 Days') }}': [moment().subtract(29, 'days'), moment()],
                    '{{ get_label('this_month', 'This Month') }}': [moment().startOf('month'), moment().endOf('month')],
                    '{{ get_label('last_month', 'Last Month') }}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            };
            
            $('#contract_date_between').daterangepicker(dateOptions);
            $('#contract_start_date_between').daterangepicker(dateOptions);
            $('#contract_end_date_between').daterangepicker(dateOptions);
        },
        
        // Refresh all data
        refreshData: function() {
            const refreshBtn = $('#refresh-contracts-data');
            const originalHtml = refreshBtn.html();
            
            refreshBtn.html('<i class="bx bx-loader-alt bx-spin"></i>').attr('disabled', true);
            
            // Refresh table
            $('#contracts_table').bootstrapTable('refresh');
            
            // Refresh stats
            this.loadStats();
            
            setTimeout(() => {
                refreshBtn.html(originalHtml).attr('disabled', false);
                toastr.success('{{ get_label('data_refreshed', 'Data refreshed successfully') }}');
            }, 1000);
        },
        
        // Apply filters
        applyFilters: function() {
            $('#contracts_table').bootstrapTable('refresh');
            toastr.success('{{ get_label('filters_applied', 'Filters applied successfully') }}');
        },
        
        // Export data
        exportData: function() {
            const exportBtn = $('#export-contracts');
            const originalHtml = exportBtn.html();
            
            exportBtn.html('<i class="bx bx-loader-alt bx-spin"></i>').attr('disabled', true);
            
            const params = this.getQueryParams({
                offset: 0,
                limit: 10000, // Export all data
                search: $('#contracts-search').val()
            });
            
            // Create export URL
            const exportUrl = '{{ url('/contracts/export') }}?' + $.param(params);
            
            // Download file
            window.location.href = exportUrl;
            
            setTimeout(() => {
                exportBtn.html(originalHtml).attr('disabled', false);
                toastr.success('{{ get_label('export_started', 'Export started') }}');
            }, 1000);
        },
        
        // Save column visibility
        saveColumnVisibility: function() {
            const visibleColumns = {};
            $('#contracts_table').bootstrapTable('getVisibleColumns').forEach(column => {
                if (column.field) {
                    visibleColumns[column.field] = column.visible;
                }
            });
            
            localStorage.setItem('contracts_column_visibility', JSON.stringify(visibleColumns));
        },
        
        // Update table information
        updateTableInfo: function(data) {
            const table = $('#contracts_table');
            const info = table.bootstrapTable('getOptions');
            
            if (data && data.total) {
                $('.card-title').append(` <span class="badge bg-primary ms-2">${data.total} {{ get_label('records', 'records') }}</span>`);
            }
        },
        
        // Initialize tooltips
        initializeTooltips: function() {
            $('[data-bs-toggle="tooltip"]').tooltip({
                trigger: 'hover'
            });
            
            // Initialize tooltips for action buttons
            $('.action-buttons .btn').tooltip({
                trigger: 'hover',
                placement: 'top'
            });
        },
        
        // Get query parameters for AJAX requests
        getQueryParams: function(params) {
            return {
                "statuses": $('#status_filter').val(),
                "workflow_statuses": $('#workflow_status_filter').val(),
                "is_archived": $('#archived_filter').val(),
                "client_ids": $('#client_filter').val(),
                "project_ids": $('#project_filter').val(),
                "type_ids": $('#type_filter').val(),
                "date_between_from": $('#contract_date_between_from').val(),
                "date_between_to": $('#contract_date_between_to').val(),
                "start_date_from": $('#contract_start_date_from').val(),
                "start_date_to": $('#contract_start_date_to').val(),
                "end_date_from": $('#contract_end_date_from').val(),
                "end_date_to": $('#contract_end_date_to').val(),
                page: params.page || 1,
                limit: params.limit || 10,
                sort: params.sort || 'id',
                order: params.order || 'desc',
                search: params.search || $('#contracts-search').val()
            };
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        ContractsManager.init();
    });
    
    // Make ContractsManager globally available for other scripts
    window.ContractsManager = ContractsManager;
</script>
<!-- Professional Contract Creation Modal -->
<div class="modal fade" id="create_contract_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-file me-2"></i>
                    <?= get_label('create_new_contract', 'Create New Contract') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create_contract_form" action="{{ url('/contracts/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Contract Information Section -->
                        <div class="col-md-12 mb-4">
                            <div class="card border-primary-subtle">
                                <div class="card-header bg-light-subtle">
                                    <h6 class="card-title text-primary mb-0">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <?= get_label('contract_information', 'Contract Information') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_title" class="form-label fw-medium">
                                                <?= get_label('title', 'Title') ?> <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="modal_title" name="title" required 
                                                   placeholder="<?= get_label('enter_contract_title', 'Enter contract title') ?>">
                                            <div class="invalid-feedback" id="title_error"></div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_value" class="form-label fw-medium">
                                                <?= get_label('contract_value', 'Contract Value') ?> <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <?= $general_settings['currency_symbol'] ?? '$' ?>
                                                </span>
                                                <input type="text" class="form-control" id="modal_value" name="value" required placeholder="0.00">
                                            </div>
                                            <div class="invalid-feedback" id="value_error"></div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_start_date" class="form-label fw-medium">
                                                <?= get_label('start_date', 'Start Date') ?> <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="modal_start_date" name="start_date" required>
                                            <div class="invalid-feedback" id="start_date_error"></div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_end_date" class="form-label fw-medium">
                                                <?= get_label('end_date', 'End Date') ?> <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="modal_end_date" name="end_date" required>
                                            <div class="invalid-feedback" id="end_date_error"></div>
                                        </div>
                                        
                                        <div class="col-md-12 mb-3">
                                            <label for="modal_description" class="form-label fw-medium">
                                                <?= get_label('description', 'Description') ?>
                                            </label>
                                            <textarea class="form-control" id="modal_description" name="description" rows="5" 
                                                      placeholder="<?= get_label('enter_contract_description', 'Enter contract description') ?>"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Client and Project Section -->
                        <div class="col-md-12 mb-4">
                            <div class="card border-primary-subtle">
                                <div class="card-header bg-light-subtle">
                                    <h6 class="card-title text-primary mb-0">
                                        <i class="bx bx-user me-2"></i>
                                        <?= get_label('client_project_info', 'Client & Project Information') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_client_id" class="form-label fw-medium">
                                                <?= get_label('client', 'Client') ?> <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <select class="form-select" id="modal_client_id" name="client_id" required 
                                                        data-placeholder="<?= get_label('select_client', 'Select Client') ?>">
                                                    <option value=""><?= get_label('select_client', 'Select Client') ?></option>
                                                </select>
                                                <button class="btn btn-outline-primary" type="button" 
                                                        onclick="window.open('{{ url('/clients/create') }}', '_blank')" 
                                                        title="<?= get_label('create_new_client', 'Create New Client') ?>">
                                                    <i class="bx bx-user-plus"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback" id="client_id_error"></div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_project_id" class="form-label fw-medium">
                                                <?= get_label('project', 'Project') ?> <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <select class="form-select" id="modal_project_id" name="project_id" required 
                                                        data-placeholder="<?= get_label('select_project', 'Select Project') ?>">
                                                    <option value=""><?= get_label('select_project', 'Select Project') ?></option>
                                                </select>
                                                <button class="btn btn-outline-primary" type="button" id="create_project_modal_btn" 
                                                        data-bs-toggle="modal" data-bs-target="#create_project_modal" 
                                                        title="<?= get_label('create_new_project', 'Create New Project') ?>">
                                                    <i class="bx bx-plus"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback" id="project_id_error"></div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_contract_type_id" class="form-label fw-medium">
                                                <?= get_label('contract_type', 'Contract Type') ?> <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="modal_contract_type_id" name="contract_type_id" required>
                                                <option value=""><?= get_label('select_contract_type', 'Select Contract Type') ?></option>
                                            </select>
                                            <div class="invalid-feedback" id="contract_type_id_error"></div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_profession_id" class="form-label fw-medium">
                                                <?= get_label('profession', 'Profession') ?>
                                            </label>
                                            <select class="form-select" id="modal_profession_id" name="profession_id">
                                                <option value=""><?= get_label('select_profession', 'Select Profession') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Workflow Assignment Section -->
                        <div class="col-md-12 mb-4">
                            <div class="card border-primary-subtle">
                                <div class="card-header bg-light-subtle">
                                    <h6 class="card-title text-primary mb-0">
                                        <i class="bx bx-sitemap me-2"></i>
                                        <?= get_label('workflow_assignments', 'Workflow Assignments') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">
                                        <?= get_label('workflow_assignments_description', 'Assign users to different workflow stages for contract approval process') ?>
                                    </p>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_site_supervisor_id" class="form-label fw-medium">
                                                <?= get_label('site_supervisor', 'Site Supervisor') ?>
                                            </label>
                                            <select class="form-select" id="modal_site_supervisor_id" name="site_supervisor_id" 
                                                    data-placeholder="<?= get_label('select_site_supervisor', 'Select Site Supervisor') ?>">
                                                <option value=""><?= get_label('select_site_supervisor', 'Select Site Supervisor') ?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_quantity_approver_id" class="form-label fw-medium">
                                                <?= get_label('quantity_approver', 'Quantity Approver') ?>
                                            </label>
                                            <select class="form-select" id="modal_quantity_approver_id" name="quantity_approver_id" 
                                                    data-placeholder="<?= get_label('select_quantity_approver', 'Select Quantity Approver') ?>">
                                                <option value=""><?= get_label('select_quantity_approver', 'Select Quantity Approver') ?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_accountant_id" class="form-label fw-medium">
                                                <?= get_label('accountant', 'Accountant') ?>
                                            </label>
                                            <select class="form-select" id="modal_accountant_id" name="accountant_id" 
                                                    data-placeholder="<?= get_label('select_accountant', 'Select Accountant') ?>">
                                                <option value=""><?= get_label('select_accountant', 'Select Accountant') ?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_reviewer_id" class="form-label fw-medium">
                                                <?= get_label('reviewer', 'Reviewer') ?>
                                            </label>
                                            <select class="form-select" id="modal_reviewer_id" name="reviewer_id" 
                                                    data-placeholder="<?= get_label('select_reviewer', 'Select Reviewer') ?>">
                                                <option value=""><?= get_label('select_reviewer', 'Select Reviewer') ?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="modal_final_approver_id" class="form-label fw-medium">
                                                <?= get_label('final_approver', 'Final Approver') ?>
                                            </label>
                                            <select class="form-select" id="modal_final_approver_id" name="final_approver_id" 
                                                    data-placeholder="<?= get_label('select_final_approver', 'Select Final Approver') ?>">
                                                <option value=""><?= get_label('select_final_approver', 'Select Final Approver') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Settings -->
                        <div class="col-md-12 mb-4">
                            <div class="card border-primary-subtle">
                                <div class="card-header bg-light-subtle">
                                    <h6 class="card-title text-primary mb-0">
                                        <i class="bx bx-cog me-2"></i>
                                        <?= get_label('additional_settings', 'Additional Settings') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="modal_extracts" class="form-label fw-medium">
                                                <?= get_label('link_existing_extracts', 'Link Existing Extracts') ?>
                                            </label>
                                            <select class="form-select" id="modal_extracts" name="extracts[]" multiple 
                                                    data-placeholder="<?= get_label('select_extracts_to_link', 'Select extracts to link to this contract') ?>">
                                            </select>
                                            <div class="form-text">
                                                <?= get_label('extracts_link_help', 'Select existing extracts that should be linked to this contract. You can also create new extracts after contract creation.') ?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="modal_auto_create_project" name="auto_create_project" value="1">
                                                <label class="form-check-label fw-medium" for="modal_auto_create_project">
                                                    <?= get_label('auto_create_project', 'Auto-create Project') ?>
                                                </label>
                                                <div class="form-text">
                                                    <?= get_label('auto_create_project_help', 'Automatically create a project with contract details') ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="modal_auto_start_workflow" name="auto_start_workflow" value="1">
                                                <label class="form-check-label fw-medium" for="modal_auto_start_workflow">
                                                    <?= get_label('auto_start_workflow', 'Auto-start Workflow') ?>
                                                </label>
                                                <div class="form-text">
                                                    <?= get_label('auto_start_workflow_help', 'Automatically start the workflow process after contract creation') ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="modal_require_signatures" name="require_signatures" value="1" checked>
                                                <label class="form-check-label fw-medium" for="modal_require_signatures">
                                                    <?= get_label('require_signatures', 'Require Signatures') ?>
                                                </label>
                                                <div class="form-text">
                                                    <?= get_label('require_signatures_help', 'Require both parties to sign the contract') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>
                        <?= get_label('cancel', 'Cancel') ?>
                    </button>
                    <button type="submit" class="btn btn-primary" id="modal_submit_btn">
                        <i class="bx bx-save me-1"></i>
                        <?= get_label('create_contract', 'Create Contract') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Project Modal (for quick project creation) -->
<div class="modal fade" id="create_project_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bx bx-plus-circle me-2"></i>
                    <?= get_label('create_new_project', 'Create New Project') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create_project_form">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="project_modal_title" class="form-label fw-medium">
                                <?= get_label('title', 'Title') ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="project_modal_title" name="title" required 
                                   placeholder="<?= get_label('enter_project_title', 'Enter project title') ?>">
                            <div class="invalid-feedback" id="project_title_error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="project_modal_client_id" class="form-label fw-medium">
                                <?= get_label('client', 'Client') ?> <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="project_modal_client_id" name="client_id" required>
                                <option value=""><?= get_label('select_client', 'Select Client') ?></option>
                            </select>
                            <div class="invalid-feedback" id="project_client_id_error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="project_modal_start_date" class="form-label fw-medium">
                                <?= get_label('start_date', 'Start Date') ?>
                            </label>
                            <input type="date" class="form-control" id="project_modal_start_date" name="start_date">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="project_modal_end_date" class="form-label fw-medium">
                                <?= get_label('end_date', 'End Date') ?>
                            </label>
                            <input type="date" class="form-control" id="project_modal_end_date" name="end_date">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="project_modal_description" class="form-label fw-medium">
                                <?= get_label('description', 'Description') ?>
                            </label>
                            <textarea class="form-control" id="project_modal_description" name="description" rows="3" 
                                      placeholder="<?= get_label('enter_project_description', 'Enter project description') ?>"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>
                        <?= get_label('cancel', 'Cancel') ?>
                    </button>
                    <button type="submit" class="btn btn-success" id="create_project_modal_btn_submit">
                        <i class="bx bx-plus me-1"></i>
                        <?= get_label('create_project', 'Create Project') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{asset('assets/js/pages/contracts-enhanced.js')}}"></script>
<script>
// Fixed contracts routes for actions
function idFormatter(value, row, index) {
    return [
        '<a href="' + baseUrl + '/contracts/' + row.id + '" title="' + label_view_details + '">' + label_contract_id_prefix + row.id + '</a>'
    ];
}

function actionsFormatter(value, row, index) {
    console.log('actionsFormatter called for row:', row.id);
    let actions = [];
    
    // Add view details button
    actions.push('<a href="' + baseUrl + '/contracts/' + row.id + '" class="btn btn-sm btn-info" title="' + label_view_details + '"><i class="bx bx-show"></i></a>');
    
    // Add edit button if user has permission
    if (row.can_edit) {
        actions.push('<a href="javascript:void(0);" class="edit-contract btn btn-sm btn-warning mx-1" data-bs-toggle="modal" data-bs-target="#edit_contract_modal" data-id="' + row.id + '" title="' + label_update + '"><i class="bx bx-edit"></i></a>');
    }
    
    // Add duplicate button if user has permission to create
    if (row.can_create) {
        actions.push('<a href="javascript:void(0);" class="duplicate btn btn-sm btn-secondary mx-1" data-id="' + row.id + '" data-title="' + row.title + '" data-type="contracts" data-table="contracts_table" title="' + label_duplicate + '"><i class="bx bx-copy"></i></a>');
    }
    
    // Add archive/unarchive buttons based on workflow status
    if (row.workflow_status === 'approved' && !row.is_archived) {
        actions.push('<a href="javascript:void(0);" class="archive-contract btn btn-sm btn-dark mx-1" data-id="' + row.id + '" title="Archive Contract"><i class="bx bx-archive"></i></a>');
    } else if (row.is_archived) {
        actions.push('<a href="javascript:void(0);" class="unarchive-contract btn btn-sm btn-warning mx-1" data-id="' + row.id + '" title="Unarchive Contract"><i class="bx bx-unarchive"></i></a>');
    }
    
    // Add delete button if user has permission
    if (row.can_delete) {
        actions.push('<button title="' + label_delete + '" type="button" class="btn btn-sm btn-danger delete mx-1" data-id="' + row.id + '" data-type="contracts" data-table="contracts_table"><i class="bx bx-trash"></i></button>');
    }
    
    // Add mind map button - using proper route
    actions.push('<a href="' + baseUrl + '/contracts/' + row.id + '/mind-map" class="btn btn-sm btn-primary mx-1" title="' + label_mind_map + '"><i class="bx bx-sitemap"></i></a>');
    
    // Add generate PDF button - using proper route
    actions.push('<a href="' + baseUrl + '/contracts/' + row.id + '/generate-pdf" class="btn btn-sm btn-success mx-1" title="Generate PDF"><i class="bx bx-file"></i></a>');
    
    // Add generate contract PDF button - using proper route
    actions.push('<a href="' + baseUrl + '/contracts/' + row.id + '/generate-pdf" class="btn btn-sm btn-info mx-1" title="Download Contract PDF"><i class="bx bx-download"></i></a>');
    
    // Wrap all actions in a container for better styling
    return '<div class="action-buttons d-flex justify-content-center">' + actions.join('') + '</div>';
}
</script>
<script>
$(document).ready(function() {
    // Initialize all select2 dropdowns
    $('#create_contract_modal select').select2({
        placeholder: function() {
            return $(this).data('placeholder') || 'Select an option';
        },
        allowClear: true,
        width: '100%',
        dropdownParent: $('#create_contract_modal')
    });
    
    // Initialize project modal select2
    $('#create_project_modal select').select2({
        placeholder: function() {
            return $(this).data('placeholder') || 'Select an option';
        },
        allowClear: true,
        width: '100%',
        dropdownParent: $('#create_project_modal')
    });
    
    // Load dropdown data
    loadDropdownData();
    
    // Form submission handling
    $('#create_contract_form').on('submit', function(e) {
        e.preventDefault();
        submitContractForm();
    });
    
    // Project creation form
    $('#create_project_form').on('submit', function(e) {
        e.preventDefault();
        createProject();
    });
    
    // Currency formatting
    $('#modal_value').on('input', function() {
        var value = $(this).val().replace(/[^0-9.]/g, '');
        if (value) {
            var parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            $(this).val(parts.join('.'));
        }
    });
    
    // Date validation
    $('#modal_start_date, #modal_end_date').on('change', function() {
        validateDates();
    });
    
    // Auto-select client when project is selected
    $('#modal_project_id').on('change', function() {
        var selectedProject = $(this).find('option:selected');
        var clientId = selectedProject.data('client-id');
        if (clientId) {
            $('#modal_client_id').val(clientId).trigger('change');
        }
    });
    
    // Handle auto-create project checkbox
    $('#modal_auto_create_project').on('change', function() {
        var projectSelectGroup = $('#modal_project_id').closest('.input-group');
        var projectCreateBtn = $('#create_project_modal_btn');
        
        if ($(this).is(':checked')) {
            // Hide project selection and create button
            projectSelectGroup.find('select').prop('disabled', true).val('').trigger('change');
            projectCreateBtn.hide();
        } else {
            // Show project selection and create button
            projectSelectGroup.find('select').prop('disabled', false);
            projectCreateBtn.show();
        }
    });
    
    // Clear validation errors when modal is closed
    $('#create_contract_modal').on('hidden.bs.modal', function() {
        clearValidationErrors();
        $('#create_contract_form')[0].reset();
        $('#create_contract_form select').trigger('change');
    });
    
    // Clear project modal errors when closed
    $('#create_project_modal').on('hidden.bs.modal', function() {
        clearProjectErrors();
        $('#create_project_form')[0].reset();
        $('#create_project_form select').trigger('change');
    });
    
    // Functions
    function loadDropdownData() {
        // Load clients with better error handling
        $.ajax({
            url: '{{ url('/api/clients') }}',
            method: 'GET',
            data: { limit: 1000 },
            success: function(response) {
                if (response.data) {
                    var clientSelect = $('#modal_client_id, #project_modal_client_id');
                    clientSelect.empty().append('<option value="">{{ get_label('select_client', 'Select Client') }}</option>');
                    $.each(response.data, function(index, client) {
                        var clientText = client.first_name + ' ' + client.last_name;
                        if (client.company) clientText += ' (' + client.company + ')';
                        if (client.profession && client.profession.name) clientText += ' - ' + client.profession.name;
                        clientSelect.append('<option value="' + client.id + '" data-profession="' + (client.profession_id || '') + '">' + clientText + '</option>');
                    });
                    clientSelect.trigger('change');
                }
            },
            error: function() {
                toastr.error('{{ get_label('error_loading_clients', 'Error loading clients') }}');
            }
        });
        
        // Load projects with better error handling
        $.ajax({
            url: '{{ url('/api/projects') }}',
            method: 'GET',
            data: { limit: 1000 },
            success: function(response) {
                if (response.data) {
                    var projectSelect = $('#modal_project_id');
                    projectSelect.empty().append('<option value="">{{ get_label('select_project', 'Select Project') }}</option>');
                    $.each(response.data, function(index, project) {
                        projectSelect.append('<option value="' + project.id + '" data-client-id="' + (project.client_id || '') + '">' + project.title + '</option>');
                    });
                    projectSelect.trigger('change');
                }
            },
            error: function() {
                toastr.error('{{ get_label('error_loading_projects', 'Error loading projects') }}');
            }
        });
        
        // Load contract types with better error handling
        $.ajax({
            url: '{{ url('/contracts/contract-types') }}',
            method: 'GET',
            data: { limit: 1000 },
            success: function(response) {
                if (response.data) {
                    var typeSelect = $('#modal_contract_type_id');
                    typeSelect.empty().append('<option value="">{{ get_label('select_contract_type', 'Select Contract Type') }}</option>');
                    $.each(response.data, function(index, type) {
                        typeSelect.append('<option value="' + type.id + '">' + type.type + '</option>');
                    });
                    typeSelect.trigger('change');
                }
            },
            error: function() {
                toastr.error('{{ get_label('error_loading_contract_types', 'Error loading contract types') }}');
            }
        });
        
        // Load users for workflow assignments with better error handling
        $.ajax({
            url: '{{ url('/api/users') }}',
            method: 'GET',
            data: { limit: 1000 },
            success: function(response) {
                if (response.data) {
                    var userSelects = [
                        '#modal_site_supervisor_id',
                        '#modal_quantity_approver_id',
                        '#modal_accountant_id',
                        '#modal_reviewer_id',
                        '#modal_final_approver_id'
                    ];
                    
                    $.each(userSelects, function(index, selector) {
                        var selectElement = $(selector);
                        selectElement.empty().append('<option value="">{{ get_label('select_user', 'Select User') }}</option>
                        $.each(response.data, function(userIndex, user) {
                            var userText = user.first_name + ' ' + user.last_name;
                            selectElement.append('<option value="' + user.id + '">' + userText + '</option>');
                        });
                        selectElement.trigger('change');
                    });
                }
            },
            error: function() {
                toastr.error('{{ get_label('error_loading_users', 'Error loading users') }}');
            }
        });
        
        // Load extracts with better error handling
        $.ajax({
            url: '{{ url('/estimates-invoices/list') }}',
            method: 'GET',
            data: { limit: 1000 },
            success: function(response) {
                if (response.rows) {
                    var extractSelect = $('#modal_extracts');
                    extractSelect.empty();
                    $.each(response.rows, function(index, extract) {
                        var extractText = extract.name + ' (' + extract.type + ') - ' + extract.final_total;
                        extractSelect.append('<option value="' + extract.id + '">' + extractText + '</option>');
                    });
                    extractSelect.trigger('change');
                }
            },
            error: function() {
                toastr.error('{{ get_label('error_loading_extracts', 'Error loading extracts') }}');
            }
        });
        
        // Load professions with better error handling
        $.ajax({
            url: '{{ url('/professions/list') }}',
            method: 'GET',
            data: { limit: 1000 },
            success: function(response) {
                if (response.rows) {
                    var professionSelect = $('#modal_profession_id');
                    professionSelect.empty().append('<option value="">{{ get_label('select_profession', 'Select Profession') }}</option>');
                    $.each(response.rows, function(index, profession) {
                        professionSelect.append('<option value="' + profession.id + '">' + profession.name + '</option>');
                    });
                    professionSelect.trigger('change');
                }
            },
            error: function() {
                toastr.error('{{ get_label('error_loading_professions', 'Error loading professions') }}');
            }
        });
    }
    
    function submitContractForm() {
        var submitBtn = $('#modal_submit_btn');
        var originalHtml = submitBtn.html();
        var autoCreateProject = $('#modal_auto_create_project').is(':checked');
        var formData = $('#create_contract_form').serialize();
        
        // Add auto-create project flag to form data
        if (autoCreateProject) {
            formData += '&auto_create_project=1';
        }
        
        $.ajax({
            url: $('#create_contract_form').attr('action'),
            method: 'POST',
            data: formData,
            beforeSend: function() {
                submitBtn.html('<i class="bx bx-loader-alt bx-spin"></i> <?= get_label('creating', 'Creating') ?>...').attr('disabled', true);
                clearValidationErrors();
            },
            success: function(response) {
                if (!response.error) {
                    toastr.success('<?= get_label('contract_created_successfully', 'Contract created successfully') ?>');
                    
                    // Handle auto-create project if requested
                    if (autoCreateProject && response.project_id) {
                        toastr.success('<?= get_label('project_created_automatically', 'Project created automatically') ?>');
                    }
                    
                    $('#create_contract_modal').modal('hide');
                    $('#contracts_table').bootstrapTable('refresh');
                    ContractsManager.loadStats();
                } else {
                    toastr.error(response.message || '<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#' + field + '_error').text(messages[0]);
                        $('#modal_' + field).addClass('is-invalid');
                    });
                    toastr.error('<?= get_label('please_fix_validation_errors', 'Please fix the validation errors') ?>');
                } else {
                    toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                }
            },
            complete: function() {
                submitBtn.html(originalHtml).attr('disabled', false);
            }
        });
    }
    
    function createProject() {
        var submitBtn = $('#create_project_modal_btn_submit');
        var originalHtml = submitBtn.html();
        
        $.ajax({
            url: '{{ url('/projects/store') }}',
            method: 'POST',
            data: $('#create_project_form').serialize(),
            beforeSend: function() {
                submitBtn.html('<i class="bx bx-loader-alt bx-spin"></i> <?= get_label('creating', 'Creating') ?>...').attr('disabled', true);
                clearProjectErrors();
            },
            success: function(response) {
                if (!response.error) {
                    toastr.success('<?= get_label('project_created_successfully', 'Project created successfully') ?>');
                    $('#create_project_modal').modal('hide');
                    
                    // Add new project to dropdown
                    var newOption = new Option(response.data.title, response.data.id, true, true);
                    $('#modal_project_id').append(newOption).trigger('change');
                } else {
                    toastr.error(response.message || '<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#project_' + field + '_error').text(messages[0]);
                        $('#project_modal_' + field).addClass('is-invalid');
                    });
                    toastr.error('<?= get_label('please_fix_validation_errors', 'Please fix the validation errors') ?>');
                } else {
                    toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                }
            },
            complete: function() {
                submitBtn.html(originalHtml).attr('disabled', false);
            }
        });
    }
    
    function validateDates() {
        var startDate = new Date($('#modal_start_date').val());
        var endDate = new Date($('#modal_end_date').val());
        
        if (startDate && endDate && startDate > endDate) {
            toastr.error('<?= get_label('end_date_must_be_after_start_date', 'End date must be after start date') ?>');
            $('#modal_end_date').val('');
        }
    }
    
    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }
    
    function clearProjectErrors() {
        $('#create_project_modal .is-invalid').removeClass('is-invalid');
        $('#create_project_modal .invalid-feedback').text('');
    }
});
</script>
@endsection
