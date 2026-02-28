@extends('layout')
@section('title')
<?= get_label('etimates_invoices', 'Estimates/Invoices') ?>
@endsection
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
                        <?= get_label('etimates_invoices', 'Estimates/Invoices') ?>
                    </li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0">
                <i class="menu-icon tf-icons bx bx-receipt text-success me-2"></i>
                <?= get_label('estimates_invoices_management', 'Estimates & Invoices Management') ?>
            </h4>
            <p class="text-muted mb-0"><?= get_label('manage_all_estimates_invoices', 'Manage all your estimates and invoices efficiently') ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{url('estimates-invoices/create')}}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i>
                <?= get_label('create_estimate_invoice', 'Create Estimate/Invoice') ?>
            </a>
            <button type="button" class="btn btn-outline-secondary" id="refresh-estimates-invoices-data">
                <i class="bx bx-refresh"></i>
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4" id="estimates-invoices-stats">
        <div class="col-md-3">
            <div class="card border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bx bx-receipt bx-lg text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0" id="total-estimates">0</h3>
                            <small class="text-muted"><?= get_label('total_estimates', 'Total Estimates') ?></small>
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
                            <i class="bx bx-file bx-lg text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0" id="total-invoices">0</h3>
                            <small class="text-muted"><?= get_label('total_invoices', 'Total Invoices') ?></small>
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
                            <h3 class="mb-0" id="pending-documents">0</h3>
                            <small class="text-muted"><?= get_label('pending_documents', 'Pending Documents') ?></small>
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
                            <h3 class="mb-0" id="total-revenue">0</h3>
                            <small class="text-muted"><?= get_label('total_revenue', 'Total Revenue') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($estimates_invoices > 0)
    @php
    $visibleColumns = getUserPreferences('estimates_invoices');
    @endphp
    
    <!-- Status Overview Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-bar-chart-square me-2"></i>
                        <?= get_label('status_overview', 'Status Overview') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Estimates Status -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bx bx-receipt me-1"></i>
                                <?= get_label('estimates', 'Estimates') ?>
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                $possibleStatuses = ['sent', 'accepted', 'draft', 'declined', 'expired', 'not_specified'];
                                $totalEstimates = array_sum(array_map(fn($status) => getStatusCount($status, 'estimate'), $possibleStatuses));
                                @endphp
                                <button type="button" class="btn btn-outline-success status-badge" data-status="" data-type="estimate">
                                    {{ get_label('all','All') }}
                                    <span class="badge bg-success ms-1">{{ getStatusCount('', 'estimate') }}</span>
                                </button>
                                @foreach($possibleStatuses as $status)
                                @php
                                $count = getStatusCount($status, 'estimate');
                                $percentage = $totalEstimates > 0 ? round(($count / $totalEstimates) * 100, 1) : 0;
                                @endphp
                                <button type="button" class="btn btn-outline-{{ getStatusColor($status) }} status-badge" data-status="{{ $status }}" data-type="estimate">
                                    {{ get_label($status, ucfirst(str_replace('_', ' ', $status))) }}
                                    <span class="badge bg-{{ getStatusColor($status) }} ms-1">
                                        {{ $count }}
                                        @if($percentage > 0)
                                        <small class="ms-1">({{ $percentage }}%)</small>
                                        @endif
                                    </span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Invoices Status -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-success mb-3">
                                <i class="bx bx-file me-1"></i>
                                <?= get_label('invoices', 'Invoices') ?>
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                $possibleStatuses = ['partially_paid', 'fully_paid', 'draft', 'cancelled', 'due', 'not_specified'];
                                $totalInvoices = array_sum(array_map(fn($status) => getStatusCount($status, 'invoice'), $possibleStatuses));
                                @endphp
                                <button type="button" class="btn btn-outline-success status-badge" data-status="" data-type="invoice">
                                    {{ get_label('all','All') }}
                                    <span class="badge bg-success ms-1">{{ getStatusCount('', 'invoice') }}</span>
                                </button>
                                @foreach($possibleStatuses as $status)
                                @php
                                $count = getStatusCount($status, 'invoice');
                                $percentage = $totalInvoices > 0 ? round(($count / $totalInvoices) * 100, 1) : 0;
                                @endphp
                                <button type="button" class="btn btn-outline-{{ getStatusColor($status) }} status-badge" data-status="{{ $status }}" data-type="invoice">
                                    {{ get_label($status, ucfirst(str_replace('_', ' ', $status))) }}
                                    <span class="badge bg-{{ getStatusColor($status) }} ms-1">
                                        {{ $count }}
                                        @if($percentage > 0)
                                        <small class="ms-1">({{ $percentage }}%)</small>
                                        @endif
                                    </span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Card -->
    <div class="card">

        <!-- Enhanced Filters Section -->
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bx bx-filter-alt me-2"></i>
                    <?= get_label('advanced_filters', 'Advanced Filters') ?>
                </h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filters-collapse" aria-expanded="true" aria-controls="filters-collapse">
                    <i class="bx bx-chevron-up"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="filters-collapse">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Date Filters -->
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('document_date', 'Document Date') ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                            <input type="text" class="form-control" id="ie_date_between" placeholder="<?= get_label('date_between', 'Date Between') ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('from_date', 'From Date') ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-calendar-start"></i></span>
                            <input type="text" id="start_date_between" class="form-control" placeholder="<?= get_label('from_date_between', 'From date between') ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('to_date', 'To Date') ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-calendar-end"></i></span>
                            <input type="text" id="end_date_between" class="form-control" placeholder="<?= get_label('to_date_between', 'To date between') ?>" autocomplete="off">
                        </div>
                    </div>
                    
                    <!-- Type and Client Filters -->
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('document_type', 'Document Type') ?></label>
                        <select class="form-select" id="type_filter" data-placeholder="<?= get_label('select_types', 'Select types') ?>">
                            <option value=""><?= get_label('all_types', 'All Types') ?></option>
                            <option value="estimate"><?= get_label('estimates', 'Estimates') ?></option>
                            <option value="invoice"><?= get_label('invoices', 'Invoices') ?></option>
                        </select>
                    </div>
                    
                    @if (!isClient() || isAdminOrHasAllDataAccess())
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('clients', 'Clients') ?></label>
                        <select class="form-select clients_select" id="client_filter" data-placeholder="<?= get_label('select_clients', 'Select Clients') ?>" multiple>
                        </select>
                    </div>
                    @endif
                    
                    @if(isAdminOrHasAllDataAccess())
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('user_creators', 'User Creators') ?></label>
                        <select class="form-select users_select" id="user_creators_filter" data-placeholder="<?= get_label('select_user_creators', 'Select User Creators') ?>" multiple>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?= get_label('client_creators', 'Client Creators') ?></label>
                        <select class="form-select clients_select" id="client_creators_filter" data-placeholder="<?= get_label('select_client_creators', 'Select Client Creators') ?>" multiple>
                        </select>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-outline-primary" id="apply-filters">
                                <i class="bx bx-filter me-1"></i>
                                <?= get_label('apply_filters', 'Apply Filters') ?>
                            </button>
                            <button type="button" class="btn btn-outline-secondary clear-estimates-invoices-filters">
                                <i class="bx bx-x me-1"></i>
                                <?= get_label('clear_filters', 'Clear Filters') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- Hidden Inputs -->
            <input type="hidden" id="date_between_from">
            <input type="hidden" id="date_between_to">
            <input type="hidden" id="start_date_from">
            <input type="hidden" id="start_date_to">
            <input type="hidden" id="end_date_from">
            <input type="hidden" id="end_date_to">
            <input type="hidden" id="hidden_status">
            
            <!-- Table Header -->
            <div class="card-header d-flex justify-content-between align-items-center border-top">
                <h5 class="card-title mb-0">
                    <i class="bx bx-table me-2"></i>
                    <?= get_label('estimates_invoices_list', 'Estimates & Invoices List') ?>
                </h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="estimates-invoices-search" placeholder="<?= get_label('search_documents', 'Search documents...') ?>">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="export-estimates-invoices">
                        <i class="bx bx-export me-1"></i>
                        <?= get_label('export', 'Export') ?>
                    </button>
                </div>
            </div>
            
            <!-- Table -->
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="estimates-invoices">
                <input type="hidden" id="save_column_visibility">
                <input type="hidden" id="multi_select">
                <input type="hidden" id="data_reload" value="1">
                
                <table id="table" 
                       class="table table-hover"
                       data-toggle="table"
                       data-loading-template="loadingTemplate"
                       data-url="{{ url('/estimates-invoices/list') }}"
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
                       data-export-options='{"fileName": "estimates-invoices-report"}'>
                    <thead class="table-light">
                        <tr>
                            <th data-checkbox="true" data-width="40"></th>
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-formatter="idFormatter" data-width="80">
                                <?= get_label('id', 'ID') ?>
                            </th>
                            <th data-field="type" data-visible="{{ (in_array('type', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="100">
                                <?= get_label('type', 'Type') ?>
                            </th>
                            <th data-field="client" data-visible="{{ (in_array('client', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="false">
                                <?= get_label('client', 'Client') ?>
                            </th>
                            <th data-field="from_date" data-visible="{{ (in_array('from_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('from_date', 'From date') ?>
                            </th>
                            <th data-field="to_date" data-visible="{{ (in_array('to_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('to_date', 'To date') ?>
                            </th>
                            <th data-field="total" data-visible="{{ (in_array('total', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('sub_total', 'Sub total') ?>
                            </th>
                            <th data-field="tax_amount" data-visible="{{ (in_array('tax_amount', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="100">
                                <?= get_label('tax', 'Tax') ?>
                            </th>
                            <th data-field="final_total" data-visible="{{ (in_array('final_total', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('final_total', 'Final total') ?>
                            </th>
                            <th data-field="status" data-visible="{{ (in_array('status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('status', 'Status') ?>
                            </th>
                            <th data-field="created_at" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" data-width="120">
                                <?= get_label('created_at', 'Created at') ?>
                            </th>
                            <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-width="120">
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
    $type = 'Estimates/Invoices';
    $link = 'estimates-invoices/create';
    ?>
    <x-empty-state-card :type="$type" :link="$link" />
    @endif
