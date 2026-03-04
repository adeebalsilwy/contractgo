<div class="documents-container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">
                    <i class="bx bx-file text-warning me-2"></i>
                    <?= get_label('extract_documents', 'Extract Documents') ?>
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i class="bx bx-plus me-1"></i><?= get_label('upload_document', 'Upload Document') ?>
                </button>
            </div>
            
            <div class="documents-list">
                <!-- Main Extract Document -->
                <div class="document-item mb-3 p-3 rounded border border-primary">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-label-primary me-3">
                            <i class="bx bx-file"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0">
                                    {{ $estimate_invoice->type == 'estimate' ? get_label('extract_document', 'Extract Document') : get_label('invoice_document', 'Invoice Document') }}
                                </h6>
                                <span class="badge bg-primary">PDF</span>
                            </div>
                            <small class="text-muted mb-2 d-block">
                                <?= $estimate_invoice->type == 'estimate' ? get_label('main_extract_description', 'Primary extract document with all items and calculations') : get_label('main_invoice_description', 'Primary invoice document with all items and calculations') ?>
                            </small>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-3">
                                        <i class="bx bx-calendar me-1"></i>{{ format_date($estimate_invoice->created_at) }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="bx bx-user me-1"></i>{{ $estimate_invoice->creator->first_name ?? '' }} {{ $estimate_invoice->creator->last_name ?? '' }}
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('estimates-invoices/pdf/' . $estimate_invoice->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-download me-1"></i><?= get_label('download', 'Download') ?>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="viewDocument({{ $estimate_invoice->id }}, 'extract')">
                                        <i class="bx bx-show me-1"></i><?= get_label('view', 'View') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Generated Documents -->
                <div class="document-item mb-3 p-3 rounded border border-success">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-label-success me-3">
                            <i class="bx bx-cog"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0"><?= get_label('generated_documents', 'Generated Documents') ?></h6>
                                <span class="badge bg-success">
                                    {{ $estimate_invoice->type == 'estimate' ? 'مستخلص' : 'فاتورة' }}
                                </span>
                            </div>
                            <small class="text-muted mb-2 d-block">
                                <?= get_label('system_generated_documents', 'System generated documents and reports') ?>
                            </small>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="generated-list">
                                    <span class="badge bg-success me-1">
                                        <i class="bx bx-file me-1"></i><?= get_label('pdf_version', 'PDF Version') ?>
                                    </span>
                                    <span class="badge bg-success me-1">
                                        <i class="bx bx-receipt me-1"></i><?= get_label('print_version', 'Print Version') ?>
                                    </span>
                                    @if($estimate_invoice->contract_id)
                                    <span class="badge bg-success">
                                        <i class="bx bx-link me-1"></i><?= get_label('contract_linked', 'Contract Linked') ?>
                                    </span>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#generatedDocumentsModal">
                                    <i class="bx bx-show me-1"></i><?= get_label('view_all', 'View All') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Documents -->
                @if($estimate_invoice->type == 'invoice' && count($estimate_invoice->payments) > 0)
                <div class="document-item mb-3 p-3 rounded border border-info">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-label-info me-3">
                            <i class="bx bx-credit-card"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0"><?= get_label('payment_documents', 'Payment Documents') ?></h6>
                                <span class="badge bg-info">{{ count($estimate_invoice->payments) }} <?= get_label('payments', 'Payments') ?></span>
                            </div>
                            <small class="text-muted mb-2 d-block">
                                <?= get_label('payment_records_and_receipts', 'Payment records and receipts for this invoice') ?>
                            </small>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted">
                                        <i class="bx bx-money me-1"></i>
                                        <?= get_label('total_paid', 'Total Paid') ?>: {{ format_currency($estimate_invoice->payments->sum('amount')) }}
                                    </small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#paymentDocumentsModal">
                                    <i class="bx bx-show me-1"></i><?= get_label('view_payments', 'View Payments') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Approval Documents -->
                @if(in_array($estimate_invoice->status, ['approved', 'archived']))
                <div class="document-item p-3 rounded border border-warning">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-label-warning me-3">
                            <i class="bx bx-check-shield"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0"><?= get_label('approval_documents', 'Approval Documents') ?></h6>
                                <span class="badge bg-warning"><?= get_label('approved', 'Approved') ?></span>
                            </div>
                            <small class="text-muted mb-2 d-block">
                                <?= get_label('approval_records_and_signatures', 'Approval records and electronic signatures') ?>
                            </small>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="approval-status">
                                    <small class="text-success">
                                        <i class="bx bx-check-circle me-1"></i>
                                        <?= get_label('final_approval_completed', 'Final approval completed on') ?> {{ format_date($estimate_invoice->updated_at) }}
                                    </small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#approvalDocumentsModal">
                                    <i class="bx bx-show me-1"></i><?= get_label('view_approvals', 'View Approvals') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Document Management Summary -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="mb-4">
                <i class="bx bx-cog text-primary me-2"></i>
                <?= get_label('document_management', 'Document Management') ?>
            </h5>
            
            <div class="document-summary">
                <!-- Document Statistics -->
                <div class="mb-4 p-3 bg-light rounded">
                    <h6 class="mb-3"><?= get_label('document_statistics', 'Document Statistics') ?></h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span><?= get_label('total_documents', 'Total Documents') ?>:</span>
                                <span class="fw-bold">
                                    @php
                                    $totalDocs = 1; // Main document
                                    if($estimate_invoice->type == 'invoice' && count($estimate_invoice->payments) > 0) $totalDocs++;
                                    if(in_array($estimate_invoice->status, ['approved', 'archived'])) $totalDocs++;
                                    @endphp
                                    {{ $totalDocs }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span class="text-success"><?= get_label('generated', 'Generated') ?>:</span>
                                <span class="fw-bold text-success">1</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span class="text-info"><?= get_label('payments', 'Payments') ?>:</span>
                                <span class="fw-bold text-info">{{ $estimate_invoice->type == 'invoice' ? count($estimate_invoice->payments) : 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span class="text-warning"><?= get_label('approvals', 'Approvals') ?>:</span>
                                <span class="fw-bold text-warning">{{ in_array($estimate_invoice->status, ['approved', 'archived']) ? 1 : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Document Actions -->
                <div class="mb-4">
                    <h6 class="mb-3"><?= get_label('quick_actions', 'Quick Actions') ?></h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="generateExtractPDF({{ $estimate_invoice->id }})">
                            <i class="bx bx-file me-1"></i><?= get_label('generate_pdf', 'Generate PDF') ?>
                        </button>
                        
                        @if($estimate_invoice->status === 'approved' && !$estimate_invoice->is_archived)
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="createFinalArchive({{ $estimate_invoice->id }})">
                            <i class="bx bx-archive me-1"></i><?= get_label('create_archive', 'Create Archive') ?>
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="exportDocumentHistory({{ $estimate_invoice->id }})">
                            <i class="bx bx-history me-1"></i><?= get_label('export_history', 'Export History') ?>
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
                        <?= get_label('documents_secured', 'All documents are securely stored and version controlled') ?>
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

<!-- Upload Document Modal -->
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
                    <input type="hidden" name="estimate_invoice_id" value="{{ $estimate_invoice->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('document_type', 'Document Type') ?></label>
                        <select class="form-select" name="document_type" required>
                            <option value=""><?= get_label('select_document_type', 'Select document type') ?></option>
                            <option value="supporting"><?= get_label('supporting_document', 'Supporting Document') ?></option>
                            <option value="correspondence"><?= get_label('correspondence', 'Correspondence') ?></option>
                            <option value="specification"><?= get_label('specification', 'Specification') ?></option>
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