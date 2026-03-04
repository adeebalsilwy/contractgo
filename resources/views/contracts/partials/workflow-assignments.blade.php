<div class="workflow-assignments-container">
    <div class="row">
        <!-- Workflow Role Assignments -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-user-check text-info me-2"></i>
                        <?= get_label('workflow_assignments', 'Workflow Assignments') ?>
                    </h5>
                    
                    <div class="assignments-list">
                        <!-- Site Supervisor Assignment -->
                        <div class="assignment-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="avatar bg-label-warning me-3 mt-1">
                                    <i class="bx bx-hard-hat"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('site_supervisor', 'Site Supervisor') ?></h6>
                                        <span class="badge bg-{{ $contract->site_supervisor_id ? 'success' : 'danger' }}">
                                            {{ $contract->site_supervisor_id ? get_label('assigned', 'Assigned') : get_label('unassigned', 'Unassigned') }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <?= get_label('site_supervisor_description', 'Responsible for uploading and managing site quantities') ?>
                                    </p>
                                    @if($contract->siteSupervisor)
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="{{ $contract->siteSupervisor->photo ? asset('storage/' . $contract->siteSupervisor->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $contract->siteSupervisor->first_name }} {{ $contract->siteSupervisor->last_name }}"
                                                 width="32" height="32">
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $contract->siteSupervisor->first_name }} {{ $contract->siteSupervisor->last_name }}</div>
                                            <small class="text-muted">{{ $contract->siteSupervisor->email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <div class="p-3 bg-warning bg-opacity-10 rounded text-center">
                                        <small class="text-warning">
                                            <i class="bx bx-error-circle me-1"></i>
                                            <?= get_label('no_site_supervisor_assigned', 'No site supervisor assigned') ?>
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quantity Approver Assignment -->
                        <div class="assignment-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="avatar bg-label-info me-3 mt-1">
                                    <i class="bx bx-check-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('quantity_approver', 'Quantity Approver') ?></h6>
                                        <span class="badge bg-{{ $contract->quantity_approver_id ? 'success' : 'danger' }}">
                                            {{ $contract->quantity_approver_id ? get_label('assigned', 'Assigned') : get_label('unassigned', 'Unassigned') }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <?= get_label('quantity_approver_description', 'Approves quantities uploaded by site supervisor') ?>
                                    </p>
                                    @if($contract->quantityApprover)
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="{{ $contract->quantityApprover->photo ? asset('storage/' . $contract->quantityApprover->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $contract->quantityApprover->first_name }} {{ $contract->quantityApprover->last_name }}"
                                                 width="32" height="32">
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $contract->quantityApprover->first_name }} {{ $contract->quantityApprover->last_name }}</div>
                                            <small class="text-muted">{{ $contract->quantityApprover->email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <div class="p-3 bg-info bg-opacity-10 rounded text-center">
                                        <small class="text-info">
                                            <i class="bx bx-error-circle me-1"></i>
                                            <?= get_label('no_quantity_approver_assigned', 'No quantity approver assigned') ?>
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Accountant Assignment -->
                        <div class="assignment-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="avatar bg-label-primary me-3 mt-1">
                                    <i class="bx bx-calculator"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('accountant', 'Accountant') ?></h6>
                                        <span class="badge bg-{{ $contract->accountant_id ? 'success' : 'danger' }}">
                                            {{ $contract->accountant_id ? get_label('assigned', 'Assigned') : get_label('unassigned', 'Unassigned') }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <?= get_label('accountant_description', 'Handles accounting integration with Onyx Pro') ?>
                                    </p>
                                    @if($contract->accountant)
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="{{ $contract->accountant->photo ? asset('storage/' . $contract->accountant->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $contract->accountant->first_name }} {{ $contract->accountant->last_name }}"
                                                 width="32" height="32">
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $contract->accountant->first_name }} {{ $contract->accountant->last_name }}</div>
                                            <small class="text-muted">{{ $contract->accountant->email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <div class="p-3 bg-primary bg-opacity-10 rounded text-center">
                                        <small class="text-primary">
                                            <i class="bx bx-error-circle me-1"></i>
                                            <?= get_label('no_accountant_assigned', 'No accountant assigned') ?>
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Final Approver Assignment -->
                        <div class="assignment-item">
                            <div class="d-flex align-items-start">
                                <div class="avatar bg-label-success me-3 mt-1">
                                    <i class="bx bx-shield-quarter"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('final_approver', 'Final Approver') ?></h6>
                                        <span class="badge bg-{{ $contract->final_approver_id ? 'success' : 'danger' }}">
                                            {{ $contract->final_approver_id ? get_label('assigned', 'Assigned') : get_label('unassigned', 'Unassigned') }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <?= get_label('final_approver_description', 'Final approval and archiving authority') ?>
                                    </p>
                                    @if($contract->finalApprover)
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="{{ $contract->finalApprover->photo ? asset('storage/' . $contract->finalApprover->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $contract->finalApprover->first_name }} {{ $contract->finalApprover->last_name }}"
                                                 width="32" height="32">
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $contract->finalApprover->first_name }} {{ $contract->finalApprover->last_name }}</div>
                                            <small class="text-muted">{{ $contract->finalApprover->email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <div class="p-3 bg-success bg-opacity-10 rounded text-center">
                                        <small class="text-success">
                                            <i class="bx bx-error-circle me-1"></i>
                                            <?= get_label('no_final_approver_assigned', 'No final approver assigned') ?>
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Assignment Actions -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-cog text-primary me-2"></i>
                        <?= get_label('assignment_actions', 'Assignment Actions') ?>
                    </h5>
                    
                    <div class="assignment-actions">
                        <!-- Assignment Status Summary -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6 class="mb-3"><?= get_label('assignment_status', 'Assignment Status') ?></h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= get_label('total_roles', 'Total Roles') ?>:</span>
                                <span class="fw-bold">4</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success"><?= get_label('assigned', 'Assigned') ?>:</span>
                                <span class="fw-bold text-success">
                                    {{ collect([$contract->site_supervisor_id, $contract->quantity_approver_id, $contract->accountant_id, $contract->final_approver_id])->filter()->count() }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-danger"><?= get_label('unassigned', 'Unassigned') ?>:</span>
                                <span class="fw-bold text-danger">
                                    {{ 4 - collect([$contract->site_supervisor_id, $contract->quantity_approver_id, $contract->accountant_id, $contract->final_approver_id])->filter()->count() }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Quick Assignment Actions -->
                        <div class="mb-4">
                            <h6 class="mb-3"><?= get_label('quick_assignments', 'Quick Assignments') ?></h6>
                            <div class="d-grid gap-2">
                                @if(auth()->user()->can('assign_contract_roles'))
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignRolesModal">
                                    <i class="bx bx-user-plus me-1"></i>
                                    <?= get_label('assign_all_roles', 'Assign All Roles') ?>
                                </button>
                                @endif
                                
                                @if(!$contract->site_supervisor_id && auth()->user()->can('assign_site_supervisor'))
                                <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#assignSiteSupervisorModal">
                                    <i class="bx bx-hard-hat me-1"></i>
                                    <?= get_label('assign_site_supervisor', 'Assign Site Supervisor') ?>
                                </button>
                                @endif
                                
                                @if(!$contract->quantity_approver_id && auth()->user()->can('assign_quantity_approver'))
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#assignQuantityApproverModal">
                                    <i class="bx bx-check-circle me-1"></i>
                                    <?= get_label('assign_quantity_approver', 'Assign Quantity Approver') ?>
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Workflow Readiness Check -->
                        <div class="p-3 bg-{{ collect([$contract->site_supervisor_id, $contract->quantity_approver_id, $contract->accountant_id, $contract->final_approver_id])->filter()->count() === 4 ? 'success' : 'warning' }} bg-opacity-10 rounded">
                            <h6 class="mb-2">
                                <i class="bx {{ collect([$contract->site_supervisor_id, $contract->quantity_approver_id, $contract->accountant_id, $contract->final_approver_id])->filter()->count() === 4 ? 'bx-check-circle text-success' : 'bx-error-circle text-warning' }} me-1"></i>
                                <?= get_label('workflow_readiness', 'Workflow Readiness') ?>
                            </h6>
                            @if(collect([$contract->site_supervisor_id, $contract->quantity_approver_id, $contract->accountant_id, $contract->final_approver_id])->filter()->count() === 4)
                            <p class="mb-0 text-success small">
                                <i class="bx bx-check me-1"></i>
                                <?= get_label('workflow_ready', 'Workflow is ready to be initiated') ?>
                            </p>
                            @else
                            <p class="mb-0 text-warning small">
                                <i class="bx bx-error me-1"></i>
                                <?= get_label('workflow_not_ready', 'Workflow cannot be initiated - missing assignments') ?>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>