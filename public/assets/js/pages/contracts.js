
'use strict';
function queryParams(p) {
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
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}


window.icons = {
    refresh: 'bx-refresh',
    toggleOn: 'bx-toggle-right',
    toggleOff: 'bx-toggle-left'
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>'
}

function idFormatter(value, row, index) {
    return [
        '<a href="' + baseUrl + '/contracts/' + row.id + '" title="' + label_view_details + '">' + label_contract_id_prefix + row.id + '</a>'
    ]
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
if ($('#promisor_sign').length) {
    var canvas = document.getElementById('promisor_sign');
    var signaturePad = new SignaturePad(canvas);
    $('#create_contract_sign_modal #submit_btn').on('click', function (e) {
        e.preventDefault();
        if (!isSignatureEmpty()) {
            var img_data = signaturePad.toDataURL('image/png');
            $("<input>").attr({
                type: "hidden",
                name: "signatureImage",
                value: img_data
            }).appendTo("#contract_sign_form");
            $("#contract_sign_form").submit();
        } else {
            toastr.error('Please draw signature.');
        }
    });
}

$('#contract_sign_form').on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var submit_btn = $(this).find('#submit_btn');
    var btn_html = submit_btn.html();
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
        },
        beforeSend: function () {
            submit_btn.html(label_please_wait);
            submit_btn.attr('disabled', true);
        },
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            if (result.error == false) {
                location.reload();
            }
            else {
                submit_btn.html(btn_html);
                submit_btn.attr('disabled', false);
                toastr.error(result.message);
            }
        }
    });

});

$(document).on('click', '#reset_promisor_sign', function (e) {
    e.preventDefault();
    signaturePad.clear();
});

function isSignatureEmpty() {
    // Get the data URL of the canvas
    var dataURL = signaturePad.toDataURL();

    // Define an initial state or known empty state
    var initialStateDataURL = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAfQAAAC1CAYAAACppQ33AAAAAXNSR0IArs4c6QAAB6hJREFUeF7t1QENAAAIwzDwbxodLMXBy5PvOAIECBAgQOC9wL5PIAABAgQIECAwBl0JCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIGPfBEEQgQIECAgEHXAQIECBAgEBAw6IEnikCAAAECBAy6DhAgQIAAgYCAQQ88UQQCBAgQIGDQdYAAAQIECAQEDHrgiSIQIECAAAGDrgMECBAgQCAgYNADTxSBAAECBAgYdB0gQIAAAQIBAYMeeKIIBAgQIEDAoOsAAQIECBAICBj0wBNFIECAAAECBl0HCBAgQIBAQMCgB54oAgECBAgQMOg6QIAAAQIEAgIHjJAAtgfRyRUAAAAASUVORK5CYII='; // Replace with your empty state data URL

    // Check if the data URL matches the initial state data URL
    return dataURL === initialStateDataURL;
}

$(document).on('click', '.delete_contract_sign', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#delete_contract_sign_modal').off('click', '#confirmDelete');
    $('#delete_contract_sign_modal').on('click', '#confirmDelete', function (e) {
        e.preventDefault();
        $.ajax({
            url: baseUrl + '/contracts/delete-sign/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value') // Replace with your method of getting the CSRF token
            },
            beforeSend: function () {
                $('#confirmDelete').html(label_please_wait).attr('disabled', true);
            },
            success: function (response) {
                location.reload();
            },
            error: function (data) {
                location.reload();
            }

        });
    });
});

$('#contract_start_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#contract_start_date_from').val(startDate);
    $('#contract_start_date_to').val(endDate);

    $('#contracts_table').bootstrapTable('refresh');
});

$('#contract_start_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#contract_start_date_from').val('');
    $('#contract_start_date_to').val('');
    $('#contract_start_date_between').val('');
    picker.setStartDate(moment());
    picker.setEndDate(moment());
    picker.updateElement();
    $('#contracts_table').bootstrapTable('refresh');
});

$('#contract_end_date_between').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate.format('YYYY-MM-DD');
    var endDate = picker.endDate.format('YYYY-MM-DD');

    $('#contract_end_date_from').val(startDate);
    $('#contract_end_date_to').val(endDate);

    $('#contracts_table').bootstrapTable('refresh');
});
$('#contract_end_date_between').on('cancel.daterangepicker', function (ev, picker) {
    $('#contract_end_date_from').val('');
    $('#contract_end_date_to').val('');
    $('#contract_end_date_between').val('');
    picker.setStartDate(moment());
    picker.setEndDate(moment());
    picker.updateElement();
    $('#contracts_table').bootstrapTable('refresh');
});

$(document).ready(function () {
    $('#contract_date_between').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        $('#contract_date_between_from').val(startDate);
        $('#contract_date_between_to').val(endDate);
        $('#contracts_table').bootstrapTable('refresh');
    });

    // Cancel event to clear values
    $('#contract_date_between').on('cancel.daterangepicker', function (ev, picker) {
        $('#contract_date_between_from').val('');
        $('#contract_date_between_to').val('');
        $(this).val('');
        picker.setStartDate(moment());
        picker.setEndDate(moment());
        picker.updateElement();
        $('#contracts_table').bootstrapTable('refresh');
    });
});

addDebouncedEventListener('#status_filter, #workflow_status_filter, #archived_filter, #client_filter, #project_filter, #type_filter', 'change', function (e, refreshTable) {
    e.preventDefault();
    if (typeof refreshTable === 'undefined' || refreshTable) {
        $('#contracts_table').bootstrapTable('refresh');
    }
});
$(document).on('click', '.clear-contracts-filters', function (e) {
    e.preventDefault();
    $('#contract_date_between').val('');
    $('#contract_date_between_from').val('');
    $('#contract_date_between_to').val('');
    $('#contract_start_date_between').val('');
    $('#contract_end_date_between').val('');
    $('#contract_start_date_from').val('');
    $('#contract_start_date_to').val('');
    $('#contract_end_date_from').val('');
    $('#contract_end_date_to').val('');
    $('#status_filter').val('').trigger('change', [0]);
    $('#workflow_status_filter').val('').trigger('change', [0]);
    $('#archived_filter').val('').trigger('change', [0]);
    $('#client_filter').val('').trigger('change', [0]);
    $('#project_filter').val('').trigger('change', [0]);
    $('#type_filter').val('').trigger('change', [0]);
    $('#contracts_table').bootstrapTable('refresh');
})






