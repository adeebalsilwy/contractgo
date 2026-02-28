$(document).ready(function() {
    // Initialize all Bootstrap Tables
    initializeApprovalTables();
    
    // Load initial counts
    loadApprovalCounts();
    
    // Handle tab changes
    $('#approvalTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("data-bs-target");
        var tableId = target.replace('#', '') + '-approvals-table';
        
        // Refresh the table when tab is shown
        if ($(tableId).bootstrapTable) {
            $('#' + tableId).bootstrapTable('refresh');
        }
    });

    // Handle search inputs
    $('#pending-search, #approved-search, #rejected-search, #archived-search, #all-search').on('keyup', function() {
        var tableId = $(this).attr('id').replace('-search', '') + '-approvals-table';
        if ($(this).val().length > 2 || $(this).val().length === 0) {
            setTimeout(function() {
                $('#' + tableId).bootstrapTable('refresh');
            }, 500);
        }
    });

    // Handle filter selects
    $('#pending-stage-filter, #approved-stage-filter, #rejected-stage-filter, #all-status-filter, #all-stage-filter').on('change', function() {
        var tableId = $(this).attr('id').replace('-filter', '').replace('-stage', '').replace('-status', '') + '-approvals-table';
        $('#' + tableId).bootstrapTable('refresh');
    });

    // Handle approve/reject buttons
    $(document).on('click', '.approve-btn', function() {
        var id = $(this).data('id');
        handleApprovalAction(id, 'approve');
    });

    $(document).on('click', '.reject-btn', function() {
        var id = $(this).data('id');
        handleApprovalAction(id, 'reject');
    });

    // Initialize signature pad for approval forms
    initializeSignaturePad();
});

// Query parameter functions for each tab
function pendingQueryParams(params) {
    return {
        ...params,
        status: 'pending',
        approval_stage: $('#pending-stage-filter').val(),
        search: $('#pending-search').val()
    };
}

function approvedQueryParams(params) {
    return {
        ...params,
        status: 'approved',
        approval_stage: $('#approved-stage-filter').val(),
        search: $('#approved-search').val()
    };
}

function rejectedQueryParams(params) {
    return {
        ...params,
        status: 'rejected',
        approval_stage: $('#rejected-stage-filter').val(),
        search: $('#rejected-search').val()
    };
}

function archivedQueryParams(params) {
    return {
        ...params,
        search: $('#archived-search').val()
    };
}

function allQueryParams(params) {
    return {
        ...params,
        status: $('#all-status-filter').val(),
        approval_stage: $('#all-stage-filter').val(),
        search: $('#all-search').val()
    };
}

function initializeApprovalTables() {
    // Initialize all Bootstrap Tables with proper configuration
    var tables = [
        'pending-approvals-table',
        'approved-approvals-table',
        'rejected-approvals-table',
        'archived-approvals-table',
        'all-approvals-table'
    ];

    tables.forEach(function(tableId) {
        if (document.getElementById(tableId)) {
            $('#' + tableId).bootstrapTable({
                url: baseUrl + '/contract-approvals/list',
                method: 'get',
                queryParams: window[tableId.replace('-table', '') + 'QueryParams'],
                pagination: true,
                sidePagination: 'server',
                pageSize: 10,
                pageList: [10, 25, 50, 100],
                search: false,
                showColumns: true,
                showRefresh: true,
                ajaxOptions: {
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                }
            });
        }
    });
}

function loadApprovalCounts() {
    // Load counts for each tab
    $.ajax({
        url: baseUrl + '/contract-approvals/list',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrf_token
        },
        data: {
            limit: 1,
            offset: 0
        },
        success: function(response) {
            // This would need to be modified to return counts for each status
            // For now, we'll set placeholder values
            $('#pending-count').text('5');
            $('#approved-count').text('12');
            $('#rejected-count').text('3');
            $('#archived-count').text('8');
            $('#all-count').text('28');
        }
    });
}

function handleApprovalAction(id, action) {
    var title = action === 'approve' ? 'Approve' : 'Reject';
    var text = 'Are you sure you want to ' + action + ' this approval?';
    
    Swal.fire({
        title: title + ' Approval',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, ' + action + ' it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseUrl + '/contract-approvals/' + id + '/' + action + '-approval',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                success: function(response) {
                    if (!response.error) {
                        Swal.fire(
                            'Success!',
                            'The approval has been ' + action + 'ed.',
                            'success'
                        );
                        // Refresh all tables
                        refreshAllTables();
                        // Reload counts
                        loadApprovalCounts();
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'An error occurred while processing the request.',
                        'error'
                    );
                }
            });
        }
    });
}

function refreshAllTables() {
    var tables = [
        'pending-approvals-table',
        'approved-approvals-table',
        'rejected-approvals-table',
        'archived-approvals-table',
        'all-approvals-table'
    ];

    tables.forEach(function(tableId) {
        if (document.getElementById(tableId)) {
            $('#' + tableId).bootstrapTable('refresh');
        }
    });
}

function initializeSignaturePad() {
    var signaturePad;
    if ($('#signature-pad canvas').length) {
        const wrapper = document.getElementById("signature-pad");
        const inline = new SignaturePad(wrapper.querySelector("canvas"));
        signaturePad = inline;

        // Adjust canvas dimensions
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        signaturePad.canvas.width = signaturePad.canvas.offsetWidth * ratio;
        signaturePad.canvas.height = signaturePad.canvas.offsetHeight * ratio;
        signaturePad.clear();

        // Clear signature button
        $('#clear-signature').on('click', function() {
            signaturePad.clear();
            $('#approval_signature').val('');
        });
    }

    // Handle approve button click
    $('#approveBtn').on('click', function(e) {
        e.preventDefault();
        
        // Get signature data if available
        var signatureData = signaturePad ? signaturePad.toDataURL() : '';
        $('#approval_signature').val(signatureData);

        var formData = $('#approvalForm').serialize();
        
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function(response) {
                if(response.error) {
                    showErrorMessage(response.message);
                } else {
                    showSuccessMessage(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url || '/contract-approvals';
                    }, 2000);
                }
            },
            error: function(xhr) {
                showErrorMessage(getResponseError(xhr));
            }
        });
    });

    // Handle reject button click
    $('#rejectBtn').on('click', function(e) {
        e.preventDefault();
        
        // Confirm rejection
        if(!confirm('Are you sure you want to reject this contract?')) {
            return;
        }
        
        // Get signature data if available
        var signatureData = signaturePad ? signaturePad.toDataURL() : '';
        $('#approval_signature').val(signatureData);

        var formData = $('#approvalForm').serialize();
        formData += '&status=rejected'; // Add status as rejected
        
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function(response) {
                if(response.error) {
                    showErrorMessage(response.message);
                } else {
                    showSuccessMessage(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url || '/contract-approvals';
                    }, 2000);
                }
            },
            error: function(xhr) {
                showErrorMessage(getResponseError(xhr));
            }
        });
    });
}

function showSuccessMessage(message) {
    // Implementation for showing success message
    alert(message);
}

function showErrorMessage(message) {
    // Implementation for showing error message
    alert(message);
}

function getResponseError(xhr) {
    // Extract error message from response
    if(xhr.responseJSON && xhr.responseJSON.message) {
        return xhr.responseJSON.message;
    }
    return 'An error occurred';
}