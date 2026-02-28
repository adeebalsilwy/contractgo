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
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_contract_modal" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i>
                <?= get_label('create_contract', 'Create Contract') ?>
            </a>
            <a href="{{url('contracts/contract-types')}}" class="btn btn-outline-primary">
                <i class="bx bx-list-ul me-1"></i>
                <?= get_label('contract_types', 'Contract Types') ?>
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
        },
        
        // Load statistics data
        loadStats: function() {
            // Get stats from the table data or set default values
            const tableData = $('#contracts_table').bootstrapTable('getData');
            if (tableData && tableData.length > 0) {
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
                },
                onLoadError: (status, res) => {
                    toastr.error('{{ get_label('error_loading_data', 'Error loading data') }}');
                },
                onPageChange: () => {
                    this.updateTableInfo();
                },
                onSort: () => {
                    this.updateTableInfo();
                }
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
<script src="{{asset('assets/js/pages/contracts-enhanced.js')}}"></script>
<script src="{{asset('assets/js/pages/contracts.js')}}"></script>
@endsection