</div>
<!-- Enhanced JavaScript -->
<script>
    // Global variables
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    var label_estimate_id_prefix = '<?= get_label('estimate_id_prefix', 'ESTMT-') ?>';
    var label_invoice_id_prefix = '<?= get_label('invoice_id_prefix', 'INVC-') ?>';
    var label_sent = '<?= get_label('sent', 'Sent') ?>';
    var label_accepted = '<?= get_label('accepted', 'Accepted') ?>';
    var label_partially_paid = '<?= get_label('partially_paid', 'Partially paid') ?>';
    var label_fully_paid = '<?= get_label('fully_paid', 'Fully paid') ?>';
    var label_draft = '<?= get_label('draft', 'Draft') ?>';
    var label_declined = '<?= get_label('declined', 'Declined') ?>';
    var label_expired = '<?= get_label('expired', 'Expired') ?>';
    var label_cancelled = '<?= get_label('cancelled', 'Cancelled') ?>';
    var label_due = '<?= get_label('due', 'Due') ?>';
    var label_loading = '<?= get_label('loading', 'Loading...') ?>';
    var label_please_wait = '<?= get_label('please_wait', 'Please wait...') ?>';
    var label_error_occurred = '<?= get_label('error_occurred', 'An error occurred') ?>';
    var label_success = '<?= get_label('success', 'Success') ?>';
    
    // Professional Data Fetching and Management
    const EstimatesInvoicesManager = {
        // Initialize the manager
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
            $('#refresh-estimates-invoices-data').on('click', () => this.refreshData());
            
            // Apply filters button
            $('#apply-filters').on('click', () => this.applyFilters());
            
            // Search functionality
            let searchTimeout;
            $('#estimates-invoices-search').on('keyup', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    $('#table').bootstrapTable('refresh');
                }, 500);
            });
            
            // Export functionality
            $('#export-estimates-invoices').on('click', () => this.exportData());
            
            // Status badge clicks
            $(document).on('click', '.status-badge', function(e) {
                const status = $(this).data('status');
                const type = $(this).data('type');
                $('#hidden_status').val(status);
                $('#type_filter').val(type).trigger('change');
                $('#table').bootstrapTable('refresh');
                
                // Update active state
                $('.status-badge').removeClass('active');
                $(this).addClass('active');
            });
            
            // Column visibility save
            $('#table').on('column-switch.bs.table', () => {
                this.saveColumnVisibility();
            });
        },
        
        // Load statistics data
        loadStats: function() {
            $.ajax({
                url: '{{ url('/estimates-invoices/stats') }}',
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    if (response.success) {
                        $('#total-estimates').text(response.data.estimates || 0);
                        $('#total-invoices').text(response.data.invoices || 0);
                        $('#pending-documents').text(response.data.pending || 0);
                        $('#total-revenue').text(new Intl.NumberFormat().format(response.data.total_revenue || 0));
                        
                        // Add animation effects
                        $('#estimates-invoices-stats').find('h3').each(function() {
                            $(this).addClass('animate__animated animate__fadeInUp');
                        });
                    }
                },
                error: (xhr) => {
                    console.error('Error loading stats:', xhr);
                }
            });
        },
        
        // Initialize Bootstrap Table
        initializeTable: function() {
            $('#table').bootstrapTable({
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
            
            $('.users_select').select2({
                placeholder: '{{ get_label('select_users', 'Select Users') }}',
                allowClear: true,
                ajax: {
                    url: '{{ url('/users/search') }}',
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
        
        // Initialize date pickers
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
            
            $('#ie_date_between').daterangepicker(dateOptions);
            $('#start_date_between').daterangepicker(dateOptions);
            $('#end_date_between').daterangepicker(dateOptions);
        },
        
        // Refresh all data
        refreshData: function() {
            const refreshBtn = $('#refresh-estimates-invoices-data');
            const originalHtml = refreshBtn.html();
            
            refreshBtn.html('<i class="bx bx-loader-alt bx-spin"></i>').attr('disabled', true);
            
            // Refresh table
            $('#table').bootstrapTable('refresh');
            
            // Refresh stats
            this.loadStats();
            
            setTimeout(() => {
                refreshBtn.html(originalHtml).attr('disabled', false);
                toastr.success('{{ get_label('data_refreshed', 'Data refreshed successfully') }}');
            }, 1000);
        },
        
        // Apply filters
        applyFilters: function() {
            $('#table').bootstrapTable('refresh');
            toastr.success('{{ get_label('filters_applied', 'Filters applied successfully') }}');
        },
        
        // Export data
        exportData: function() {
            const exportBtn = $('#export-estimates-invoices');
            const originalHtml = exportBtn.html();
            
            exportBtn.html('<i class="bx bx-loader-alt bx-spin"></i>').attr('disabled', true);
            
            const params = this.getQueryParams({
                offset: 0,
                limit: 10000,
                search: $('#estimates-invoices-search').val()
            });
            
            const exportUrl = '{{ url('/estimates-invoices/export') }}?' + $.param(params);
            window.location.href = exportUrl;
            
            setTimeout(() => {
                exportBtn.html(originalHtml).attr('disabled', false);
                toastr.success('{{ get_label('export_started', 'Export started') }}');
            }, 1000);
        },
        
        // Save column visibility
        saveColumnVisibility: function() {
            const visibleColumns = {};
            $('#table').bootstrapTable('getVisibleColumns').forEach(column => {
                if (column.field) {
                    visibleColumns[column.field] = column.visible;
                }
            });
            
            localStorage.setItem('estimates_invoices_column_visibility', JSON.stringify(visibleColumns));
        },
        
        // Update table information
        updateTableInfo: function(data) {
            const table = $('#table');
            const info = table.bootstrapTable('getOptions');
            
            if (data && data.total) {
                $('.card-title').last().append(` <span class="badge bg-primary ms-2">${data.total} {{ get_label('records', 'records') }}</span>`);
            }
        },
        
        // Initialize tooltips
        initializeTooltips: function() {
            $('[data-bs-toggle="tooltip"]').tooltip({
                trigger: 'hover'
            });
        },
        
        // Get query parameters
        getQueryParams: function(params) {
            return {
                "types": $('#type_filter').val(),
                "status": $('#hidden_status').val(),
                "client_ids": $('#client_filter').val(),
                "created_by_user_ids": $('#user_creators_filter').val(),
                "created_by_client_ids": $('#client_creators_filter').val(),
                "date_between_from": $('#date_between_from').val(),
                "date_between_to": $('#date_between_to').val(),
                "start_date_from": $('#start_date_from').val(),
                "start_date_to": $('#start_date_to').val(),
                "end_date_from": $('#end_date_from').val(),
                "end_date_to": $('#end_date_to').val(),
                page: params.page || 1,
                limit: params.limit || 10,
                sort: params.sort || 'id',
                order: params.order || 'desc',
                search: params.search || $('#estimates-invoices-search').val()
            };
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        EstimatesInvoicesManager.init();
    });
    
    // Make manager globally available
    window.EstimatesInvoicesManager = EstimatesInvoicesManager;
</script>
<script src="{{asset('assets/js/pages/estimates-invoices-enhanced.js')}}"></script>
<script src="{{asset('assets/js/pages/estimates-invoices.js')}}"></script>
@endsection