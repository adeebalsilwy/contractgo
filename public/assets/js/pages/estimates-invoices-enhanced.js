'use strict';

// Enhanced Estimates/Invoices JavaScript with Professional Data Fetching
const EnhancedEstimatesInvoices = {
    // Configuration
    config: {
        baseUrl: window.baseUrl || '',
        debounceDelay: 300,
        refreshInterval: 30000 // 30 seconds
    },
    
    // Initialize the enhanced functionality
    init: function() {
        this.bindEvents();
        this.initializeComponents();
        this.startAutoRefresh();
    },
    
    // Bind all event listeners
    bindEvents: function() {
        // Enhanced filter events with debouncing
        const filterSelectors = '#type_filter, #client_filter, #user_creators_filter, #client_creators_filter';
        this.addDebouncedEventListener(filterSelectors, 'change', (e) => {
            e.preventDefault();
            $('#table').bootstrapTable('refresh');
            this.updateFilterSummary();
        });
        
        // Enhanced date range events
        this.bindDateRangeEvents();
        
        // Enhanced action buttons
        this.bindActionButtons();
        
        // Enhanced table events
        this.bindTableEvents();
        
        // Enhanced status badge events
        this.bindStatusBadgeEvents();
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
            { selector: '#ie_date_between', from: '#date_between_from', to: '#date_between_to' },
            { selector: '#start_date_between', from: '#start_date_from', to: '#start_date_to' },
            { selector: '#end_date_between', from: '#end_date_from', to: '#end_date_to' }
        ];
        
        dateEvents.forEach(event => {
            $(event.selector).on('apply.daterangepicker', (ev, picker) => {
                const startDate = picker.startDate.format('YYYY-MM-DD');
                const endDate = picker.endDate.format('YYYY-MM-DD');
                
                $(event.from).val(startDate);
                $(event.to).val(endDate);
                
                $('#table').bootstrapTable('refresh');
                this.showDateFilterApplied();
            });
            
            $(event.selector).on('cancel.daterangepicker', (ev, picker) => {
                $(event.from).val('');
                $(event.to).val('');
                $(event.selector).val('');
                picker.setStartDate(moment());
                picker.setEndDate(moment());
                picker.updateElement();
                $('#table').bootstrapTable('refresh');
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
            const selected = this.getSelectedDocuments();
            
            if (selected.length === 0) {
                this.showNotification('warning', 'Please select documents first');
                return;
            }
            
            this.performBulkAction(action, selected);
        });
        
        // Quick actions
        $(document).on('click', '.quick-action-btn', (e) => {
            e.preventDefault();
            const action = $(e.currentTarget).data('action');
            const documentId = $(e.currentTarget).data('document-id');
            const documentType = $(e.currentTarget).data('document-type');
            
            this.performQuickAction(action, documentId, documentType);
        });
        
        // Export with progress
        $('#export-estimates-invoices-enhanced').on('click', () => {
            this.exportWithProgress();
        });
        
        // Convert estimate to invoice
        $(document).on('click', '.convert-to-invoice', (e) => {
            e.preventDefault();
            const estimateId = $(e.currentTarget).data('estimate-id');
            this.convertEstimateToInvoice(estimateId);
        });
    },
    
    // Enhanced table events
    bindTableEvents: function() {
        $('#table').on('load-success.bs.table', (e, data) => {
            this.onTableLoadSuccess(data);
        });
        
        $('#table').on('load-error.bs.table', (e, status, res) => {
            this.onTableLoadError(status, res);
        });
        
        // Row selection events
        $('#table').on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', () => {
            this.updateBulkActionButtons();
        });
    },
    
    // Enhanced status badge events
    bindStatusBadgeEvents: function() {
        $(document).on('click', '.status-badge', function(e) {
            const status = $(this).data('status');
            const type = $(this).data('type');
            
            // Update hidden status field
            $('#hidden_status').val(status);
            
            // Update type filter if specified
            if (type) {
                $('#type_filter').val(type).trigger('change');
            }
            
            // Refresh table
            $('#table').bootstrapTable('refresh');
            
            // Update active state
            $('.status-badge').removeClass('active');
            $(this).addClass('active');
            
            // Show notification
            const statusText = status ? $(this).text().trim() : 'All';
            const typeText = type === 'estimate' ? 'Estimates' : type === 'invoice' ? 'Invoices' : 'Documents';
            EnhancedEstimatesInvoices.showNotification('info', `Showing ${statusText} ${typeText}`);
        });
    },
    
    // Get selected documents
    getSelectedDocuments: function() {
        return $('#table').bootstrapTable('getSelections').map(item => ({
            id: item.id,
            type: item.type
        }));
    },
    
    // Perform bulk action
    performBulkAction: function(action, documents) {
        const actions = {
            'delete': () => this.deleteDocuments(documents),
            'export': () => this.exportSelectedDocuments(documents),
            'duplicate': () => this.duplicateDocuments(documents),
            'mark-as-sent': () => this.markAsSent(documents),
            'mark-as-paid': () => this.markAsPaid(documents)
        };
        
        if (actions[action]) {
            actions[action]();
        }
    },
    
    // Perform quick action
    performQuickAction: function(action, documentId, documentType) {
        const actions = {
            'view': () => this.viewDocument(documentId, documentType),
            'edit': () => this.editDocument(documentId, documentType),
            'duplicate': () => this.duplicateDocument(documentId, documentType),
            'delete': () => this.deleteDocument(documentId, documentType),
            'send': () => this.sendDocument(documentId, documentType),
            'convert': () => this.convertDocument(documentId, documentType)
        };
        
        if (actions[action]) {
            actions[action]();
        }
    },
    
    // Enhanced delete documents
    deleteDocuments: function(documents) {
        const documentCount = documents.length;
        const estimateCount = documents.filter(d => d.type === 'Estimate').length;
        const invoiceCount = documents.filter(d => d.type === 'Invoice').length;
        
        let message = `Are you sure you want to delete ${documentCount} document(s)?`;
        if (estimateCount > 0) message += ` (${estimateCount} estimate${estimateCount > 1 ? 's' : ''})`;
        if (invoiceCount > 0) message += ` (${invoiceCount} invoice${invoiceCount > 1 ? 's' : ''})`;
        message += ' This action cannot be undone.';
        
        this.showConfirmationDialog({
            title: 'Delete Documents',
            message: message,
            confirmText: 'Delete',
            confirmClass: 'btn-danger',
            onConfirm: () => {
                this.executeAjaxRequest({
                    url: `${this.config.baseUrl}/estimates-invoices/bulk-delete`,
                    method: 'POST',
                    data: { documents: documents },
                    successMessage: `${documentCount} document(s) deleted successfully`,
                    errorMessage: 'Failed to delete documents'
                });
            }
        });
    },
    
    // Enhanced export with progress
    exportWithProgress: function() {
        const exportBtn = $('#export-estimates-invoices-enhanced');
        const originalHtml = exportBtn.html();
        
        // Show progress
        exportBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Preparing Export...').attr('disabled', true);
        
        // Get current filters
        const params = this.getEnhancedQueryParams();
        
        // Create export request
        this.executeAjaxRequest({
            url: `${this.config.baseUrl}/estimates-invoices/export-enhanced`,
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
    
    // Convert estimate to invoice
    convertEstimateToInvoice: function(estimateId) {
        this.showConfirmationDialog({
            title: 'Convert to Invoice',
            message: 'Are you sure you want to convert this estimate to an invoice?',
            confirmText: 'Convert',
            confirmClass: 'btn-success',
            onConfirm: () => {
                this.executeAjaxRequest({
                    url: `${this.config.baseUrl}/estimates-invoices/${estimateId}/convert-to-invoice`,
                    method: 'POST',
                    successMessage: 'Estimate converted to invoice successfully',
                    errorMessage: 'Failed to convert estimate to invoice'
                });
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
                    $('#table').bootstrapTable('refresh');
                    if (typeof EstimatesInvoicesManager !== 'undefined') {
                        EstimatesInvoicesManager.loadStats();
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
            "end_date_to": $('#end_date_to').val()
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
        this.loadClients();
        this.loadUsers();
    },
    
    // Load clients
    loadClients: function() {
        // Implementation for loading clients
    },
    
    // Load users
    loadUsers: function() {
        // Implementation for loading users
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
        const filterSelectors = ['#type_filter', '#client_filter', '#user_creators_filter', '#client_creators_filter'];
        
        filterSelectors.forEach(selector => {
            const value = $(selector).val();
            if (value && value.length > 0) {
                count += Array.isArray(value) ? value.length : 1;
            }
        });
        
        // Check status filter
        if ($('#hidden_status').val()) count++;
        
        // Check date filters
        ['#date_between_from', '#start_date_from', '#end_date_from'].forEach(selector => {
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
        const selectedCount = this.getSelectedDocuments().length;
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
        this.showNotification('error', 'Failed to load document data');
    },
    
    // Update table information
    updateTableInfo: function(data) {
        if (data && data.total) {
            $('.table-info').html(`Showing ${data.total} documents`);
        }
    },
    
    // Start auto-refresh
    startAutoRefresh: function() {
        if (this.config.refreshInterval > 0) {
            setInterval(() => {
                if (!$('input:focus').length && !$('.modal.show').length) {
                    $('#table').bootstrapTable('refresh');
                }
            }, this.config.refreshInterval);
        }
    }
};

// Initialize when document is ready
$(document).ready(function() {
    EnhancedEstimatesInvoices.init();
});

// Make available globally
window.EnhancedEstimatesInvoices = EnhancedEstimatesInvoices;