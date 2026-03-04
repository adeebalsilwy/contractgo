<div class="workflow-steps-container">
    <!-- Workflow Progress Visualization -->
    <div class="workflow-progress mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="bx bx-current-location me-2"></i>
                <?= get_label('current_stage', 'Current Stage') ?>: 
                <span class="text-capitalize">{{ getExtractStatusText($estimate_invoice->status) }}</span>
            </h6>
            <span class="badge bg-{{ getExtractStatusColor($estimate_invoice->status) }} fs-6">
                {{ $estimate_invoice->type == 'estimate' ? 'مستخلص' : 'فاتورة' }}
            </span>
        </div>
        
        <!-- Status Progress -->
        <div class="d-flex align-items-center gap-3">
            <div class="flex-grow-1">
                <div class="progress" style="height: 10px;">
                    @php
                    $statusProgress = [
                        'draft' => 20,
                        'submitted' => 40,
                        'under_review' => 60,
                        'approved' => 100,
                        'rejected' => 30,
                        'archived' => 100
                    ];
                    $progressPercentage = $statusProgress[$estimate_invoice->status] ?? 0;
                    @endphp
                    <div class="progress-bar bg-{{ getExtractProgressColor($progressPercentage) }}" 
                         role="progressbar" 
                         style="width: {{ $progressPercentage }}%" 
                         aria-valuenow="{{ $progressPercentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
            <span class="fw-bold text-{{ getExtractProgressColor($progressPercentage) }}">{{ $progressPercentage }}%</span>
        </div>
    </div>

    <!-- Workflow Steps -->
    <div class="workflow-steps">
        @php
        $workflowSteps = [
            [
                'id' => 'creation',
                'title' => get_label('extract_creation', 'Extract Creation'),
                'icon' => 'bx-file',
                'description' => get_label('extract_created_description', 'Extract has been created and is ready for submission'),
                'status' => 'completed',
                'completed_date' => $estimate_invoice->created_at,
                'assignee' => $estimate_invoice->creator ?? null
            ],
            [
                'id' => 'submission',
                'title' => get_label('submission', 'Submission'),
                'icon' => 'bx-send',
                'description' => get_label('extract_submitted_description', 'Extract has been submitted for review'),
                'status' => in_array($estimate_invoice->status, ['submitted', 'under_review', 'approved', 'archived']) ? 'completed' : 'pending',
                'completed_date' => in_array($estimate_invoice->status, ['submitted', 'under_review', 'approved', 'archived']) ? $estimate_invoice->updated_at : null,
                'assignee' => null
            ],
            [
                'id' => 'review',
                'title' => get_label('review_process', 'Review Process'),
                'icon' => 'bx-group',
                'description' => get_label('extract_review_description', 'Extract is under review by authorized personnel'),
                'status' => in_array($estimate_invoice->status, ['under_review', 'approved', 'archived']) ? 'completed' : (in_array($estimate_invoice->status, ['submitted']) ? 'current' : 'upcoming'),
                'completed_date' => in_array($estimate_invoice->status, ['approved', 'archived']) ? $estimate_invoice->updated_at : null,
                'assignee' => null
            ],
            [
                'id' => 'approval',
                'title' => get_label('final_approval', 'Final Approval'),
                'icon' => 'bx-check-shield',
                'description' => get_label('extract_approval_description', 'Final approval and authorization'),
                'status' => in_array($estimate_invoice->status, ['approved', 'archived']) ? 'completed' : (in_array($estimate_invoice->status, ['under_review']) ? 'current' : 'upcoming'),
                'completed_date' => $estimate_invoice->status === 'approved' || $estimate_invoice->status === 'archived' ? $estimate_invoice->updated_at : null,
                'assignee' => null
            ]
        ];
        @endphp

        @foreach($workflowSteps as $index => $step)
        <div class="workflow-step {{ $step['status'] === 'completed' ? 'completed' : ($step['status'] === 'current' ? 'current' : 'upcoming') }} mb-3">
            <div class="d-flex">
                <!-- Step Indicator -->
                <div class="step-indicator me-3">
                    <div class="avatar {{ $step['status'] === 'completed' ? 'bg-success' : ($step['status'] === 'current' ? 'bg-warning' : 'bg-light') }} rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bx {{ $step['icon'] }} {{ $step['status'] === 'completed' ? 'text-white' : ($step['status'] === 'current' ? 'text-white' : 'text-muted') }}"></i>
                    </div>
                    @if($index < count($workflowSteps) - 1)
                    <div class="step-line {{ $step['status'] === 'completed' ? 'bg-success' : 'bg-light' }}" style="width: 2px; height: 30px; margin: 5px auto;"></div>
                    @endif
                </div>
                
                <!-- Step Content -->
                <div class="step-content flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold {{ $step['status'] === 'completed' ? 'text-success' : ($step['status'] === 'current' ? 'text-warning' : 'text-muted') }}">
                                {{ $step['title'] }}
                            </h6>
                            <p class="mb-2 text-muted small">{{ $step['description'] }}</p>
                        </div>
                        <span class="badge bg-{{ $step['status'] === 'completed' ? 'success' : ($step['status'] === 'current' ? 'warning' : 'secondary') }} ms-2">
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
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Workflow Controls -->
    <div class="workflow-controls mt-4 p-3 bg-light rounded">
        <h6 class="mb-3">
            <i class="bx bx-play-circle text-primary me-2"></i>
            <?= get_label('workflow_controls', 'Workflow Controls') ?>
        </h6>
        <div class="d-flex flex-wrap gap-2">
            @if(auth()->user()->can('submit_estimates_invoices') && $estimate_invoice->status === 'draft')
            <button type="button" class="btn btn-sm btn-success" onclick="submitExtract({{ $estimate_invoice->id }})">
                <i class="bx bx-send me-1"></i><?= get_label('submit_for_review', 'Submit for Review') ?>
            </button>
            @endif
            
            @if(auth()->user()->can('approve_estimates_invoices') && in_array($estimate_invoice->status, ['submitted', 'under_review']))
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal">
                <i class="bx bx-check-double me-1"></i><?= get_label('process_approval', 'Process Approval') ?>
            </button>
            @endif
            
            @if(auth()->user()->can('reject_estimates_invoices') && in_array($estimate_invoice->status, ['submitted', 'under_review']))
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectionModal">
                <i class="bx bx-x me-1"></i><?= get_label('reject', 'Reject') ?>
            </button>
            @endif
            
            @if($estimate_invoice->status === 'approved' && !$estimate_invoice->is_archived && auth()->user()->can('archive_estimates_invoices'))
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="archiveExtract({{ $estimate_invoice->id }})">
                <i class="bx bx-archive me-1"></i><?= get_label('archive_extract', 'Archive Extract') ?>
            </button>
            @endif
        </div>
    </div>
</div>