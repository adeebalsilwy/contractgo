// Contract Amendments Page JavaScript

function queryParamsContractAmendments(params) {
    return {
        ...params,
        status: document.getElementById('statusFilter')?.value || '',
        amendment_type: document.getElementById('typeFilter')?.value || '',
        search: document.getElementById('searchInput')?.value || ''
    };
}

// Initialize DataTable when document is ready
$(document).ready(function() {
    // Initialize the main table
    $('#dataTable').bootstrapTable({
        url: baseUrl + '/contract-amendments/list',
        method: 'GET',
        pagination: true,
        sidePagination: 'server',
        pageSize: 10,
        pageList: [10, 25, 50, 100],
        queryParams: queryParamsContractAmendments,
        responseHandler: function(res) {
            return {
                total: res.total,
                rows: res.rows
            };
        },
        columns: [
            { field: 'id', title: 'ID', sortable: true },
            { field: 'contract_title', title: 'Contract', sortable: true },
            { field: 'amendment_type', title: 'Type', sortable: true },
            { field: 'request_reason', title: 'Reason', sortable: true },
            { field: 'original_value', title: 'Original Value', sortable: true },
            { field: 'new_value', title: 'New Value', sortable: true },
            { field: 'requested_by', title: 'Requested By', sortable: true },
            { field: 'status', title: 'Status', sortable: true },
            { field: 'requested_at', title: 'Requested At', sortable: true },
            { field: 'actions', title: 'Actions' }
        ]
    });

    // Handle filter button click
    $('#filterBtn').click(function() {
        $('#dataTable').bootstrapTable('refresh');
    });

    // Handle search input keyup
    $('#searchInput').on('keyup', function() {
        $('#dataTable').bootstrapTable('refresh');
    });
});

// Status filter
if (document.getElementById('amendment_status_filter')) {
    $('#amendment_status_filter').select2({
        placeholder: label_select_statuses
    }).on('change', function() {
        $('#contract_amendments_table').bootstrapTable('refresh');
    });
}

// Amendment type filter
if (document.getElementById('amendment_type_filter')) {
    $('#amendment_type_filter').select2({
        placeholder: label_select_types
    }).on('change', function() {
        $('#contract_amendments_table').bootstrapTable('refresh');
    });
}

// Load contracts for filter
if (document.getElementById('amendment_contract_filter')) {
    $.get(baseUrl + '/contracts/list', function(data) {
        var options = '';
        data.rows.forEach(function(contract) {
            options += '<option value="' + contract.id + '">' + contract.title + '</option>';
        });
        $('#amendment_contract_filter').html(options).select2({
            placeholder: label_select_contracts
        }).on('change', function() {
            $('#contract_amendments_table').bootstrapTable('refresh');
        });
    });
}

// Handle delete for contract amendments
$(document).on('click', '.delete[data-type="contract-amendments"]', function() {
    var id = $(this).data('id');
    deleteRecord(id, 'contract-amendments');
});

// Delete record function
function deleteRecord(id, type) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseUrl + '/' + type + '/destroy/' + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                success: function(response) {
                    if (!response.error) {
                        Swal.fire(
                            'Deleted!',
                            'The record has been deleted.',
                            'success'
                        );
                        $('#' + type + '_table').bootstrapTable('refresh');
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
                        'An error occurred while deleting the record.',
                        'error'
                    );
                }
            });
        }
    });
}