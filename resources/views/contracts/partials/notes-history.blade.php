<div class="notes-history-container">
    <div class="row">
        <!-- Notes Section -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="bx bx-notepad text-secondary me-2"></i>
                            <?= get_label('notes', 'Notes') ?>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i class="bx bx-plus me-1"></i><?= get_label('add_note', 'Add Note') ?>
                        </button>
                    </div>
                    
                    <div class="notes-list">
                        <!-- Workflow Notes -->
                        @if($contract->workflow_notes)
                        <div class="note-item mb-3 p-3 rounded border border-info">
                            <div class="d-flex align-items-start">
                                <div class="avatar bg-label-info me-3 mt-1">
                                    <i class="bx bx-message-square-detail"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('workflow_notes', 'Workflow Notes') ?></h6>
                                        <small class="text-muted">{{ format_date($contract->updated_at, true) }}</small>
                                    </div>
                                    <p class="mb-2">{{ $contract->workflow_notes }}</p>
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
                        @endif
                        
                        <!-- Amendment Notes -->
                        @if($contract->amendment_reason)
                        <div class="note-item mb-3 p-3 rounded border border-warning">
                            <div class="d-flex align-items-start">
                                <div class="avatar bg-label-warning me-3 mt-1">
                                    <i class="bx bx-edit"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('amendment_notes', 'Amendment Notes') ?></h6>
                                        <small class="text-muted">{{ format_date($contract->amendment_requested_at ?? $contract->updated_at, true) }}</small>
                                    </div>
                                    <p class="mb-2">{{ $contract->amendment_reason }}</p>
                                    @if($contract->amendment_requested_by)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <img src="{{ $contract->amendmentRequester->photo ? asset('storage/' . $contract->amendmentRequester->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $contract->amendmentRequester->first_name }} {{ $contract->amendmentRequester->last_name }}"
                                                 width="20" height="20">
                                        </div>
                                        <small class="text-muted">
                                            {{ $contract->amendmentRequester->first_name }} {{ $contract->amendmentRequester->last_name }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- General Notes -->
                        <div class="note-item p-3 rounded border border-light">
                            <div class="d-flex align-items-start">
                                <div class="avatar bg-label-secondary me-3 mt-1">
                                    <i class="bx bx-note"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= get_label('general_notes', 'General Notes') ?></h6>
                                        <small class="text-muted"><?= get_label('no_notes', 'No notes added') ?></small>
                                    </div>
                                    <p class="mb-0 text-muted"><?= get_label('no_notes_description', 'No general notes have been added to this contract yet') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- History Section -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-history text-primary me-2"></i>
                        <?= get_label('activity_history', 'Activity History') ?>
                    </h5>
                    
                    <div class="history-list">
                        <!-- Recent Activity Items -->
                        <div class="history-item mb-3 p-3 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-success me-3">
                                    <i class="bx bx-check-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= get_label('contract_approved', 'Contract Approved') ?></h6>
                                            <small class="text-muted"><?= get_label('final_approval_completed', 'Final approval process completed successfully') ?></small>
                                        </div>
                                        <small class="text-muted">{{ format_date($contract->final_approval_signed_at ?? $contract->updated_at, true) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="history-item mb-3 p-3 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-info me-3">
                                    <i class="bx bx-calculator"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= get_label('accounting_integration', 'Accounting Integration') ?></h6>
                                            <small class="text-muted"><?= get_label('onyx_pro_integration_completed', 'Onyx Pro accounting integration completed') ?></small>
                                        </div>
                                        <small class="text-muted">{{ format_date($contract->accounting_review_signed_at ?? $contract->updated_at, true) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="history-item mb-3 p-3 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-warning me-3">
                                    <i class="bx bx-list-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= get_label('quantities_approved', 'Quantities Approved') ?></h6>
                                            <small class="text-muted"><?= get_label('site_quantities_approved', 'Site supervisor quantities approved') ?></small>
                                        </div>
                                        <small class="text-muted">{{ format_date($contract->quantity_approval_signed_at ?? $contract->updated_at, true) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="history-item p-3 rounded border">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-primary me-3">
                                    <i class="bx bx-file"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= get_label('contract_created', 'Contract Created') ?></h6>
                                            <small class="text-muted"><?= get_label('initial_contract_setup', 'Initial contract setup and workflow configuration') ?></small>
                                        </div>
                                        <small class="text-muted">{{ format_date($contract->created_at, true) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Audit Trail -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="bx bx-shield text-success me-2"></i>
                            <?= get_label('audit_trail', 'Audit Trail') ?>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="exportAuditTrail({{ $contract->id }})">
                            <i class="bx bx-download me-1"></i><?= get_label('export_audit_trail', 'Export Audit Trail') ?>
                        </button>
                    </div>
                    
                    <div class="audit-trail">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= get_label('timestamp', 'Timestamp') ?></th>
                                        <th><?= get_label('user', 'User') ?></th>
                                        <th><?= get_label('action', 'Action') ?></th>
                                        <th><?= get_label('details', 'Details') ?></th>
                                        <th><?= get_label('ip_address', 'IP Address') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ format_date($contract->final_approval_signed_at ?? $contract->updated_at, true) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <img src="{{ $contract->finalApprover->photo ? asset('storage/' . $contract->finalApprover->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                         class="rounded-circle" 
                                                         alt="{{ $contract->finalApprover->first_name }} {{ $contract->finalApprover->last_name }}"
                                                         width="20" height="20">
                                                </div>
                                                <span>{{ $contract->finalApprover->first_name ?? 'System' }} {{ $contract->finalApprover->last_name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="bx bx-check-circle me-1"></i><?= get_label('final_approval', 'Final Approval') ?>
                                            </span>
                                        </td>
                                        <td><?= get_label('electronic_signature_applied', 'Electronic signature applied for final approval') ?></td>
                                        <td class="text-muted">-</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>{{ format_date($contract->accounting_review_signed_at ?? $contract->updated_at, true) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <img src="{{ $contract->accountant->photo ? asset('storage/' . $contract->accountant->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                         class="rounded-circle" 
                                                         alt="{{ $contract->accountant->first_name }} {{ $contract->accountant->last_name }}"
                                                         width="20" height="20">
                                                </div>
                                                <span>{{ $contract->accountant->first_name ?? 'System' }} {{ $contract->accountant->last_name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <i class="bx bx-calculator me-1"></i><?= get_label('accounting_review', 'Accounting Review') ?>
                                            </span>
                                        </td>
                                        <td><?= get_label('journal_entry_created', 'Journal entry created in Onyx Pro system') ?></td>
                                        <td class="text-muted">-</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>{{ format_date($contract->quantity_approval_signed_at ?? $contract->updated_at, true) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <img src="{{ $contract->quantityApprover->photo ? asset('storage/' . $contract->quantityApprover->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                         class="rounded-circle" 
                                                         alt="{{ $contract->quantityApprover->first_name }} {{ $contract->quantityApprover->last_name }}"
                                                         width="20" height="20">
                                                </div>
                                                <span>{{ $contract->quantityApprover->first_name ?? 'System' }} {{ $contract->quantityApprover->last_name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">
                                                <i class="bx bx-list-check me-1"></i><?= get_label('quantity_approval', 'Quantity Approval') ?>
                                            </span>
                                        </td>
                                        <td><?= get_label('quantities_approved_by_supervisor', 'Quantities approved by site supervisor') ?></td>
                                        <td class="text-muted">-</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>{{ format_date($contract->created_at, true) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <img src="{{ $contract->creator->photo ? asset('storage/' . $contract->creator->photo) : asset('storage/photos/no-image.jpg') }}" 
                                                         class="rounded-circle" 
                                                         alt="{{ $contract->creator->first_name }} {{ $contract->creator->last_name }}"
                                                         width="20" height="20">
                                                </div>
                                                <span>{{ $contract->creator->first_name ?? 'System' }} {{ $contract->creator->last_name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <i class="bx bx-file me-1"></i><?= get_label('contract_created', 'Contract Created') ?>
                                            </span>
                                        </td>
                                        <td><?= get_label('contract_initial_setup', 'Contract initial setup and workflow configuration') ?></td>
                                        <td class="text-muted">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= get_label('add_note', 'Add Note') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addNoteForm">
                    @csrf
                    <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note_type', 'Note Type') ?></label>
                        <select class="form-select" name="note_type" required>
                            <option value=""><?= get_label('select_note_type', 'Select note type') ?></option>
                            <option value="general"><?= get_label('general_note', 'General Note') ?></option>
                            <option value="workflow"><?= get_label('workflow_note', 'Workflow Note') ?></option>
                            <option value="technical"><?= get_label('technical_note', 'Technical Note') ?></option>
                            <option value="financial"><?= get_label('financial_note', 'Financial Note') ?></option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note_title', 'Note Title') ?></label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note_content', 'Note Content') ?></label>
                        <textarea class="form-control" name="content" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <select class="form-select" name="priority">
                            <option value="low"><?= get_label('low', 'Low') ?></option>
                            <option value="medium" selected><?= get_label('medium', 'Medium') ?></option>
                            <option value="high"><?= get_label('high', 'High') ?></option>
                            <option value="urgent"><?= get_label('urgent', 'Urgent') ?></option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('cancel', 'Cancel') ?></button>
                <button type="button" class="btn btn-primary" onclick="saveNote()"><?= get_label('save_note', 'Save Note') ?></button>
            </div>
        </div>
    </div>
</div>