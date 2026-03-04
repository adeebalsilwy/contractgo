<div class="timeline-container">
    <div class="row">
        <!-- Workflow Timeline -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-time-five text-info me-2"></i>
                        <?= get_label('workflow_timeline', 'Workflow Timeline') ?>
                    </h5>
                    
                    <div class="timeline">
                        <!-- Contract Creation -->
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-badge bg-success me-3">
                                    <i class="bx bx-file"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('contract_created', 'Contract Created') ?></h6>
                                        <small class="text-muted">{{ format_date($contract->created_at, true) }}</small>
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('contract_creation_description', 'Contract was created and initial workflow setup completed') ?>
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <img src="{{ $contract->creator->photo ? asset('storage/' . $contract->creator->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $contract->creator->first_name }} {{ $contract->creator->last_name }}"
                                                 width="20" height="20">
                                        </div>
                                        <small class="text-muted">
                                            {{ $contract->creator->first_name }} {{ $contract->creator->last_name }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Workflow Initiated -->
                        @if($contract->workflow_status !== 'draft')
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-badge bg-primary me-3">
                                    <i class="bx bx-play-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('workflow_initiated', 'Workflow Initiated') ?></h6>
                                        <small class="text-muted">{{ format_date($contract->updated_at, true) }}</small>
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('workflow_initiation_description', 'Workflow process has been initiated and is now active') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Quantity Approval Events -->
                        @if($contract->quantity_approval_status)
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-badge bg-warning me-3">
                                    <i class="bx bx-list-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('quantity_approval', 'Quantity Approval') ?> - {{ ucfirst($contract->quantity_approval_status) }}</h6>
                                        @if($contract->quantity_approval_signed_at)
                                        <small class="text-muted">{{ format_date($contract->quantity_approval_signed_at, true) }}</small>
                                        @endif
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('quantity_approval_description', 'Site supervisor has uploaded quantities for approval') ?>
                                    </p>
                                    @if($contract->quantity_approval_signature)
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
                        @endif
                        
                        <!-- Management Review Events -->
                        @if($contract->management_review_status)
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-badge bg-info me-3">
                                    <i class="bx bx-group"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('management_review', 'Management Review') ?> - {{ ucfirst($contract->management_review_status) }}</h6>
                                        @if($contract->management_review_signed_at)
                                        <small class="text-muted">{{ format_date($contract->management_review_signed_at, true) }}</small>
                                        @endif
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('management_review_description', 'Management has reviewed and processed the quantities') ?>
                                    </p>
                                    @if($contract->management_review_signature)
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
                        @endif
                        
                        <!-- Accounting Review Events -->
                        @if($contract->accounting_review_status)
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-badge bg-primary me-3">
                                    <i class="bx bx-calculator"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('accounting_review', 'Accounting Review') ?> - {{ ucfirst($contract->accounting_review_status) }}</h6>
                                        @if($contract->accounting_review_signed_at)
                                        <small class="text-muted">{{ format_date($contract->accounting_review_signed_at, true) }}</small>
                                        @endif
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('accounting_review_description', 'Accounting integration with Onyx Pro completed') ?>
                                    </p>
                                    @if($contract->journal_entry_number)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small class="text-primary">
                                            <i class="bx bx-receipt me-1"></i>
                                            <?= get_label('journal_entry', 'Journal Entry') ?>: {{ $contract->journal_entry_number }}
                                        </small>
                                    </div>
                                    @endif
                                    @if($contract->accounting_review_signature)
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
                        @endif
                        
                        <!-- Final Approval Events -->
                        @if($contract->final_approval_status)
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-badge bg-dark me-3">
                                    <i class="bx bx-check-shield"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('final_approval', 'Final Approval') ?> - {{ ucfirst($contract->final_approval_status) }}</h6>
                                        @if($contract->final_approval_signed_at)
                                        <small class="text-muted">{{ format_date($contract->final_approval_signed_at, true) }}</small>
                                        @endif
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('final_approval_description', 'Final approval and archiving completed') ?>
                                    </p>
                                    @if($contract->final_approval_signature)
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
                        @endif
                        
                        <!-- Amendment Events -->
                        @if($contract->amendment_requested)
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-badge bg-warning me-3">
                                    <i class="bx bx-edit"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('amendment_requested', 'Amendment Requested') ?></h6>
                                        <small class="text-muted">{{ format_date($contract->amendment_requested_at, true) }}</small>
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('amendment_request_description', 'Contract amendment has been requested') ?>
                                    </p>
                                    @if($contract->amendment_reason)
                                    <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded">
                                        <small class="text-warning">
                                            <i class="bx bx-message-square-detail me-1"></i>
                                            {{ $contract->amendment_reason }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Archiving Event -->
                        @if($contract->is_archived)
                        <div class="timeline-item">
                            <div class="d-flex">
                                <div class="timeline-badge bg-secondary me-3">
                                    <i class="bx bx-archive"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('contract_archived', 'Contract Archived') ?></h6>
                                        <small class="text-muted">{{ format_date($contract->archived_at, true) }}</small>
                                    </div>
                                    <p class="mb-2 text-muted small">
                                        <?= get_label('contract_archived_description', 'Contract has been archived and closed') ?>
                                    </p>
                                    @if($contract->archivedBy)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <img src="{{ $contract->archivedBy->photo ? asset('storage/' . $contract->archivedBy->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $contract->archivedBy->first_name }} {{ $contract->archivedBy->last_name }}"
                                                 width="20" height="20">
                                        </div>
                                        <small class="text-muted">
                                            {{ $contract->archivedBy->first_name }} {{ $contract->archivedBy->last_name }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timeline Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-stats text-primary me-2"></i>
                        <?= get_label('timeline_summary', 'Timeline Summary') ?>
                    </h5>
                    
                    <div class="timeline-summary">
                        <!-- Duration Statistics -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6 class="mb-3"><?= get_label('duration_stats', 'Duration Statistics') ?></h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= get_label('total_duration', 'Total Duration') ?>:</span>
                                <span class="fw-bold">
                                    @php
                                    $totalDuration = $contract->created_at->diffInDays(now());
                                    @endphp
                                    {{ $totalDuration }} <?= get_label('days', 'days') ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= get_label('current_phase', 'Current Phase') ?>:</span>
                                <span class="fw-bold text-primary">{{ getWorkflowStatusText($contract->workflow_status) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><?= get_label('completion_rate', 'Completion Rate') ?>:</span>
                                <span class="fw-bold text-success">{{ $contract->progress_percentage }}%</span>
                            </div>
                        </div>
                        
                        <!-- Key Milestones -->
                        <div class="mb-4">
                            <h6 class="mb-3"><?= get_label('key_milestones', 'Key Milestones') ?></h6>
                            <div class="milestones-list">
                                <div class="milestone-item mb-3 p-2 rounded border {{ $contract->workflow_status !== 'draft' ? 'border-success' : 'border-light' }}">
                                    <div class="d-flex align-items-center">
                                        <i class="bx {{ $contract->workflow_status !== 'draft' ? 'bx-check-circle text-success' : 'bx-circle text-muted' }} me-2"></i>
                                        <div>
                                            <div class="fw-medium small"><?= get_label('workflow_initiated', 'Workflow Initiated') ?></div>
                                            @if($contract->workflow_status !== 'draft')
                                            <small class="text-muted">{{ format_date($contract->updated_at) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="milestone-item mb-3 p-2 rounded border {{ $contract->workflow_status === 'approved' ? 'border-success' : ($contract->workflow_status !== 'draft' ? 'border-warning' : 'border-light') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="bx {{ $contract->workflow_status === 'approved' ? 'bx-check-circle text-success' : ($contract->workflow_status !== 'draft' ? 'bx-time-five text-warning' : 'bx-circle text-muted') }} me-2"></i>
                                        <div>
                                            <div class="fw-medium small"><?= get_label('final_approval', 'Final Approval') ?></div>
                                            @if($contract->workflow_status === 'approved')
                                            <small class="text-muted">{{ format_date($contract->final_approval_signed_at) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="milestone-item p-2 rounded border {{ $contract->is_archived ? 'border-success' : 'border-light' }}">
                                    <div class="d-flex align-items-center">
                                        <i class="bx {{ $contract->is_archived ? 'bx-check-circle text-success' : 'bx-circle text-muted' }} me-2"></i>
                                        <div>
                                            <div class="fw-medium small"><?= get_label('contract_archived', 'Contract Archived') ?></div>
                                            @if($contract->is_archived)
                                            <small class="text-muted">{{ format_date($contract->archived_at) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity Summary -->
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <h6 class="mb-2">
                                <i class="bx bx-pulse text-info me-1"></i>
                                <?= get_label('recent_activity', 'Recent Activity') ?>
                            </h6>
                            <p class="mb-0 text-info small">
                                <i class="bx bx-time me-1"></i>
                                <?= get_label('last_updated', 'Last updated') ?>: {{ format_date($contract->updated_at, true) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>