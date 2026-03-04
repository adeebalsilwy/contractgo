@extends('layout')
@section('title')
    <?= get_label('workflow_testing', 'Workflow Testing') ?>
@endsection

@section('content')
    @php
        $menu = 'workflow-tests';
    @endphp

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between mb-4 mt-4">
            <div>
                <h2><?= get_label('workflow_testing', 'Workflow Testing') ?></h2>
                <p class="text-muted">Test and verify all workflow stages</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" onclick="runAutomatedTests()">
                    <i class='bx bx-test-tube'></i> Run Automated Tests
                </button>
                <button type="button" class="btn btn-success" onclick="simulateWorkflow()">
                    <i class='bx bx-play'></i> Simulate Complete Workflow
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-label-primary rounded-circle p-3 me-3">
                                <i class='bx bx-file fs-1'></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['total_contracts'] }}</h6>
                                <small class="text-muted">Total Contracts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-label-warning rounded-circle p-3 me-3">
                                <i class='bx bx-list-ul fs-1'></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['pending_quantities'] }}</h6>
                                <small class="text-muted">Pending Quantities</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-label-info rounded-circle p-3 me-3">
                                <i class='bx bx-check-circle fs-1'></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['pending_approvals'] }}</h6>
                                <small class="text-muted">Pending Approvals</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-label-success rounded-circle p-3 me-3">
                                <i class='bx bx-calculator fs-1'></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $stats['journal_entries'] }}</h6>
                                <small class="text-muted">Journal Entries</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Sections -->
        <div class="row mt-4">
            <!-- Stage 1: Contract Creation -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-file text-primary'></i>
                            Stage 1: Contract Creation
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Test contract creation with workflow role assignments</p>
                        <a href="{{ route('workflow.test.contract-creation') }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class='bx bx-link'></i> Test Contract Creation View
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stage 2: Quantity Upload -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-upload text-warning'></i>
                            Stage 2: Quantity Upload
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Test site supervisor quantity upload with documents</p>
                        <select class="form-select form-select-sm mb-2" id="contractForQuantityUpload">
                            <option value="">Select Contract...</option>
                            @foreach(\App\Models\Contract::where('workspace_id', getWorkspaceId())->get() as $contract)
                                <option value="{{ $contract->id }}">{{ $contract->title }}</option>
                            @endforeach
                        </select>
                        <button onclick="testQuantityUpload()" class="btn btn-outline-warning btn-sm w-100">
                            <i class='bx bx-link'></i> Test Quantity Upload
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stage 3: Quantity Approval -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-check-double text-info'></i>
                            Stage 3: Quantity Approval
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Test quantity approver review and decision</p>
                        <a href="{{ route('workflow.test.quantity-approval') }}" class="btn btn-outline-info btn-sm w-100">
                            <i class='bx bx-link'></i> Test Quantity Approval View
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stage 4: Contract Approval -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-user-check text-success'></i>
                            Stage 4: Management Approval
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Test management review and e-signature</p>
                        <select class="form-select form-select-sm mb-2" id="contractForApproval">
                            <option value="">Select Contract...</option>
                            @foreach(\App\Models\Contract::where('workspace_id', getWorkspaceId())->get() as $contract)
                                <option value="{{ $contract->id }}">{{ $contract->title }}</option>
                            @endforeach
                        </select>
                        <select class="form-select form-select-sm mb-2" id="approvalStage">
                            <option value="quantity_approval">Quantity Approval</option>
                            <option value="management_approval">Management Approval</option>
                            <option value="final_approval">Final Approval</option>
                        </select>
                        <button onclick="testContractApproval()" class="btn btn-outline-success btn-sm w-100">
                            <i class='bx bx-link'></i> Test Approval View
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stage 5: Accounting -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-calculator text-danger'></i>
                            Stage 5: Accounting Integration
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Test journal entry creation and Onyx Pro sync</p>
                        <a href="{{ route('workflow.test.journal-entry') }}" class="btn btn-outline-danger btn-sm w-100">
                            <i class='bx bx-link'></i> Test Journal Entry View
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stage 6: Amendments -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-edit text-secondary'></i>
                            Stage 6: Amendments
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Test amendment requests and re-flow</p>
                        <select class="form-select form-select-sm mb-2" id="contractForAmendment">
                            <option value="">Select Contract...</option>
                            @foreach(\App\Models\Contract::where('workspace_id', getWorkspaceId())->get() as $contract)
                                <option value="{{ $contract->id }}">{{ $contract->title }}</option>
                            @endforeach
                        </select>
                        <button onclick="testAmendment()" class="btn btn-outline-secondary btn-sm w-100">
                            <i class='bx bx-link'></i> Test Amendment View
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stage 7: Obligations -->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class='bx bx-task text-primary'></i>
                            Stage 7: Obligations
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Test contract obligations tracking</p>
                        <a href="{{ route('workflow.test.obligations') }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class='bx bx-link'></i> Test Obligations View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Results Modal -->
        <div class="modal fade" id="testResultsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Automated Test Results</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="testResultsContent"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script>
