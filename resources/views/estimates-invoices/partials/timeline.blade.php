<div class="timeline-container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">
                <i class="bx bx-time-five text-info me-2"></i>
                <?= get_label('activity_timeline', 'Activity Timeline') ?>
            </h5>
            
            <div class="timeline">
                <!-- Extract Creation -->
                <div class="timeline-item mb-4">
                    <div class="d-flex">
                        <div class="timeline-badge bg-success me-3">
                            <i class="bx bx-file"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">
                                    {{ $estimate_invoice->type == 'estimate' ? get_label('extract_created', 'Extract Created') : get_label('invoice_created', 'Invoice Created') }}
                                </h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->created_at, true) }}</small>
                            </div>
                            <p class="mb-2 text-muted small">
                                <?= $estimate_invoice->type == 'estimate' ? get_label('extract_creation_description', 'Extract was created with items and calculations') : get_label('invoice_creation_description', 'Invoice was created with items and calculations') ?>
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs me-2">
                                    <img src="{{ $estimate_invoice->creator->photo ? asset('storage/' . $estimate_invoice->creator->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ $estimate_invoice->creator->first_name }} {{ $estimate_invoice->creator->last_name }}"
                                         width="20" height="20">
                                </div>
                                <small class="text-muted">
                                    {{ $estimate_invoice->creator->first_name }} {{ $estimate_invoice->creator->last_name }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status Changes -->
                @if($estimate_invoice->status !== 'draft')
                <div class="timeline-item mb-4">
                    <div class="d-flex">
                        <div class="timeline-badge bg-primary me-3">
                            <i class="bx bx-send"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('status_changed', 'Status Changed') ?></h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->updated_at, true) }}</small>
                            </div>
                            <p class="mb-2 text-muted small">
                                <?= get_label('extract_status_updated', 'Extract status was updated to') ?> <strong>{{ getExtractStatusText($estimate_invoice->status) }}</strong>
                            </p>
                            <div class="status-badge">
                                <span class="badge bg-{{ getExtractStatusColor($estimate_invoice->status) }}">
                                    {{ getExtractStatusText($estimate_invoice->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Submission Event -->
                @if(in_array($estimate_invoice->status, ['submitted', 'under_review', 'approved', 'archived']))
                <div class="timeline-item mb-4">
                    <div class="d-flex">
                        <div class="timeline-badge bg-warning me-3">
                            <i class="bx bx-send"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('extract_submitted', 'Extract Submitted') ?></h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->updated_at, true) }}</small>
                            </div>
                            <p class="mb-2 text-muted small">
                                <?= get_label('extract_submission_description', 'Extract was submitted for review and approval') ?>
                            </p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Review Process -->
                @if(in_array($estimate_invoice->status, ['under_review', 'approved', 'archived']))
                <div class="timeline-item mb-4">
                    <div class="d-flex">
                        <div class="timeline-badge bg-info me-3">
                            <i class="bx bx-group"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('under_review', 'Under Review') ?></h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->updated_at, true) }}</small>
                            </div>
                            <p class="mb-2 text-muted small">
                                <?= get_label('extract_review_description', 'Extract is currently under review by authorized personnel') ?>
                            </p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Approval Event -->
                @if(in_array($estimate_invoice->status, ['approved', 'archived']))
                <div class="timeline-item mb-4">
                    <div class="d-flex">
                        <div class="timeline-badge bg-success me-3">
                            <i class="bx bx-check-shield"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('extract_approved', 'Extract Approved') ?></h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->updated_at, true) }}</small>
                            </div>
                            <p class="mb-2 text-muted small">
                                <?= get_label('extract_approval_description', 'Extract has been fully approved and authorized') ?>
                            </p>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-badge-check text-success me-1"></i>
                                <small class="text-success"><?= get_label('final_approval_completed', 'Final approval completed') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Payment Records (for invoices) -->
                @if($estimate_invoice->type == 'invoice' && count($estimate_invoice->payments) > 0)
                <div class="timeline-item mb-4">
                    <div class="d-flex">
                        <div class="timeline-badge bg-primary me-3">
                            <i class="bx bx-credit-card"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('payments_recorded', 'Payments Recorded') ?></h6>
                                <small class="text-muted">
                                    {{ count($estimate_invoice->payments) }} <?= get_label('payments', 'payments') ?>
                                </small>
                            </div>
                            <p class="mb-2 text-muted small">
                                <?= get_label('payment_records_description', 'Payment records have been added to this invoice') ?>
                            </p>
                            <div class="payment-summary">
                                <small class="text-primary">
                                    <i class="bx bx-money me-1"></i>
                                    <?= get_label('total_paid', 'Total Paid') ?>: {{ format_currency($estimate_invoice->payments->sum('amount')) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Archiving Event -->
                @if($estimate_invoice->status === 'archived')
                <div class="timeline-item">
                    <div class="d-flex">
                        <div class="timeline-badge bg-secondary me-3">
                            <i class="bx bx-archive"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('extract_archived', 'Extract Archived') ?></h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->updated_at, true) }}</small>
                            </div>
                            <p class="mb-2 text-muted small">
                                <?= get_label('extract_archived_description', 'Extract has been archived and closed for final record keeping') ?>
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Timeline Summary -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="mb-4">
                <i class="bx bx-stats text-primary me-2"></i>
                <?= get_label('timeline_summary', 'Timeline Summary') ?>
            </h5>
            
            <div class="timeline-summary">
                <!-- Duration Statistics -->
                <div class="mb-4 p-3 bg-light rounded">
                    <h6 class="mb-3"><?= get_label('duration_stats', 'Duration Statistics') ?></h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span><?= get_label('total_duration', 'Total Duration') ?>:</span>
                                <span class="fw-bold">
                                    @php
                                    $totalDuration = $estimate_invoice->created_at->diffInDays(now());
                                    @endphp
                                    {{ $totalDuration }} <?= get_label('days', 'days') ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span><?= get_label('current_status', 'Current Status') ?>:</span>
                                <span class="fw-bold text-{{ getExtractStatusColor($estimate_invoice->status) }}">
                                    {{ getExtractStatusText($estimate_invoice->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span><?= get_label('completion_rate', 'Completion Rate') ?>:</span>
                                <span class="fw-bold text-success">{{ $statusProgress[$estimate_invoice->status] ?? 0 }}%</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span><?= get_label('items_count', 'Items Count') ?>:</span>
                                <span class="fw-bold">{{ count($estimate_invoice->items) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Key Milestones -->
                <div class="mb-4">
                    <h6 class="mb-3"><?= get_label('key_milestones', 'Key Milestones') ?></h6>
                    <div class="milestones-list">
                        <div class="milestone-item mb-3 p-2 rounded border {{ $estimate_invoice->status !== 'draft' ? 'border-success' : 'border-light' }}">
                            <div class="d-flex align-items-center">
                                <i class="bx {{ $estimate_invoice->status !== 'draft' ? 'bx-check-circle text-success' : 'bx-circle text-muted' }} me-2"></i>
                                <div>
                                    <div class="fw-medium small"><?= get_label('extract_created', 'Extract Created') ?></div>
                                    <small class="text-muted">{{ format_date($estimate_invoice->created_at) }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="milestone-item mb-3 p-2 rounded border {{ in_array($estimate_invoice->status, ['submitted', 'under_review', 'approved', 'archived']) ? 'border-success' : 'border-light' }}">
                            <div class="d-flex align-items-center">
                                <i class="bx {{ in_array($estimate_invoice->status, ['submitted', 'under_review', 'approved', 'archived']) ? 'bx-check-circle text-success' : 'bx-circle text-muted' }} me-2"></i>
                                <div>
                                    <div class="fw-medium small"><?= get_label('extract_submitted', 'Extract Submitted') ?></div>
                                    @if(in_array($estimate_invoice->status, ['submitted', 'under_review', 'approved', 'archived']))
                                    <small class="text-muted">{{ format_date($estimate_invoice->updated_at) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="milestone-item p-2 rounded border {{ in_array($estimate_invoice->status, ['approved', 'archived']) ? 'border-success' : 'border-light' }}">
                            <div class="d-flex align-items-center">
                                <i class="bx {{ in_array($estimate_invoice->status, ['approved', 'archived']) ? 'bx-check-circle text-success' : 'bx-circle text-muted' }} me-2"></i>
                                <div>
                                    <div class="fw-medium small"><?= get_label('extract_approved', 'Extract Approved') ?></div>
                                    @if(in_array($estimate_invoice->status, ['approved', 'archived']))
                                    <small class="text-muted">{{ format_date($estimate_invoice->updated_at) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="p-3 bg-info bg-opacity-10 rounded">
                    <h6 class="mb-2">
                        <i class="bx bx-pulse text-info me-1"></i>
                        <?= get_label('recent_activity', 'Recent Activity') ?>
                    </h6>
                    <p class="mb-0 text-info small">
                        <i class="bx bx-time me-1"></i>
                        <?= get_label('last_updated', 'Last updated') ?>: {{ format_date($estimate_invoice->updated_at, true) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>