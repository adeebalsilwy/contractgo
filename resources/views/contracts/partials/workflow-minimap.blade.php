<!-- Workflow Mini Map Component -->
<div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bx bx-sitemap"></i> <?= get_label('workflow_status', 'Workflow Status') ?></h5>
    </div>
    <div class="card-body">
        <!-- Workflow Progress Bar -->
        <div class="workflow-progress-container mb-4">
            <div class="progress" style="height: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: {{ $workflowProgress }}%;" 
                     aria-valuenow="{{ $workflowProgress }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                </div>
            </div>
            <small class="text-muted"><?= get_label('workflow_completion', 'Workflow Completion') ?>: {{ $workflowProgress }}%</small>
        </div>

        <!-- Workflow Stages Visualization -->
        <div class="workflow-stages">
            <div class="row">
                <!-- Site Supervisor Stage -->
                <div class="col text-center mb-3">
                    <div class="workflow-stage-card {{ $contract->workflow_status === 'quantity_approval' || in_array($contract->workflow_status, ['management_review', 'accounting_review', 'final_approval', 'approved']) ? 'stage-completed' : '' }} {{ $contract->workflow_status === 'quantity_approval' ? 'stage-active' : '' }}">
                        <div class="avatar avatar-md bg-label-primary mb-2">
                            <i class="bx bx-user fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-1"><?= get_label('site_supervisor', 'Site Supervisor') ?></h6>
                        <small class="text-muted">{{ $contract->siteSupervisor ? $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name : get_label('not_assigned', 'Not Assigned') }}</small>
                        @if($contract->workflow_status === 'quantity_approval')
                            <span class="badge bg-primary mt-1"><?= get_label('current_stage', 'Current Stage') ?></span>
                        @elseif(in_array($contract->workflow_status, ['management_review', 'accounting_review', 'final_approval', 'approved']))
                            <span class="badge bg-success mt-1"><i class="bx bx-check"></i> <?= get_label('completed', 'Completed') ?></span>
                        @else
                            <span class="badge bg-secondary mt-1"><?= get_label('pending', 'Pending') ?></span>
                        @endif
                    </div>
                </div>

                <!-- Quantity Approver Stage -->
                <div class="col text-center mb-3">
                    <div class="workflow-stage-card {{ $contract->workflow_status === 'management_review' || in_array($contract->workflow_status, ['accounting_review', 'final_approval', 'approved']) ? 'stage-completed' : '' }} {{ $contract->workflow_status === 'management_review' ? 'stage-active' : '' }}">
                        <div class="avatar avatar-md bg-label-warning mb-2">
                            <i class="bx bx-check fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-1"><?= get_label('quantity_approver', 'Quantity Approver') ?></h6>
                        <small class="text-muted">{{ $contract->quantityApprover ? $contract->quantityApprover->first_name . ' ' . $contract->quantityApprover->last_name : get_label('not_assigned', 'Not Assigned') }}</small>
                        @if($contract->workflow_status === 'management_review')
                            <span class="badge bg-warning mt-1"><?= get_label('current_stage', 'Current Stage') ?></span>
                        @elseif(in_array($contract->workflow_status, ['accounting_review', 'final_approval', 'approved']))
                            <span class="badge bg-success mt-1"><i class="bx bx-check"></i> <?= get_label('completed', 'Completed') ?></span>
                        @else
                            <span class="badge bg-secondary mt-1"><?= get_label('pending', 'Pending') ?></span>
                        @endif
                    </div>
                </div>

                <!-- Accountant Stage -->
                <div class="col text-center mb-3">
                    <div class="workflow-stage-card {{ $contract->workflow_status === 'accounting_review' || in_array($contract->workflow_status, ['final_approval', 'approved']) ? 'stage-completed' : '' }} {{ $contract->workflow_status === 'accounting_review' ? 'stage-active' : '' }}">
                        <div class="avatar avatar-md bg-label-success mb-2">
                            <i class="bx bx-dollar fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-1"><?= get_label('accountant', 'Accountant') ?></h6>
                        <small class="text-muted">{{ $contract->accountant ? $contract->accountant->first_name . ' ' . $contract->accountant->last_name : get_label('not_assigned', 'Not Assigned') }}</small>
                        @if($contract->workflow_status === 'accounting_review')
                            <span class="badge bg-success mt-1"><?= get_label('current_stage', 'Current Stage') ?></span>
                        @elseif(in_array($contract->workflow_status, ['final_approval', 'approved']))
                            <span class="badge bg-success mt-1"><i class="bx bx-check"></i> <?= get_label('completed', 'Completed') ?></span>
                        @else
                            <span class="badge bg-secondary mt-1"><?= get_label('pending', 'Pending') ?></span>
                        @endif
                    </div>
                </div>

                <!-- Reviewer Stage -->
                <div class="col text-center mb-3">
                    <div class="workflow-stage-card {{ $contract->workflow_status === 'final_approval' || $contract->workflow_status === 'approved' ? 'stage-completed' : '' }} {{ $contract->workflow_status === 'final_approval' ? 'stage-active' : '' }}">
                        <div class="avatar avatar-md bg-label-info mb-2">
                            <i class="bx bx-edit fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-1"><?= get_label('reviewer', 'Reviewer') ?></h6>
                        <small class="text-muted">{{ $contract->reviewer ? $contract->reviewer->first_name . ' ' . $contract->reviewer->last_name : get_label('not_assigned', 'Not Assigned') }}</small>
                        @if($contract->workflow_status === 'final_approval')
                            <span class="badge bg-info mt-1"><?= get_label('current_stage', 'Current Stage') ?></span>
                        @elseif($contract->workflow_status === 'approved')
                            <span class="badge bg-success mt-1"><i class="bx bx-check"></i> <?= get_label('completed', 'Completed') ?></span>
                        @else
                            <span class="badge bg-secondary mt-1"><?= get_label('pending', 'Pending') ?></span>
                        @endif
                    </div>
                </div>

                <!-- Final Approver Stage -->
                <div class="col text-center mb-3">
                    <div class="workflow-stage-card {{ $contract->workflow_status === 'approved' ? 'stage-completed' : '' }}">
                        <div class="avatar avatar-md bg-label-danger mb-2">
                            <i class="bx bx-badge-check fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-1"><?= get_label('final_approver', 'Final Approver') ?></h6>
                        <small class="text-muted">{{ $contract->finalApprover ? $contract->finalApprover->first_name . ' ' . $contract->finalApprover->last_name : get_label('not_assigned', 'Not Assigned') }}</small>
                        @if($contract->workflow_status === 'approved')
                            <span class="badge bg-success mt-1"><i class="bx bx-check-double"></i> <?= get_label('fully_approved', 'Fully Approved') ?></span>
                        @else
                            <span class="badge bg-secondary mt-1"><?= get_label('pending', 'Pending') ?></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Arrows (Desktop only) -->
        <div class="d-none d-lg-flex justify-content-between align-items-center mt-n3">
            <div class="text-primary"><i class="bx bx-right-arrow-alt fs-4"></i></div>
            <div class="text-primary"><i class="bx bx-right-arrow-alt fs-4"></i></div>
            <div class="text-primary"><i class="bx bx-right-arrow-alt fs-4"></i></div>
            <div class="text-primary"><i class="bx bx-right-arrow-alt fs-4"></i></div>
        </div>

        <!-- Additional Information -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bx bx-info-circle me-2 fs-4"></i>
                    <div>
                        <strong><?= get_label('workflow_note', 'Workflow Note') ?>:</strong>
                        <span class="ms-1"><?= get_label('workflow_sequential_note', 'Each stage must be completed sequentially. The contract will move through each approval stage automatically.') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.workflow-stage-card {
    padding: 15px;
    border-radius: 10px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.workflow-stage-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.workflow-stage-card.stage-active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

.workflow-stage-card.stage-active h6,
.workflow-stage-card.stage-active small {
    color: white;
}

.workflow-stage-card.stage-completed {
    background-color: #d1e7dd;
    border-color: #198754;
}

.workflow-progress-container {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

@media (max-width: 991px) {
    .workflow-stages .row {
        flex-direction: column;
    }
    
    .d-none.d-lg-flex.justify-content-between {
        display: none !important;
    }
}
</style>
