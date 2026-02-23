// Journal Entries Page JavaScript

function queryParamsJournalEntries(params) {
    return {
        ...params,
        je_date_between_from: document.getElementById('je_date_between_from')?.value || '',
        je_date_between_to: document.getElementById('je_date_between_to')?.value || '',
        je_entry_date_from: document.getElementById('je_entry_date_from')?.value || '',
        je_entry_date_to: document.getElementById('je_entry_date_to')?.value || '',
        contract_ids: document.getElementById('je_contract_filter')?.value ? document.getElementById('je_contract_filter').value.split(',') : [],
        invoice_ids: document.getElementById('je_invoice_filter')?.value ? document.getElementById('je_invoice_filter').value.split(',') : [],
        entry_type: document.getElementById('je_entry_type_filter')?.value || '',
        status: document.getElementById('je_status_filter')?.value || ''
    };
}

// Date range handling for journal entries
if (document.getElementById('je_date_between')) {
    $('#je_date_between').daterangepicker({
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

    $('#je_date_between').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        $('#je_date_between_from').val(picker.startDate.format('YYYY-MM-DD'));
        $('#je_date_between_to').val(picker.endDate.format('YYYY-MM-DD'));
        $('#journal_entries_table').bootstrapTable('refresh');
    });

    $('#je_date_between').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#je_date_between_from').val('');
        $('#je_date_between_to').val('');
        $('#journal_entries_table').bootstrapTable('refresh');
    });
}

// Entry date range handling
if (document.getElementById('je_entry_date_between')) {
    $('#je_entry_date_between').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('#je_entry_date_between').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        $('#je_entry_date_from').val(picker.startDate.format('YYYY-MM-DD'));
        $('#je_entry_date_to').val(picker.endDate.format('YYYY-MM-DD'));
        $('#journal_entries_table').bootstrapTable('refresh');
    });

    $('#je_entry_date_between').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#je_entry_date_from').val('');
        $('#je_entry_date_to').val('');
        $('#journal_entries_table').bootstrapTable('refresh');
    });
}

// Load contracts for filter
if (document.getElementById('je_contract_filter')) {
    $.get(baseUrl + '/contracts/list', function(data) {
        var options = '';
        data.rows.forEach(function(contract) {
            options += '<option value="' + contract.id + '">' + contract.title + '</option>';
        });
        $('#je_contract_filter').html(options).select2({
            placeholder: label_select_contracts
        }).on('change', function() {
            $('#journal_entries_table').bootstrapTable('refresh');
        });
    });
}

// Load invoices for filter
if (document.getElementById('je_invoice_filter')) {
    $.get(baseUrl + '/estimates-invoices/list', function(data) {
        var options = '';
        data.rows.forEach(function(invoice) {
            options += '<option value="' + invoice.id + '">' + invoice.name + '</option>';
        });
        $('#je_invoice_filter').html(options).select2({
            placeholder: label_select_invoices
        }).on('change', function() {
            $('#journal_entries_table').bootstrapTable('refresh');
        });
    });
}

// Entry type filter
if (document.getElementById('je_entry_type_filter')) {
    $('#je_entry_type_filter').select2({
        placeholder: label_select_entry_types
    }).on('change', function() {
        $('#journal_entries_table').bootstrapTable('refresh');
    });
}

// Status filter
if (document.getElementById('je_status_filter')) {
    $('#je_status_filter').select2({
        placeholder: label_select_statuses
    }).on('change', function() {
        $('#journal_entries_table').bootstrapTable('refresh');
    });
}

// Handle delete for journal entries
$(document).on('click', '.delete[data-type="journal-entries"]', function() {
    var id = $(this).data('id');
    deleteRecord(id, 'journal-entries');
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