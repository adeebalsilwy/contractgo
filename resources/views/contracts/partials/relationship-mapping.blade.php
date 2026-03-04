<div class="relationship-mapping">
    <div class="row">
        <!-- Stakeholder Network Visualization -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-dark text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-network-chart me-2"></i>{{ get_label('stakeholder_network', 'Stakeholder Network') }}</h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-light active" data-view="network">
                                <i class="bx bx-network-chart"></i>
                            </button>
                            <button type="button" class="btn btn-outline-light" data-view="hierarchy">
                                <i class="bx bx-sitemap"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="network-visualization">
                        <div class="d-flex flex-wrap justify-content-center gap-4 mb-4">
                            <!-- Central Contract Node -->
                            <div class="network-node central-node bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" 
                                 style="width: 120px; height: 120px; z-index: 10;">
                                <div class="text-center">
                                    <i class="bx bx-file-blank bx-lg mb-1"></i>
                                    <div class="node-label small fw-bold">Contract #{{ $contract->id }}</div>
                                </div>
                            </div>
                            
                            <!-- Connected Stakeholder Nodes -->
                            <div class="connected-nodes d-flex flex-wrap justify-content-center gap-3 mt-4">
                                <!-- Client Node -->
                                @if($contract->client)
                                <div class="network-node stakeholder-node bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 80px; height: 80px;" data-bs-toggle="tooltip" title="{{ $contract->client->first_name }} {{ $contract->client->last_name }}">
                                    <div class="text-center">
                                        <i class="bx bx-user bx-md mb-1"></i>
                                        <div class="node-label x-small">Client</div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Site Supervisor Node -->
                                @if($contract->siteSupervisor)
                                <div class="network-node stakeholder-node bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 80px; height: 80px;" data-bs-toggle="tooltip" title="{{ $contract->siteSupervisor->first_name }} {{ $contract->siteSupervisor->last_name }}">
                                    <div class="text-center">
                                        <i class="bx bx-hard-hat bx-md mb-1"></i>
                                        <div class="node-label x-small">Supervisor</div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Project Manager Node -->
                                @if($contract->project && $contract->project->users->first())
                                <div class="network-node stakeholder-node bg-info text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 80px; height: 80px;" data-bs-toggle="tooltip" title="{{ $contract->project->users->first()->first_name }} {{ $contract->project->users->first()->last_name }}">
                                    <div class="text-center">
                                        <i class="bx bx-briefcase bx-md mb-1"></i>
                                        <div class="node-label x-small">Manager</div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Accountant Node -->
                                @if($contract->accountant)
                                <div class="network-node stakeholder-node bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 80px; height: 80px;" data-bs-toggle="tooltip" title="{{ $contract->accountant->first_name }} {{ $contract->accountant->last_name }}">
                                    <div class="text-center">
                                        <i class="bx bx-calculator bx-md mb-1"></i>
                                        <div class="node-label x-small">Accountant</div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Final Approver Node -->
                                @if($contract->finalApprover)
                                <div class="network-node stakeholder-node bg-dark text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 80px; height: 80px;" data-bs-toggle="tooltip" title="{{ $contract->finalApprover->first_name }} {{ $contract->finalApprover->last_name }}">
                                    <div class="text-center">
                                        <i class="bx bx-shield-quarter bx-md mb-1"></i>
                                        <div class="node-label x-small">Approver</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Connection Lines Visualization -->
                        <div class="connection-legend mt-4">
                            <h6 class="mb-3">{{ get_label('relationship_types', 'Relationship Types') }}</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="connection-line bg-success me-2" style="width: 30px; height: 3px;"></div>
                                    <small class="text-muted">{{ get_label('primary_contact', 'Primary Contact') }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="connection-line bg-warning me-2" style="width: 30px; height: 3px;"></div>
                                    <small class="text-muted">{{ get_label('approval_authority', 'Approval Authority') }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="connection-line bg-info me-2" style="width: 30px; height: 3px;"></div>
                                    <small class="text-muted">{{ get_label('financial_responsibility', 'Financial Responsibility') }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="connection-line bg-primary me-2" style="width: 30px; height: 3px;"></div>
                                    <small class="text-muted">{{ get_label('technical_oversight', 'Technical Oversight') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stakeholder Details Panel -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-secondary text-white">
                    <h5 class="mb-0"><i class="bx bx-group me-2"></i>{{ get_label('key_stakeholders', 'Key Stakeholders') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="stakeholder-list">
                        <!-- Client Information -->
                        <div class="stakeholder-item border-bottom p-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img src="{{ $contract->client->photo ? asset('storage/' . $contract->client->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ $contract->client->first_name }} {{ $contract->client->last_name }}"
                                         width="45" height="45">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $contract->client->first_name }} {{ $contract->client->last_name }}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2">Client</span>
                                        <small class="text-muted">{{ $contract->client->email ?? 'No email' }}</small>
                                    </div>
                                </div>
                                <div class="stakeholder-actions">
                                    <a href="{{ url('/clients/profile/' . $contract->client->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-user"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Site Supervisor -->
                        <div class="stakeholder-item border-bottom p-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 45px; height: 45px;">
                                    <i class="bx bx-hard-hat"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $contract->siteSupervisor->first_name ?? 'Unassigned' }} {{ $contract->siteSupervisor->last_name ?? '' }}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning me-2">Site Supervisor</span>
                                        <small class="text-muted">
                                            @if($contract->siteSupervisor)
                                                {{ $contract->siteSupervisor->email ?? 'No email' }}
                                            @else
                                                {{ get_label('not_assigned', 'Not assigned') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if($contract->siteSupervisor)
                                <div class="stakeholder-actions">
                                    <a href="{{ url('/users/profile/' . $contract->siteSupervisor->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bx bx-user"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Project Manager -->
                        <div class="stakeholder-item border-bottom p-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 45px; height: 45px;">
                                    <i class="bx bx-briefcase"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        @if($contract->project && $contract->project->users->first())
                                            {{ $contract->project->users->first()->first_name }} {{ $contract->project->users->first()->last_name }}
                                        @else
                                            {{ get_label('not_assigned', 'Not assigned') }}
                                        @endif
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info me-2">Project Manager</span>
                                        <small class="text-muted">
                                            @if($contract->project && $contract->project->users->first())
                                                {{ $contract->project->users->first()->email ?? 'No email' }}
                                            @else
                                                {{ get_label('no_project_linked', 'No project linked') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if($contract->project && $contract->project->users->first())
                                <div class="stakeholder-actions">
                                    <a href="{{ url('/users/profile/' . $contract->project->users->first()->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bx bx-user"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Accountant -->
                        <div class="stakeholder-item border-bottom p-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 45px; height: 45px;">
                                    <i class="bx bx-calculator"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $contract->accountant->first_name ?? 'Unassigned' }} {{ $contract->accountant->last_name ?? '' }}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2">Accountant</span>
                                        <small class="text-muted">
                                            @if($contract->accountant)
                                                {{ $contract->accountant->email ?? 'No email' }}
                                            @else
                                                {{ get_label('not_assigned', 'Not assigned') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if($contract->accountant)
                                <div class="stakeholder-actions">
                                    <a href="{{ url('/users/profile/' . $contract->accountant->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-user"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Final Approver -->
                        <div class="stakeholder-item p-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 45px; height: 45px;">
                                    <i class="bx bx-shield-quarter"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $contract->finalApprover->first_name ?? 'Unassigned' }} {{ $contract->finalApprover->last_name ?? '' }}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-dark me-2">Final Approver</span>
                                        <small class="text-muted">
                                            @if($contract->finalApprover)
                                                {{ $contract->finalApprover->email ?? 'No email' }}
                                            @else
                                                {{ get_label('not_assigned', 'Not assigned') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if($contract->finalApprover)
                                <div class="stakeholder-actions">
                                    <a href="{{ url('/users/profile/' . $contract->finalApprover->id) }}" class="btn btn-sm btn-outline-dark">
                                        <i class="bx bx-user"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Projects and Contracts -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-light">
                    <h5 class="mb-0"><i class="bx bx-link me-2 text-primary"></i>{{ get_label('related_entities', 'Related Entities') }}</h5>
                </div>
                <div class="card-body">
                    <div class="related-entities">
                        <div class="row">
                            <!-- Related Projects -->
                            <div class="col-md-4 mb-3">
                                <div class="entity-card border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar bg-info text-white rounded-circle me-3">
                                            <i class="bx bx-building-house bx-md"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ get_label('related_projects', 'Related Projects') }}</h6>
                                            <small class="text-muted">{{ $contract->project ? '1 project linked' : 'No projects linked' }}</small>
                                        </div>
                                    </div>
                                    @if($contract->project)
                                    <div class="project-preview">
                                        <div class="border rounded p-2 mb-2">
                                            <h6 class="mb-1">{{ $contract->project->title }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-{{ $contract->project->status->color ?? 'secondary' }}">
                                                    {{ $contract->project->status->title ?? 'Unknown' }}
                                                </span>
                                                <small class="text-muted">{{ $contract->project->tasks_count ?? 0 }} tasks</small>
                                            </div>
                                        </div>
                                        <a href="{{ url('/projects/' . $contract->project->id) }}" class="btn btn-sm btn-outline-info w-100">
                                            <i class="bx bx-show me-1"></i>{{ get_label('view_project', 'View Project') }}
                                        </a>
                                    </div>
                                    @else
                                    <div class="text-center py-3">
                                        <i class="bx bx-building-house bx-lg text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">{{ get_label('no_related_projects', 'No related projects') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Related Contracts -->
                            <div class="col-md-4 mb-3">
                                <div class="entity-card border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar bg-primary text-white rounded-circle me-3">
                                            <i class="bx bx-file bx-md"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ get_label('related_contracts', 'Related Contracts') }}</h6>
                                            <small class="text-muted">{{ $contract->related_contracts_count ?? 0 }} related contracts</small>
                                        </div>
                                    </div>
                                    <div class="contracts-list">
                                        @if(isset($contract->relatedContracts) && $contract->relatedContracts->count() > 0)
                                            @foreach($contract->relatedContracts->take(3) as $relatedContract)
                                            <div class="border rounded p-2 mb-2">
                                                <h6 class="mb-1">{{ $relatedContract->title }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-{{ getWorkflowStatusColor($relatedContract->workflow_status) }}">
                                                        {{ getWorkflowStatusText($relatedContract->workflow_status) }}
                                                    </span>
                                                    <small class="text-muted">{{ format_currency($relatedContract->value) }}</small>
                                                </div>
                                            </div>
                                            @endforeach
                                            <a href="{{ url('/contracts?related_to=' . $contract->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bx bx-show me-1"></i>{{ get_label('view_all', 'View All') }}
                                            </a>
                                        @else
                                        <div class="text-center py-3">
                                            <i class="bx bx-file bx-lg text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">{{ get_label('no_related_contracts', 'No related contracts') }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Related Estimates/Invoices -->
                            <div class="col-md-4 mb-3">
                                <div class="entity-card border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar bg-success text-white rounded-circle me-3">
                                            <i class="bx bx-receipt bx-md"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ get_label('related_documents', 'Related Documents') }}</h6>
                                            <small class="text-muted">{{ $contract->estimates->count() }} estimates/invoices</small>
                                        </div>
                                    </div>
                                    <div class="documents-list">
                                        @if($contract->estimates->count() > 0)
                                            @foreach($contract->estimates->take(3) as $estimate)
                                            <div class="border rounded p-2 mb-2">
                                                <h6 class="mb-1">{{ Str::limit($estimate->name, 25) }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-{{ $estimate->type === 'estimate' ? 'info' : 'success' }}">
                                                        {{ ucfirst($estimate->type) }}
                                                    </span>
                                                    <small class="text-muted">{{ format_currency($estimate->final_total) }}</small>
                                                </div>
                                            </div>
                                            @endforeach
                                            <a href="{{ url('/estimates-invoices?contract_id=' . $contract->id) }}" class="btn btn-sm btn-outline-success w-100">
                                                <i class="bx bx-show me-1"></i>{{ get_label('view_all', 'View All') }}
                                            </a>
                                        @else
                                        <div class="text-center py-3">
                                            <i class="bx bx-receipt bx-lg text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">{{ get_label('no_related_documents', 'No related documents') }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>