// Contract Quantities Page JavaScript

// Initialize Bootstrap Table when document is ready
$(document).ready(function() {
    if (document.getElementById('contract_quantities_table')) {
        $('#contract_quantities_table').bootstrapTable({
            url: baseUrl + '/contract-quantities/list',
            method: 'get',
            queryParams: function(params) {
                params.search = $('#searchInput').val();
                params.status = $('#statusFilter').val();
                return params;
            },
            pagination: true,
            sidePagination: 'server',
            pageSize: 10,
            pageList: [10, 25, 50, 100],
            search: false,
            showColumns: true,
            showRefresh: true,
            toolbar: '.dt-plugin-buttons',
            // Add CSRF token to all requests
            ajaxOptions: {
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            }
        });
    }
});

// Handle filter button click
$('#filterBtn').on('click', function() {
    $('#contract_quantities_table').bootstrapTable('refresh');
});

// Handle search input
$('#searchInput').on('keyup', function() {
    if ($(this).val().length > 2 || $(this).val().length === 0) {
        setTimeout(function() {
            $('#contract_quantities_table').bootstrapTable('refresh');
        }, 500);
    }
});

// Simple filter functionality
$('#statusFilter').on('change', function() {
    $('#contract_quantities_table').bootstrapTable('refresh');
});

// Handle delete for contract quantities
$(document).on('click', '.delete[data-type="contract-quantities"]', function() {
    var id = $(this).data('id');
    deleteRecord(id, 'contract-quantities');
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
                url: baseUrl + '/' + type + '/' + id,
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
                        $('#contract_quantities_table').bootstrapTable('refresh');
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