function runAutomatedTests() {
    Swal.fire({
        title: 'Running Automated Tests...',
        text: 'Please wait while we test all workflow components',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('{{ route("workflow.test.run") }}')
        .then(response => response.json())
        .then(data => {
            Swal.close();
            displayTestResults(data.results);
        })
        .catch(error => {
            Swal.fire('Error', 'Failed to run tests: ' + error.message, 'error');
        });
}

function displayTestResults(results) {
    const modal = new bootstrap.Modal(document.getElementById('testResultsModal'));
    const content = document.getElementById('testResultsContent');
    
    let html = '<div class="list-group">';
    results.forEach(result => {
        const icon = result.status.includes('✓') ? 'check-circle' : (result.status.includes('✗') ? 'x-circle' : 'info-circle');
        const color = result.status.includes('✓') ? 'success' : (result.status.includes('✗') ? 'danger' : 'info');
        
        html += `
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 text-${color}">
                        <i class='bx bx-${icon}'></i> ${result.view || result.test || 'Test'}
                    </h6>
                    <small class="badge bg-${color}">${result.status}</small>
                </div>
                <p class="mb-1 small">${result.message}</p>
            </div>
        `;
    });
    html += '</div>';
    
    // Add summary
    html += `
        <div class="mt-3 p-3 bg-light rounded">
            <strong>Summary:</strong><br>
            Total Tests: ${data.summary.total_tests}<br>
            Passed: <span class="text-success">${data.summary.passed}</span><br>
            Failed: <span class="text-danger">${data.summary.failed}</span>
        </div>
    `;
    
    content.innerHTML = html;
    modal.show();
}

function simulateWorkflow() {
    Swal.fire({
        title: 'Simulating Complete Workflow?',
        text: "This will create a test contract and simulate all workflow stages",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, simulate!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Simulating...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('{{ route("workflow.test.simulate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire(
                        'Success!',
                        'Workflow simulation completed successfully',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Simulation failed: ' + error.message, 'error');
            });
        }
    });
}

function testQuantityUpload() {
    const contractId = document.getElementById('contractForQuantityUpload').value;
    if (!contractId) {
        Swal.fire('Error', 'Please select a contract', 'error');
        return;
    }
    window.open(`{{ url('workflow/test/quantity-upload') }}/${contractId}`, '_blank');
}

function testContractApproval() {
    const contractId = document.getElementById('contractForApproval').value;
    const stage = document.getElementById('approvalStage').value;
    
    if (!contractId) {
        Swal.fire('Error', 'Please select a contract', 'error');
        return;
    }
    
    window.open(`{{ url('workflow/test/contract-approval') }}/${contractId}/${stage}`, '_blank');
}

function testAmendment() {
    const contractId = document.getElementById('contractForAmendment').value;
    if (!contractId) {
        Swal.fire('Error', 'Please select a contract', 'error');
        return;
    }
    window.open(`{{ url('workflow/test/amendment') }}/${contractId}`, '_blank');
}
</script>
@endsection
