<div class="assignments-container">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <h5 class="mb-4">
                <i class="bx bx-user-check text-info me-2"></i>
                <?= get_label('workflow_assignments', 'Workflow Assignments') ?>
            </h5>
            
            <div class="assignments-list">
                <!-- Creator Assignment -->
                <div class="assignment-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-primary me-3 mt-1">
                            <i class="bx bx-user"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('created_by', 'Created By') ?></h6>
                                <span class="badge bg-success"><?= get_label('assigned', 'Assigned') ?></span>
                            </div>
                            <p class="text-muted small mb-2">
                                <?= get_label('extract_creator_description', 'Person who created this extract') ?>
                            </p>
                            @if($estimate_invoice->creator)
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ $estimate_invoice->creator->photo ? asset('storage/' . $estimate_invoice->creator->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ $estimate_invoice->creator->first_name }} {{ $estimate_invoice->creator->last_name }}"
                                         width="32" height="32">
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $estimate_invoice->creator->first_name }} {{ $estimate_invoice->creator->last_name }}</div>
                                    <small class="text-muted">{{ $estimate_invoice->creator->email ?? 'No email' }}</small>
                                </div>
                            </div>
                            @else
                            <div class="p-3 bg-primary bg-opacity-10 rounded text-center">
                                <small class="text-primary">
                                    <i class="bx bx-error-circle me-1"></i>
                                    <?= get_label('creator_not_found', 'Creator information not available') ?>
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Reviewer Assignment -->
                <div class="assignment-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-warning me-3 mt-1">
                            <i class="bx bx-group"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('reviewer', 'Reviewer') ?></h6>
                                <span class="badge bg-{{ $estimate_invoice->status !== 'draft' ? 'success' : 'warning' }}">
                                    {{ $estimate_invoice->status !== 'draft' ? get_label('assigned', 'Assigned') : get_label('pending_assignment', 'Pending Assignment') }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">
                                <?= get_label('reviewer_description', 'Person responsible for reviewing this extract') ?>
                            </p>
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="Reviewer"
                                         width="32" height="32">
                                </div>
                                <div>
                                    <div class="fw-medium"><?= get_label('system_reviewer', 'System Reviewer') ?></div>
                                    <small class="text-muted"><?= get_label('automated_review', 'Automated review process') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Approver Assignment -->
                <div class="assignment-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-success me-3 mt-1">
                            <i class="bx bx-shield-quarter"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('final_approver', 'Final Approver') ?></h6>
                                <span class="badge bg-{{ in_array($estimate_invoice->status, ['approved', 'archived']) ? 'success' : 'warning' }}">
                                    {{ in_array($estimate_invoice->status, ['approved', 'archived']) ? get_label('assigned', 'Assigned') : get_label('pending_assignment', 'Pending Assignment') }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">
                                <?= get_label('approver_description', 'Person with final approval authority') ?>
                            </p>
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="Approver"
                                         width="32" height="32">
                                </div>
                                <div>
                                    <div class="fw-medium"><?= get_label('authorized_approver', 'Authorized Approver') ?></div>
                                    <small class="text-muted"><?= get_label('management_approval', 'Management approval required') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Related Contract Assignment -->
                <div class="assignment-item">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-info me-3 mt-1">
                            <i class="bx bx-link"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('related_contract', 'Related Contract') ?></h6>
                                <span class="badge bg-{{ $estimate_invoice->contract_id ? 'success' : 'secondary' }}">
                                    {{ $estimate_invoice->contract_id ? get_label('linked', 'Linked') : get_label('not_linked', 'Not Linked') }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">
                                <?= get_label('contract_link_description', 'Contract that this extract is associated with') ?>
                            </p>
                            @if($estimate_invoice->contract_id)
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                <div class="avatar avatar-sm bg-label-info me-2">
                                    <i class="bx bx-file"></i>
                                </div>
                                <div>
                                    <div class="fw-medium"><?= get_label('contract', 'Contract') ?> #{{ $estimate_invoice->contract_id }}</div>
                                    @if(isset($relatedContract))
                                    <small class="text-muted">{{ $relatedContract->title ?? 'N/A' }}</small>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="p-3 bg-info bg-opacity-10 rounded text-center">
                                <small class="text-info">
                                    <i class="bx bx-error-circle me-1"></i>
                                    <?= get_label('no_contract_linked', 'No contract linked to this extract') ?>
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assignment Summary -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="mb-4">
                <i class="bx bx-stats text-primary me-2"></i>
                <?= get_label('assignment_summary', 'Assignment Summary') ?>
            </h5>
            
            <div class="assignment-summary">
                <!-- Assignment Status -->
                <div class="mb-4 p-3 bg-light rounded">
                    <h6 class="mb-3"><?= get_label('assignment_status', 'Assignment Status') ?></h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><?= get_label('total_assignments', 'Total Assignments') ?>:</span>
                        <span class="fw-bold">4</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-success"><?= get_label('completed', 'Completed') ?>:</span>
                        <span class="fw-bold text-success">
                            @php
                            $completedAssignments = 1; // Creator always assigned
                            if($estimate_invoice->status !== 'draft') $completedAssignments++;
                            if(in_array($estimate_invoice->status, ['approved', 'archived'])) $completedAssignments++;
                            if($estimate_invoice->contract_id) $completedAssignments++;
                            @endphp
                            {{ $completedAssignments }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-warning"><?= get_label('pending', 'Pending') ?>:</span>
                        <span class="fw-bold text-warning">{{ 4 - $completedAssignments }}</span>
                    </div>
                </div>
                
                <!-- Workflow Readiness -->
                <div class="p-3 bg-{{ $completedAssignments >= 3 ? 'success' : 'warning' }} bg-opacity-10 rounded">
                    <h6 class="mb-2">
                        <i class="bx {{ $completedAssignments >= 3 ? 'bx-check-circle text-success' : 'bx-error-circle text-warning' }} me-1"></i>
                        <?= get_label('workflow_readiness', 'Workflow Readiness') ?>
                    </h6>
                    @if($completedAssignments >= 3)
                    <p class="mb-0 text-success small">
                        <i class="bx bx-check me-1"></i>
                        <?= get_label('workflow_ready', 'Workflow is ready for processing') ?>
                    </p>
                    @else
                    <p class="mb-0 text-warning small">
                        <i class="bx bx-error me-1"></i>
                        <?= get_label('workflow_not_ready', 'Workflow needs more assignments to be ready') ?>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>