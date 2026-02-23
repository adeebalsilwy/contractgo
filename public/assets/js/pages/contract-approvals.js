$(document).ready(function() {
    // Initialize signature pad for approval
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
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

    // DataTable for approval history if on index page
    if($('#dataTable').length) {
        initializeDataTable('#dataTable', {
            columns: [
                {data: 'id'},
                {data: 'contract'},
                {data: 'approval_stage'},
                {data: 'submitted_at'},
                {data: 'status'},
                {data: 'actions', sortable: false, searchable: false}
            ],
            url: '/contract-approvals/list'
        });
    }
});

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