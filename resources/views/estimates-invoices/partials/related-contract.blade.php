<div class="related-contract-container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">
                <i class="bx bx-link text-info me-2"></i>
                <?= get_label('related_contract', 'Related Contract') ?>
            </h5>
            
            @if($estimate_invoice->contract_id && isset($relatedContract))
            <!-- Contract Information -->
            <div class="contract-info mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar bg-label-primary me-3">
                        <i class="bx bx-file bx-md"></i>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= get_label('contract_details', 'Contract Details') ?></h5>
                        <small class="text-muted"><?= get_label('linked_contract_information', 'Linked contract information') ?></small>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1"><?= get_label('contract_id', 'Contract ID') ?></label>
                        <h6 class="mb-0">#{{ $relatedContract->id }}</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1"><?= get_label('contract_title', 'Contract Title') ?></label>
                        <h6 class="mb-0">{{ $relatedContract->title ?? 'N/A' }}</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1"><?= get_label('contract_value', 'Contract Value') ?></label>
                        <h6 class="mb-0 text-success">{{ format_currency($relatedContract->value ?? 0) }}</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1"><?= get_label('contract_status', 'Contract Status') ?></label>
                        <span class="badge bg-{{ $relatedContract->workflow_status === 'approved' ? 'success' : 'warning' }}">
                            {{ getWorkflowStatusText($relatedContract->workflow_status ?? 'draft') }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Contract Parties -->
            <div class="contract-parties mb-4">
                <h6 class="mb-3">
                    <i class="bx bx-user text-primary me-2"></i>
                    <?= get_label('contract_parties', 'Contract Parties') ?>
                </h6>
                
                <div class="row g-3">
                    <!-- Client Information -->
                    <div class="col-md-6">
                        <div class="party-card p-3 rounded border">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar bg-label-success me-2">
                                    <i class="bx bx-user"></i>
                                </div>
                                <h6 class="mb-0"><?= get_label('client', 'Client') ?></h6>
                            </div>
                            @if(isset($relatedContract->client))
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ $relatedContract->client->photo ? asset('storage/' . $relatedContract->client->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ $relatedContract->client->first_name }} {{ $relatedContract->client->last_name }}"
                                         width="32" height="32">
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $relatedContract->client->first_name }} {{ $relatedContract->client->last_name }}</div>
                                    @if($relatedContract->client->company)
                                    <small class="text-muted">{{ $relatedContract->client->company }}</small>
                                    @endif
                                </div>
                            </div>
                            @else
                            <small class="text-muted"><?= get_label('client_not_assigned', 'Client not assigned') ?></small>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Contractor Information -->
                    <div class="col-md-6">
                        <div class="party-card p-3 rounded border">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar bg-label-warning me-2">
                                    <i class="bx bx-buildings"></i>
                                </div>
                                <h6 class="mb-0"><?= get_label('contractor', 'Contractor') ?></h6>
                            </div>
                            @if(isset($contractor))
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ $contractor->photo ? asset('storage/' . $contractor->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ $contractor->first_name }} {{ $contractor->last_name }}"
                                         width="32" height="32">
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $contractor->first_name }} {{ $contractor->last_name }}</div>
                                    <small class="text-muted"><?= get_label('primary_contractor', 'Primary Contractor') ?></small>
                                </div>
                            </div>
                            @else
                            <small class="text-muted"><?= get_label('contractor_not_assigned', 'Contractor not assigned') ?></small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contract Timeline -->
            <div class="contract-timeline mb-4">
                <h6 class="mb-3">
                    <i class="bx bx-calendar text-info me-2"></i>
                    <?= get_label('contract_timeline', 'Contract Timeline') ?>
                </h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small mb-1"><?= get_label('start_date', 'Start Date') ?></label>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-calendar-check text-success me-2"></i>
                            <span>{{ format_date($relatedContract->start_date ?? now()) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small mb-1"><?= get_label('end_date', 'End Date') ?></label>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-calendar-x text-danger me-2"></i>
                            <span>{{ format_date($relatedContract->end_date ?? now()->addYear()) }}</span>
                        </div>
                    </div>
                    @if(isset($relatedProject))
                    <div class="col-md-12">
                        <label class="text-muted small mb-1"><?= get_label('project', 'Project') ?></label>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-building-house text-primary me-2"></i>
                            <span>{{ $relatedProject->title ?? 'N/A' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Contract Actions -->
            <div class="contract-actions">
                <h6 class="mb-3">
                    <i class="bx bx-link-external text-warning me-2"></i>
                    <?= get_label('contract_actions', 'Contract Actions') ?>
                </h6>
                
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('contracts.show', $relatedContract->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-show me-1"></i><?= get_label('view_contract', 'View Contract') ?>
                    </a>
                    
                    @if(isset($relatedProject))
                    <a href="{{ route('projects.info', $relatedProject->id) }}" class="btn btn-sm btn-outline-info">
                        <i class="bx bx-building-house me-1"></i><?= get_label('view_project', 'View Project') ?>
                    </a>
                    @endif
                    
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="generateContractLink({{ $relatedContract->id }})">
                        <i class="bx bx-link me-1"></i><?= get_label('link_to_contract', 'Link to Contract') ?>
                    </button>
                </div>
            </div>
            
            @else
            <!-- No Contract Linked -->
            <div class="text-center py-5">
                <div class="avatar bg-label-secondary mx-auto mb-3" style="width: 80px; height: 80px;">
                    <i class="bx bx-link bx-lg"></i>
                </div>
                <h5 class="mb-2"><?= get_label('no_contract_linked', 'No Contract Linked') ?></h5>
                <p class="text-muted mb-4">
                    <?= get_label('no_contract_linked_description', 'This extract is not currently linked to any contract. You can link it to a contract for better tracking and management.') ?>
                </p>
                
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#linkContractModal">
                        <i class="bx bx-link me-1"></i><?= get_label('link_contract', 'Link Contract') ?>
                    </button>
                    <a href="{{ url('contracts') }}" class="btn btn-outline-info">
                        <i class="bx bx-file me-1"></i><?= get_label('view_contracts', 'View Contracts') ?>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Link Contract Modal -->
<div class="modal fade" id="linkContractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= get_label('link_contract', 'Link Contract') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="linkContractForm">
                    @csrf
                    <input type="hidden" name="estimate_invoice_id" value="{{ $estimate_invoice->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('select_contract', 'Select Contract') ?></label>
                        <select class="form-select" name="contract_id" required>
                            <option value=""><?= get_label('select_contract', 'Select Contract') ?></option>
                            @foreach($availableContracts as $contract)
                            <option value="{{ $contract->id }}">{{ $contract->title }} (ID: {{ $contract->id }})</option>
                            @endforeach
                        </select>
                        <div class="form-text"><?= get_label('link_contract_description', 'Select a contract to link this extract to') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('link_reason', 'Link Reason') ?></label>
                        <textarea class="form-control" name="link_reason" rows="3" placeholder="<?= get_label('link_reason_placeholder', 'Enter reason for linking this contract...') ?>"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('cancel', 'Cancel') ?></button>
                <button type="button" class="btn btn-primary" onclick="linkContract()"><?= get_label('link_contract', 'Link Contract') ?></button>
            </div>
        </div>
    </div>
</div>