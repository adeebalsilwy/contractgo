'use strict';

// Enhanced Contracts JavaScript with Professional Data Fetching
const EnhancedContracts = {
    // Configuration
    config: {
        baseUrl: window.baseUrl || '',
        debounceDelay: 300,
        refreshInterval: 30000 // 30 seconds
    },
    
    // Initialize the enhanced contracts functionality
    init: function() {
        this.bindEvents();
        this.initializeComponents();
        this.startAutoRefresh();
    },
    
    // Bind all event listeners
    bindEvents: function() {
        // Enhanced filter events with debouncing
        const filterSelectors = '#status_filter, #workflow_status_filter, #archived_filter, #client_filter, #project_filter, #type_filter';
        this.addDebouncedEventListener(filterSelectors, 'change', (e) => {
            e.preventDefault();
            $('#contracts_table').bootstrapTable('refresh');
            this.updateFilterSummary();
        });
        
        // Enhanced date range events
        this.bindDateRangeEvents();
        
        // Enhanced action buttons
        this.bindActionButtons();
        
        // Enhanced table events
        this.bindTableEvents();
    },
    
    // Initialize components
    initializeComponents: function() {
        // Initialize enhanced tooltips
        this.initializeTooltips();
        
        // Initialize enhanced modals
        this.initializeModals();
        
        // Initialize enhanced notifications
        this.initializeNotifications();
        
        // Load initial data
        this.loadInitialData();
    },
    
    // Enhanced date range picker events
    bindDateRangeEvents: function() {
        const dateEvents = [
            { selector: '#contract_date_between', from: '#contract_date_between_from', to: '#contract_date_between_to' },
            { selector: '#contract_start_date_between', from: '#contract_start_date_from', to: '#contract_start_date_to' },
            { selector: '#contract_end_date_between', from: '#contract_end_date_from', to: '#contract_end_date_to' }
        ];
        
        dateEvents.forEach(event => {
            $(event.selector).on('apply.daterangepicker', (ev, picker) => {
                const startDate = picker.startDate.format('YYYY-MM-DD');
                const endDate = picker.endDate.format('YYYY-MM-DD');
                
                $(event.from).val(startDate);
                $(event.to).val(endDate);
                
                $('#contracts_table').bootstrapTable('refresh');
                this.showDateFilterApplied();
            });
            
            $(event.selector).on('cancel.daterangepicker', (ev, picker) => {
                $(event.from).val('');
                $(event.to).val('');
                $(event.selector).val('');
                picker.setStartDate(moment());
                picker.setEndDate(moment());
                picker.updateElement();
                $('#contracts_table').bootstrapTable('refresh');
                this.showDateFilterCleared();
            });
        });
    },
    
    // Enhanced action buttons
    bindActionButtons: function() {
        // Bulk actions
        $(document).on('click', '.bulk-action-btn', (e) => {
            e.preventDefault();
            const action = $(e.currentTarget).data('action');
            const selected = this.getSelectedContracts();
            
            if (selected.length === 0) {
                this.showNotification('warning', 'Please select contracts first');
                return;
            }
            
            this.performBulkAction(action, selected);
        });
        
        // Quick actions
        $(document).on('click', '.quick-action-btn', (e) => {
            e.preventDefault();
            const action = $(e.currentTarget).data('action');
            const contractId = $(e.currentTarget).data('contract-id');
            
            this.performQuickAction(action, contractId);
        });
        
        // Export with progress
        $('#export-contracts-enhanced').on('click', () => {
            this.exportWithProgress();
        });
    },
    
    // Enhanced table events
    bindTableEvents: function() {
        $('#contracts_table').on('load-success.bs.table', (e, data) => {
            this.onTableLoadSuccess(data);
        });
        
        $('#contracts_table').on('load-error.bs.table', (e, status, res) => {
            this.onTableLoadError(status, res);
        });
        
        // Row selection events
        $('#contracts_table').on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', () => {
            this.updateBulkActionButtons();
        });
    },
    
    // Get selected contracts
    getSelectedContracts: function() {
        return $('#contracts_table').bootstrapTable('getSelections').map(item => item.id);
    },
    
    // Perform bulk action
    performBulkAction: function(action, contractIds) {
        const actions = {
            'archive': () => this.archiveContracts(contractIds),
            'delete': () => this.deleteContracts(contractIds),
            'export': () => this.exportSelectedContracts(contractIds),
            'duplicate': () => this.duplicateContracts(contractIds)
        };
        
        if (actions[action]) {
            actions[action]();
        }
    },
    
    // Perform quick action
    performQuickAction: function(action, contractId) {
        const actions = {
            'view': () => this.viewContract(contractId),
            'edit': () => this.editContract(contractId),
            'duplicate': () => this.duplicateContract(contractId),
            'delete': () => this.deleteContract(contractId)
        };
        
        if (actions[action]) {
            actions[action]();
        }
    },
    
    // Enhanced archive contracts
    archiveContracts: function(contractIds) {
        this.showConfirmationDialog({
            title: 'Archive Contracts',
            message: `Are you sure you want to archive ${contractIds.length} contract(s)?`,
            confirmText: 'Archive',
            confirmClass: 'btn-warning',
            onConfirm: () => {
                this.executeAjaxRequest({
                    url: `${this.config.baseUrl}/contracts/bulk-archive`,
                    method: 'POST',
                    data: { contract_ids: contractIds },
                    successMessage: `${contractIds.length} contract(s) archived successfully`,
                    errorMessage: 'Failed to archive contracts'
                });
            }
        });
    },
    
    // Enhanced delete contracts
    deleteContracts: function(contractIds) {
        this.showConfirmationDialog({
            title: 'Delete Contracts',
            message: `Are you sure you want to delete ${contractIds.length} contract(s)? This action cannot be undone.`,
            confirmText: 'Delete',
            confirmClass: 'btn-danger',
            onConfirm: () => {
                this.executeAjaxRequest({
                    url: `${this.config.baseUrl}/contracts/bulk-delete`,
                    method: 'POST',
                    data: { contract_ids: contractIds },
                    successMessage: `${contractIds.length} contract(s) deleted successfully`,
                    errorMessage: 'Failed to delete contracts'
                });
            }
        });
    },
    
    // Enhanced export with progress
    exportWithProgress: function() {
        const exportBtn = $('#export-contracts-enhanced');
        const originalHtml = exportBtn.html();
        
        // Show progress
        exportBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Preparing Export...').attr('disabled', true);
        
        // Get current filters
        const params = this.getEnhancedQueryParams();
        
        // Create export request
        this.executeAjaxRequest({
            url: `${this.config.baseUrl}/contracts/export-enhanced`,
            method: 'POST',
            data: params,
            success: (response) => {
                if (response.download_url) {
                    // Download the file
                    window.location.href = response.download_url;
                    this.showNotification('success', 'Export completed successfully');
                }
            },
            error: () => {
                this.showNotification('error', 'Export failed. Please try again.');
            },
            complete: () => {
                exportBtn.html(originalHtml).attr('disabled', false);
            }
        });
    },
    
    // Enhanced AJAX request executor
    executeAjaxRequest: function(options) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: () => {},
            error: () => {},
            complete: () => {}
        };
        
        const finalOptions = { ...defaultOptions, ...options };
        
        $.ajax({
            url: finalOptions.url,
            method: finalOptions.method,
            data: finalOptions.data,
            headers: finalOptions.headers,
            success: (response) => {
                if (response.success) {
                    if (finalOptions.successMessage) {
                        this.showNotification('success', finalOptions.successMessage);
                    }
                    finalOptions.success(response);
                    // Refresh table and stats
                    $('#contracts_table').bootstrapTable('refresh');
                    if (typeof ContractsManager !== 'undefined') {
                        ContractsManager.loadStats();
                    }
                } else {
                    this.showNotification('error', response.message || finalOptions.errorMessage || 'Operation failed');
                    finalOptions.error(response);
                }
            },
            error: (xhr) => {
                this.showNotification('error', finalOptions.errorMessage || 'An error occurred');
                finalOptions.error(xhr);
            },
            complete: finalOptions.complete
        });
    },
    
    // Enhanced query parameters
    getEnhancedQueryParams: function(additionalParams = {}) {
        const baseParams = {
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
            "end_date_to": $('#contract_end_date_to').val()
        };
        
        return { ...baseParams, ...additionalParams };
    },
    
    // Enhanced notifications
    showNotification: function(type, message, options = {}) {
        const defaultOptions = {
            position: 'top-right',
            duration: 5000,
            close: true
        };
        
        const notificationOptions = { ...defaultOptions, ...options };
        
        // Use Toastr if available, otherwise fallback to alert
        if (typeof toastr !== 'undefined') {
            switch(type) {
                case 'success':
                    toastr.success(message, 'Success', notificationOptions);
                    break;
                case 'error':
                    toastr.error(message, 'Error', notificationOptions);
                    break;
                case 'warning':
                    toastr.warning(message, 'Warning', notificationOptions);
                    break;
                case 'info':
                    toastr.info(message, 'Info', notificationOptions);
                    break;
            }
        } else {
            alert(`${type.toUpperCase()}: ${message}`);
        }
    },
    
    // Enhanced confirmation dialog
    showConfirmationDialog: function(options) {
        const defaultOptions = {
            title: 'Confirm Action',
            message: 'Are you sure?',
            confirmText: 'Confirm',
            cancelText: 'Cancel',
            confirmClass: 'btn-primary',
            onConfirm: () => {},
            onCancel: () => {}
        };
        
        const finalOptions = { ...defaultOptions, ...options };
        
        // Use Bootstrap modal if available
        if (typeof bootstrap !== 'undefined') {
            this.createBootstrapModal(finalOptions);
        } else {
            // Fallback to native confirm
            if (confirm(finalOptions.message)) {
                finalOptions.onConfirm();
            } else {
                finalOptions.onCancel();
            }
        }
    },
    
    // Create Bootstrap modal for confirmation
    createBootstrapModal: function(options) {
        const modalId = 'confirmation-modal-' + Date.now();
        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${options.title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>${options.message}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${options.cancelText}</button>
                            <button type="button" class="btn ${options.confirmClass}" id="confirm-action">${options.confirmText}</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHtml);
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        
        $('#confirm-action').on('click', function() {
            options.onConfirm();
            modal.hide();
        });
        
        document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
            $(this).remove();
            options.onCancel();
        });
        
        modal.show();
    },
    
    // Enhanced debounced event listener
    addDebouncedEventListener: function(selector, event, callback) {
        let timeout;
        $(document).on(event, selector, function(e) {
            clearTimeout(timeout);
            timeout = setTimeout(() => callback(e), this.config.debounceDelay);
        });
    },
    
    // Enhanced tooltips initialization
    initializeTooltips: function() {
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    },
    
    // Enhanced modals initialization
    initializeModals: function() {
        // Initialize any custom modal behavior
        $(document).on('show.bs.modal', '.modal', function() {
            // Add loading states to modal buttons
            $(this).find('.modal-footer .btn-primary').attr('data-loading-text', 'Processing...');
        });
    },
    
    // Enhanced notifications initialization
    initializeNotifications: function() {
        // Configure Toastr if available
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: true,
                positionClass: "toast-top-right",
                preventDuplicates: false,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                timeOut: "5000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };
        }
    },
    
    // Load initial data
    loadInitialData: function() {
        // Load any additional data needed for the page
        this.loadContractTypes();
        this.loadProjects();
        this.loadClients();
    },
    
    // Load contract types
    loadContractTypes: function() {
        // Implementation for loading contract types
    },
    
    // Load projects
    loadProjects: function() {
        // Implementation for loading projects
    },
    
    // Load clients
    loadClients: function() {
        // Implementation for loading clients
    },
    
    // Update filter summary
    updateFilterSummary: function() {
        // Update the filter summary display
        const activeFilters = this.getActiveFiltersCount();
        if (activeFilters > 0) {
            $('.filter-summary').html(`<span class="badge bg-primary">${activeFilters} active filters</span>`);
        } else {
            $('.filter-summary').html('');
        }
    },
    
    // Get active filters count
    getActiveFiltersCount: function() {
        let count = 0;
        const filterSelectors = ['#status_filter', '#workflow_status_filter', '#archived_filter', '#client_filter', '#project_filter', '#type_filter'];
        
        filterSelectors.forEach(selector => {
            const value = $(selector).val();
            if (value && value.length > 0) {
                count += Array.isArray(value) ? value.length : 1;
            }
        });
        
        // Check date filters
        ['#contract_date_between_from', '#contract_start_date_from', '#contract_end_date_from'].forEach(selector => {
            if ($(selector).val()) count++;
        });
        
        return count;
    },
    
    // Show date filter applied notification
    showDateFilterApplied: function() {
        this.showNotification('info', 'Date filter applied successfully');
    },
    
    // Show date filter cleared notification
    showDateFilterCleared: function() {
        this.showNotification('info', 'Date filter cleared');
    },
    
    // Update bulk action buttons
    updateBulkActionButtons: function() {
        const selectedCount = this.getSelectedContracts().length;
        $('.bulk-action-btn').prop('disabled', selectedCount === 0);
        
        if (selectedCount > 0) {
            $('.selected-count').text(`${selectedCount} selected`);
        } else {
            $('.selected-count').text('');
        }
    },
    
    // Table load success handler
    onTableLoadSuccess: function(data) {
        this.updateTableInfo(data);
        this.initializeTooltips();
    },
    
    // Table load error handler
    onTableLoadError: function(status, res) {
        this.showNotification('error', 'Failed to load contract data');
    },
    
    // Update table information
    updateTableInfo: function(data) {
        if (data && data.total) {
            $('.table-info').html(`Showing ${data.total} contracts`);
        }
    },
    
    // Start auto-refresh
    startAutoRefresh: function() {
        if (this.config.refreshInterval > 0) {
            setInterval(() => {
                if (!$('input:focus').length && !$('.modal.show').length) {
                    $('#contracts_table').bootstrapTable('refresh');
                }
            }, this.config.refreshInterval);
        }
    }
};

// Initialize when document is ready
$(document).ready(function() {
    EnhancedContracts.init();
});

// Make available globally
window.EnhancedContracts = EnhancedContracts;