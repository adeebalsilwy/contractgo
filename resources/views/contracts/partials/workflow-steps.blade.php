<div class="workflow-steps-container">
    <!-- Workflow Progress Visualization -->
    <div class="workflow-progress mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="bx bx-current-location me-2"></i>
                <?= get_label('current_stage', 'Current Stage') ?>: 
                <span class="text-capitalize">{{ getWorkflowStatusText($contract->workflow_status) }}</span>
            </h6>
            <span class="badge bg-{{ getWorkflowStatusColor($contract->workflow_status) }} fs-6">
                {{ $contract->progress_percentage }}% <?= get_label('complete', 'Complete') ?>
            </span>
        </div>
        
        <!-- Progress Bar -->
        <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-{{ getProgressColor($contract->progress_percentage) }}" 
                 role="progressbar" 
                 style="width: {{ $contract->progress_percentage }}%" 
                 aria-valuenow="{{ $contract->progress_percentage }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
            </div>
        </div>
    </div>

    <!-- Workflow Steps -->
    <div class="workflow-steps">
        @php
        $workflowSteps = [
            [
                'id' => 'quantity_approval',
                'title' => get_label('quantity_approval', 'Quantity Approval'),
                'icon' => 'bx-list-check',
                'description' => get_label('site_supervisor_quantity_upload', 'Site supervisor quantity upload and approval'),
                'status' => getApprovalStageStatus($contract, 'quantity_approval'),
                'completed_date' => $contract->quantity_approval_signed_at ?? null,
                'assignee' => $contract->quantityApprover ?? null
            ],
            [
                'id' => 'management_review',
                'title' => get_label('management_review', 'Management Review'),
                'icon' => 'bx-group',
                'description' => get_label('management_review_description', 'Management review and approval of quantities'),
                'status' => getApprovalStageStatus($contract, 'management_review'),
                'completed_date' => $contract->management_review_signed_at ?? null,
                'assignee' => $contract->reviewer ?? null
            ],
            [
                'id' => 'accounting_review',
                'title' => get_label('accounting_review', 'Accounting Review'),
                'icon' => 'bx-calculator',
                'description' => get_label('accounting_integration', 'Accounting integration with Onyx Pro'),
                'status' => getApprovalStageStatus($contract, 'accounting_review'),
                'completed_date' => $contract->accounting_review_signed_at ?? null,
                'assignee' => $contract->accountant ?? null
            ],
            [
                'id' => 'final_approval',
                'title' => get_label('final_approval', 'Final Approval'),
                'icon' => 'bx-check-shield',
                'description' => get_label('final_review_archiving', 'Final review and archiving'),
                'status' => getApprovalStageStatus($contract, 'final_approval'),
                'completed_date' => $contract->final_approval_signed_at ?? null,
                'assignee' => $contract->finalApprover ?? null
            ]
        ];
        @endphp

        @foreach($workflowSteps as $index => $step)
        <div class="workflow-step {{ $step['status'] === 'approved' ? 'completed' : ($step['status'] === 'pending' ? 'current' : 'upcoming') }} mb-3">
            <div class="d-flex">
                <!-- Step Indicator -->
                <div class="step-indicator me-3">
                    <div class="avatar {{ $step['status'] === 'approved' ? 'bg-success' : ($step['status'] === 'pending' ? 'bg-warning' : 'bg-light') }} rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bx {{ $step['icon'] }} {{ $step['status'] === 'approved' ? 'text-white' : ($step['status'] === 'pending' ? 'text-white' : 'text-muted') }}"></i>
                    </div>
                    @if($index < count($workflowSteps) - 1)
                    <div class="step-line {{ $step['status'] === 'approved' ? 'bg-success' : 'bg-light' }}" style="width: 2px; height: 30px; margin: 5px auto;"></div>
                    @endif
                </div>
                
                <!-- Step Content -->
                <div class="step-content flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold {{ $step['status'] === 'approved' ? 'text-success' : ($step['status'] === 'pending' ? 'text-warning' : 'text-muted') }}">
                                {{ $step['title'] }}
                            </h6>
                            <p class="mb-2 text-muted small">{{ $step['description'] }}</p>
                        </div>
                        <span class="badge bg-{{ getApprovalStageColor($step['status']) }} ms-2">
                            {{ ucfirst($step['status']) }}
                        </span>
                    </div>
                    
                    <!-- Assignee and Date Information -->
                    <div class="d-flex flex-wrap gap-3">
                        @if($step['assignee'])
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xs me-2">
                                <img src="{{ $step['assignee']->photo ? asset('storage/' . $step['assignee']->photo) : asset('storage/photos/no-image.jpg') }}" 
                                     class="rounded-circle" 
                                     alt="{{ $step['assignee']->first_name }} {{ $step['assignee']->last_name }}"
                                     width="24" height="24">
                            </div>
                            <small class="text-muted">
                                {{ $step['assignee']->first_name }} {{ $step['assignee']->last_name }}
                            </small>
                        </div>
                        @endif
                        
                        @if($step['completed_date'])
                        <div class="d-flex align-items-center">
                            <i class="bx bx-calendar-check text-success me-1 small"></i>
                            <small class="text-muted">
                                {{ format_date($step['completed_date']) }}
                            </small>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Electronic Signature -->
                    @if($step['status'] === 'approved' && isset($contract->{$step['id'] . '_signature'}))
                    <div class="mt-2 p-2 bg-light rounded">
                        <small class="text-success">
                            <i class="bx bx-signature me-1"></i>
                            <?= get_label('electronic_signature_applied', 'Electronic signature applied') ?>
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Workflow Controls -->
    @if($contract->workflow_status !== 'approved' && $contract->workflow_status !== 'archived')
    <div class="workflow-controls mt-4 p-3 bg-light rounded">
        <h6 class="mb-3">
            <i class="bx bx-play-circle text-primary me-2"></i>
            <?= get_label('workflow_controls', 'Workflow Controls') ?>
        </h6>
        <div class="d-flex flex-wrap gap-2">
            @if(auth()->user()->can('approve_contract_quantities') && $contract->workflow_status === 'quantity_approval')
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveQuantityModal">
                <i class="bx bx-check me-1"></i><?= get_label('approve_quantities', 'Approve Quantities') ?>
            </button>
            @endif
            
            @if(auth()->user()->can('manage_contract_approvals') && in_array($contract->workflow_status, ['management_review', 'accounting_review', 'final_approval']))
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal">
                <i class="bx bx-check-double me-1"></i><?= get_label('process_approval', 'Process Approval') ?>
            </button>
            @endif
            
            @if(auth()->user()->can('reject_contract_approvals'))
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectionModal">
                <i class="bx bx-x me-1"></i><?= get_label('reject', 'Reject') ?>
            </button>
            @endif
        </div>
    </div>
    @endif
</div>