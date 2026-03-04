<div class="documents-container">
    <div class="row">
        <!-- Contract Documents -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="bx bx-file text-warning me-2"></i>
                            <?= get_label('contract_documents', 'Contract Documents') ?>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            <i class="bx bx-plus me-1"></i><?= get_label('upload_document', 'Upload Document') ?>
                        </button>
                    </div>
                    
                    <div class="documents-list">
                        <!-- Main Contract Document -->
                        <div class="document-item mb-3 p-3 rounded border border-primary">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-primary me-3">
                                    <i class="bx bx-file"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0"><?= get_label('main_contract', 'Main Contract') ?></h6>
                                        <span class="badge bg-primary">PDF</span>
                                    </div>
                                    <small class="text-muted mb-2 d-block">
                                        <?= get_label('main_contract_description', 'Primary contract document with all terms and conditions') ?>
                                    </small>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-3">
                                                <i class="bx bx-calendar me-1"></i>{{ format_date($contract->created_at) }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bx bx-user me-1"></i>{{ $contract->creator->first_name ?? '' }} {{ $contract->creator->last_name ?? '' }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('contracts.pdf', $contract->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-download me-1"></i><?= get_label('download', 'Download') ?>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="viewDocument({{ $contract->id }}, 'contract')">
                                                <i class="bx bx-show me-1"></i><?= get_label('view', 'View') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Signed Documents -->
                        @if($contract->quantity_approval_signature || $contract->management_review_signature || $contract->accounting_review_signature || $contract->final_approval_signature)
                        <div class="document-item mb-3 p-3 rounded border border-success">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-success me-3">
                                    <i class="bx bx-signature"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0"><?= get_label('signed_documents', 'Signed Documents') ?></h6>
                                        <span class="badge bg-success">
                                            {{ collect([$contract->quantity_approval_signature, $contract->management_review_signature, $contract->accounting_review_signature, $contract->final_approval_signature])->filter()->count() }} <?= get_label('signatures', 'Signatures') ?>
                                        </span>
                                    </div>
                                    <small class="text-muted mb-2 d-block">
                                        <?= get_label('electronic_signatures_applied', 'Electronic signatures applied to contract') ?>
                                    </small>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="signature-list">
                                            @if($contract->quantity_approval_signature)
                                            <span class="badge bg-success me-1">
                                                <i class="bx bx-check me-1"></i><?= get_label('quantity_approval', 'Quantity Approval') ?>
                                            </span>
                                            @endif
                                            @if($contract->management_review_signature)
                                            <span class="badge bg-success me-1">
                                                <i class="bx bx-check me-1"></i><?= get_label('management_review', 'Management Review') ?>
                                            </span>
                                            @endif
                                            @if($contract->accounting_review_signature)
                                            <span class="badge bg-success me-1">
                                                <i class="bx bx-check me-1"></i><?= get_label('accounting_review', 'Accounting Review') ?>
                                            </span>
                                            @endif
                                            @if($contract->final_approval_signature)
                                            <span class="badge bg-success">
                                                <i class="bx bx-check me-1"></i><?= get_label('final_approval', 'Final Approval') ?>
                                            </span>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#viewSignaturesModal">
                                            <i class="bx bx-show me-1"></i><?= get_label('view_signatures', 'View Signatures') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Journal Entry Document -->
                        @if($contract->journal_entry_number)
                        <div class="document-item mb-3 p-3 rounded border border-info">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-info me-3">
                                    <i class="bx bx-receipt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0"><?= get_label('journal_entry', 'Journal Entry') ?>: {{ $contract->journal_entry_number }}</h6>
                                        <span class="badge bg-info">Onyx Pro</span>
                                    </div>
                                    <small class="text-muted mb-2 d-block">
                                        <?= get_label('accounting_integration_document', 'Accounting integration document from Onyx Pro system') ?>
                                    </small>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-3">
                                                <i class="bx bx-calendar me-1"></i>{{ format_date($contract->journal_entry_date ?? $contract->updated_at) }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bx bx-calculator me-1"></i><?= get_label('accounting_system', 'Accounting System') ?>
                                            </small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="viewJournalEntry({{ $contract->id }})">
                                            <i class="bx bx-show me-1"></i><?= get_label('view_details', 'View Details') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Amendment Documents -->
                        @if($contract->amendment_requested)
                        <div class="document-item mb-3 p-3 rounded border border-warning">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-warning me-3">
                                    <i class="bx bx-edit"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0"><?= get_label('amendment_documents', 'Amendment Documents') ?></h6>
                                        <span class="badge bg-warning"><?= get_label('pending', 'Pending') ?></span>
                                    </div>
                                    <small class="text-muted mb-2 d-block">
                                        <?= get_label('contract_amendment_request', 'Contract amendment request and supporting documents') ?>
                                    </small>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted">
                                                <i class="bx bx-message-square-detail me-1"></i>{{ $contract->amendment_reason ?? get_label('amendment_requested', 'Amendment requested') }}
                                            </small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#viewAmendmentsModal">
                                            <i class="bx bx-show me-1"></i><?= get_label('view_amendments', 'View Amendments') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Archived Document -->
                        @if($contract->is_archived)
                        <div class="document-item p-3 rounded border border-secondary">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-secondary me-3">
                                    <i class="bx bx-archive"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0"><?= get_label('archived_document', 'Archived Document') ?></h6>
                                        <span class="badge bg-secondary"><?= get_label('archived', 'Archived') ?></span>
                                    </div>
                                    <small class="text-muted mb-2 d-block">
                                        <?= get_label('final_contract_archive', 'Final contract archive with all signatures and approvals') ?>
                                    </small>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-3">
                                                <i class="bx bx-calendar me-1"></i>{{ format_date($contract->archived_at) }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bx bx-user me-1"></i>{{ $contract->archivedBy->first_name ?? '' }} {{ $contract->archivedBy->last_name ?? '' }}
                                            </small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="viewArchivedContract({{ $contract->id }})">
                                            <i class="bx bx-show me-1"></i><?= get_label('view_archive', 'View Archive') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Document Management -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-cog text-primary me-2"></i>
                        <?= get_label('document_management', 'Document Management') ?>
                    </h5>
                    
                    <div class="document-actions">
                        <!-- Document Status Summary -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6 class="mb-3"><?= get_label('document_status', 'Document Status') ?></h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= get_label('total_documents', 'Total Documents') ?>:</span>
                                <span class="fw-bold">
                                    @php
                                    $totalDocs = 1; // Main contract
                                    if($contract->quantity_approval_signature || $contract->management_review_signature || $contract->accounting_review_signature || $contract->final_approval_signature) $totalDocs++;
                                    if($contract->journal_entry_number) $totalDocs++;
                                    if($contract->amendment_requested) $totalDocs++;
                                    if($contract->is_archived) $totalDocs++;
                                    @endphp
                                    {{ $totalDocs }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success"><?= get_label('signed', 'Signed') ?>:</span>
                                <span class="fw-bold text-success">
                                    {{ collect([$contract->quantity_approval_signature, $contract->management_review_signature, $contract->accounting_review_signature, $contract->final_approval_signature])->filter()->count() > 0 ? 1 : 0 }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-info"><?= get_label('system_generated', 'System Generated') ?>:</span>
                                <span class="fw-bold text-info">
                                    {{ $contract->journal_entry_number ? 1 : 0 }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Quick Document Actions -->
                        <div class="mb-4">
                            <h6 class="mb-3"><?= get_label('quick_actions', 'Quick Actions') ?></h6>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="generateContractPDF({{ $contract->id }})">
                                    <i class="bx bx-file me-1"></i><?= get_label('generate_contract_pdf', 'Generate Contract PDF') ?>
                                </button>
                                
                                @if($contract->workflow_status === 'approved' && !$contract->is_archived)
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="generateFinalArchive({{ $contract->id }})">
                                    <i class="bx bx-archive me-1"></i><?= get_label('create_final_archive', 'Create Final Archive') ?>
                                </button>
                                @endif
                                
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="exportDocumentHistory({{ $contract->id }})">
                                    <i class="bx bx-history me-1"></i><?= get_label('export_history', 'Export Document History') ?>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Document Security -->
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <h6 class="mb-2">
                                <i class="bx bx-shield text-success me-1"></i>
                                <?= get_label('document_security', 'Document Security') ?>
                            </h6>
                            <p class="mb-2 small text-success">
                                <i class="bx bx-check-circle me-1"></i>
                                <?= get_label('electronic_signatures_secured', 'All electronic signatures are cryptographically secured') ?>
                            </p>
                            <p class="mb-0 small text-success">
                                <i class="bx bx-check-circle me-1"></i>
                                <?= get_label('audit_trail_maintained', 'Complete audit trail maintained for all document changes') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Upload Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= get_label('upload_document', 'Upload Document') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="documentUploadForm">
                    @csrf
                    <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('document_type', 'Document Type') ?></label>
                        <select class="form-select" name="document_type" required>
                            <option value=""><?= get_label('select_document_type', 'Select document type') ?></option>
                            <option value="supporting"><?= get_label('supporting_document', 'Supporting Document') ?></option>
                            <option value="amendment"><?= get_label('amendment_document', 'Amendment Document') ?></option>
                            <option value="correspondence"><?= get_label('correspondence', 'Correspondence') ?></option>
                            <option value="other"><?= get_label('other', 'Other') ?></option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('document_title', 'Document Title') ?></label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('document_file', 'Document File') ?></label>
                        <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        <div class="form-text"><?= get_label('supported_formats', 'Supported formats: PDF, DOC, DOCX, JPG, PNG') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('cancel', 'Cancel') ?></button>
                <button type="button" class="btn btn-primary" onclick="uploadDocument()"><?= get_label('upload', 'Upload') ?></button>
            </div>
        </div>
    </div>
</div>