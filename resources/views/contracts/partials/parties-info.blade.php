<div class="parties-container">
    <div class="row">
        <!-- Client Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-primary me-3">
                            <i class="bx bx-user bx-md"></i>
                        </div>
                        <div>
                            <h5 class="mb-0"><?= get_label('client', 'Client') ?></h5>
                            <small class="text-muted"><?= get_label('contract_owner', 'Contract Owner') ?></small>
                        </div>
                    </div>
                    
                    @if($contract->client)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <img src="{{ $contract->client->photo ? asset('storage/' . $contract->client->photo) : asset('storage/photos/no-image.jpg') }}" 
                                 class="rounded-circle" 
                                 alt="{{ $contract->client->first_name }} {{ $contract->client->last_name }}"
                                 width="50" height="50">
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $contract->client->first_name }} {{ $contract->client->last_name }}</h6>
                            @if($contract->client->company)
                            <p class="mb-1 text-muted">{{ $contract->client->company }}</p>
                            @endif
                            @if($contract->client->email)
                            <small class="text-muted">
                                <i class="bx bx-envelope me-1"></i>{{ $contract->client->email }}
                            </small>
                            @endif
                        </div>
                    </div>
                    
                    @if($contract->client->phone)
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="bx bx-phone me-1"></i>{{ $contract->client->phone }}
                        </small>
                    </div>
                    @endif
                    
                    @if($contract->client->address || $contract->client->city)
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="bx bx-map me-1"></i>
                            {{ $contract->client->address ?? '' }}
                            @if($contract->client->city), {{ $contract->client->city }}@endif
                        </small>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-4">
                        <i class="bx bx-user-circle bx-lg text-muted"></i>
                        <p class="text-muted mt-2 mb-0"><?= get_label('no_client_assigned', 'No client assigned') ?></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Contractor/Project Team -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-success me-3">
                            <i class="bx bx-buildings bx-md"></i>
                        </div>
                        <div>
                            <h5 class="mb-0"><?= get_label('project_team', 'Project Team') ?></h5>
                            <small class="text-muted"><?= get_label('key_stakeholders', 'Key Stakeholders') ?></small>
                        </div>
                    </div>
                    
                    <div class="parties-list">
                        <!-- Site Supervisor -->
                        <div class="party-item mb-3 p-2 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-label-warning me-2">
                                    <i class="bx bx-hard-hat"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= get_label('site_supervisor', 'Site Supervisor') ?></h6>
                                        @if($contract->siteSupervisor)
                                        <span class="badge bg-success"><?= get_label('assigned', 'Assigned') ?></span>
                                        @else
                                        <span class="badge bg-danger"><?= get_label('unassigned', 'Unassigned') ?></span>
                                        @endif
                                    </div>
                                    @if($contract->siteSupervisor)
                                    <small class="text-muted">{{ $contract->siteSupervisor->first_name }} {{ $contract->siteSupervisor->last_name }}</small>
                                    @else
                                    <small class="text-muted"><?= get_label('no_assignment', 'No assignment') ?></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quantity Approver -->
                        <div class="party-item mb-3 p-2 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-label-info me-2">
                                    <i class="bx bx-check-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= get_label('quantity_approver', 'Quantity Approver') ?></h6>
                                        @if($contract->quantityApprover)
                                        <span class="badge bg-success"><?= get_label('assigned', 'Assigned') ?></span>
                                        @else
                                        <span class="badge bg-danger"><?= get_label('unassigned', 'Unassigned') ?></span>
                                        @endif
                                    </div>
                                    @if($contract->quantityApprover)
                                    <small class="text-muted">{{ $contract->quantityApprover->first_name }} {{ $contract->quantityApprover->last_name }}</small>
                                    @else
                                    <small class="text-muted"><?= get_label('no_assignment', 'No assignment') ?></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Accountant -->
                        <div class="party-item mb-3 p-2 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-label-primary me-2">
                                    <i class="bx bx-calculator"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= get_label('accountant', 'Accountant') ?></h6>
                                        @if($contract->accountant)
                                        <span class="badge bg-success"><?= get_label('assigned', 'Assigned') ?></span>
                                        @else
                                        <span class="badge bg-danger"><?= get_label('unassigned', 'Unassigned') ?></span>
                                        @endif
                                    </div>
                                    @if($contract->accountant)
                                    <small class="text-muted">{{ $contract->accountant->first_name }} {{ $contract->accountant->last_name }}</small>
                                    @else
                                    <small class="text-muted"><?= get_label('no_assignment', 'No assignment') ?></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Final Approver -->
                        <div class="party-item p-2 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-label-success me-2">
                                    <i class="bx bx-shield-quarter"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= get_label('final_approver', 'Final Approver') ?></h6>
                                        @if($contract->finalApprover)
                                        <span class="badge bg-success"><?= get_label('assigned', 'Assigned') ?></span>
                                        @else
                                        <span class="badge bg-danger"><?= get_label('unassigned', 'Unassigned') ?></span>
                                        @endif
                                    </div>
                                    @if($contract->finalApprover)
                                    <small class="text-muted">{{ $contract->finalApprover->first_name }} {{ $contract->finalApprover->last_name }}</small>
                                    @else
                                    <small class="text-muted"><?= get_label('no_assignment', 'No assignment') ?></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Project Information -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-info me-3">
                            <i class="bx bx-building-house bx-md"></i>
                        </div>
                        <div>
                            <h5 class="mb-0"><?= get_label('project_information', 'Project Information') ?></h5>
                            <small class="text-muted"><?= get_label('related_project_details', 'Related project details') ?></small>
                        </div>
                    </div>
                    
                    @if($contract->project)
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small mb-1"><?= get_label('project_name', 'Project Name') ?></label>
                            <h6 class="mb-0">{{ $contract->project->title }}</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small mb-1"><?= get_label('project_status', 'Project Status') ?></label>
                            <span class="badge bg-{{ $contract->project->status->color ?? 'secondary' }}">
                                {{ $contract->project->status->title ?? 'Unknown' }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small mb-1"><?= get_label('project_manager', 'Project Manager') ?></label>
                            @if($contract->project->users->first())
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ $contract->project->users->first()->photo ? asset('storage/' . $contract->project->users->first()->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ $contract->project->users->first()->first_name }} {{ $contract->project->users->first()->last_name }}"
                                         width="24" height="24">
                                </div>
                                <span>{{ $contract->project->users->first()->first_name }} {{ $contract->project->users->first()->last_name }}</span>
                            </div>
                            @else
                            <span class="text-muted"><?= get_label('not_assigned', 'Not assigned') ?></span>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bx bx-building-house bx-lg text-muted"></i>
                        <p class="text-muted mt-2 mb-0"><?= get_label('no_project_linked', 'No project linked to this contract') ?></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>