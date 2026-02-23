// Contract Quantities Page JavaScript

function queryParamsContractQuantities(params) {
    return {
        ...params,
        cq_date_between_from: document.getElementById('cq_date_between_from')?.value || '',
        cq_date_between_to: document.getElementById('cq_date_between_to')?.value || '',
        cq_submitted_date_from: document.getElementById('cq_submitted_date_from')?.value || '',
        cq_submitted_date_to: document.getElementById('cq_submitted_date_to')?.value || '',
        contract_ids: document.getElementById('cq_contract_filter')?.value ? document.getElementById('cq_contract_filter').value.split(',') : [],
        user_ids: document.getElementById('cq_user_filter')?.value ? document.getElementById('cq_user_filter').value.split(',') : [],
        status: document.getElementById('cq_status_filter')?.value || ''
    };
}

// Date range handling for contract quantities
if (document.getElementById('cq_date_between')) {
    $('#cq_date_between').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#cq_date_between').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        $('#cq_date_between_from').val(picker.startDate.format('YYYY-MM-DD'));
        $('#cq_date_between_to').val(picker.endDate.format('YYYY-MM-DD'));
        $('#contract_quantities_table').bootstrapTable('refresh');
    });

    $('#cq_date_between').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#cq_date_between_from').val('');
        $('#cq_date_between_to').val('');
        $('#contract_quantities_table').bootstrapTable('refresh');
    });
}

// Submitted date range handling
if (document.getElementById('cq_submitted_date_between')) {
    $('#cq_submitted_date_between').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('#cq_submitted_date_between').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        $('#cq_submitted_date_from').val(picker.startDate.format('YYYY-MM-DD'));
        $('#cq_submitted_date_to').val(picker.endDate.format('YYYY-MM-DD'));
        $('#contract_quantities_table').bootstrapTable('refresh');
    });

    $('#cq_submitted_date_between').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#cq_submitted_date_from').val('');
        $('#cq_submitted_date_to').val('');
        $('#contract_quantities_table').bootstrapTable('refresh');
    });
}

// Load contracts for filter
if (document.getElementById('cq_contract_filter')) {
    $.get(baseUrl + '/contracts/list', function(data) {
        var options = '';
        data.rows.forEach(function(contract) {
            options += '<option value="' + contract.id + '">' + contract.title + '</option>';
        });
        $('#cq_contract_filter').html(options).select2({
            placeholder: label_select_contracts
        }).on('change', function() {
            $('#contract_quantities_table').bootstrapTable('refresh');
        });
    });
}

// Load users for filter
if (document.getElementById('cq_user_filter')) {
    $.get(baseUrl + '/users/list', function(data) {
        var options = '';
        data.rows.forEach(function(user) {
            options += '<option value="' + user.id + '">' + user.first_name + ' ' + user.last_name + '</option>';
        });
        $('#cq_user_filter').html(options).select2({
            placeholder: label_select_users
        }).on('change', function() {
            $('#contract_quantities_table').bootstrapTable('refresh');
        });
    });
}

// Status filter
if (document.getElementById('cq_status_filter')) {
    $('#cq_status_filter').select2({
        placeholder: label_select_statuses
    }).on('change', function() {
        $('#contract_quantities_table').bootstrapTable('refresh');
    });
}

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