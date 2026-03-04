<div class="status-summary-container">
    <div class="row">
        <!-- Status Overview Cards -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-stats text-primary me-2"></i>
                        <?= get_label('status_overview', 'Status Overview') ?>
                    </h5>
                    
                    <div class="status-cards">
                        <!-- Workflow Status -->
                        <div class="status-card mb-3 p-3 rounded border border-{{ getWorkflowStatusColor($contract->workflow_status) }}">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-{{ getWorkflowStatusColor($contract->workflow_status) }} me-3">
                                    <i class="bx {{ getWorkflowStatusIcon($contract->workflow_status) }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= get_label('workflow_status', 'Workflow Status') ?></h6>
                                        <span class="badge bg-{{ getWorkflowStatusColor($contract->workflow_status) }}">
                                            {{ getWorkflowStatusText($contract->workflow_status) }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        <?= get_label('current_workflow_state', 'Current workflow state') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Status -->
                        <div class="status-card mb-3 p-3 rounded border border-{{ getProgressColor($contract->progress_percentage) }}">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-{{ getProgressColor($contract->progress_percentage) }} me-3">
                                    <i class="bx bx-task"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= get_label('progress_status', 'Progress Status') ?></h6>
                                        <span class="fw-bold text-{{ getProgressColor($contract->progress_percentage) }}">
                                            {{ $contract->progress_percentage }}%
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-{{ getProgressColor($contract->progress_percentage) }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $contract->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Approval Status -->
                        <div class="status-card p-3 rounded border border-{{ $contract->workflow_status === 'approved' ? 'success' : 'warning' }}">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-label-{{ $contract->workflow_status === 'approved' ? 'success' : 'warning' }} me-3">
                                    <i class="bx bx-check-shield"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= get_label('approval_status', 'Approval Status') ?></h6>
                                        <span class="badge bg-{{ $contract->workflow_status === 'approved' ? 'success' : 'warning' }}">
                                            {{ $contract->workflow_status === 'approved' ? get_label('approved', 'Approved') : get_label('pending', 'Pending') }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        {{ $contract->workflow_status === 'approved' ? get_label('fully_approved', 'Fully approved and completed') : get_label('approval_in_progress', 'Approval in progress') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Status Breakdown -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-detail text-info me-2"></i>
                        <?= get_label('detailed_status', 'Detailed Status') ?>
                    </h5>
                    
                    <div class="status-breakdown">
                        <!-- Approval Stages Status -->
                        <div class="mb-4">
                            <h6 class="mb-3"><?= get_label('approval_stages', 'Approval Stages') ?></h6>
                            
                            @php
                            $approvalStages = [
                                'quantity_approval' => [
                                    'title' => get_label('quantity_approval', 'Quantity Approval'),
                                    'status' => $contract->quantity_approval_status ?? 'pending',
                                    'date' => $contract->quantity_approval_signed_at ?? null
                                ],
                                'management_review' => [
                                    'title' => get_label('management_review', 'Management Review'),
                                    'status' => $contract->management_review_status ?? 'pending',
                                    'date' => $contract->management_review_signed_at ?? null
                                ],
                                'accounting_review' => [
                                    'title' => get_label('accounting_review', 'Accounting Review'),
                                    'status' => $contract->accounting_review_status ?? 'pending',
                                    'date' => $contract->accounting_review_signed_at ?? null
                                ],
                                'final_approval' => [
                                    'title' => get_label('final_approval', 'Final Approval'),
                                    'status' => $contract->final_approval_status ?? 'pending',
                                    'date' => $contract->final_approval_signed_at ?? null
                                ]
                            ];
                            @endphp
                            
                            @foreach($approvalStages as $stage => $data)
                            <div class="stage-item mb-3 p-2 rounded border border-{{ getApprovalStageColor($data['status']) }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium small">{{ $data['title'] }}</div>
                                        <small class="text-muted">{{ ucfirst($data['status']) }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-{{ getApprovalStageColor($data['status']) }} me-2">
                                            {{ ucfirst($data['status']) }}
                                        </span>
                                        @if($data['date'])
                                        <small class="text-muted">{{ format_date($data['date']) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Additional Status Indicators -->
                        <div>
                            <h6 class="mb-3"><?= get_label('additional_indicators', 'Additional Indicators') ?></h6>
                            
                            <div class="indicator-item mb-2 p-2 rounded bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?= get_label('archived_status', 'Archived Status') ?>:</span>
                                    <span class="badge bg-{{ $contract->is_archived ? 'success' : 'secondary' }}">
                                        {{ $contract->is_archived ? get_label('archived', 'Archived') : get_label('not_archived', 'Not Archived') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="indicator-item mb-2 p-2 rounded bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?= get_label('amendment_status', 'Amendment Status') ?>:</span>
                                    <span class="badge bg-{{ $contract->amendment_requested ? 'warning' : 'secondary' }}">
                                        {{ $contract->amendment_requested ? get_label('amendment_requested', 'Amendment Requested') : get_label('no_amendments', 'No Amendments') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="indicator-item p-2 rounded bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?= get_label('electronic_signatures', 'Electronic Signatures') ?>:</span>
                                    <span class="badge bg-{{ collect([$contract->quantity_approval_signature, $contract->management_review_signature, $contract->accounting_review_signature, $contract->final_approval_signature])->filter()->count() > 0 ? 'success' : 'secondary' }}">
                                        {{ collect([$contract->quantity_approval_signature, $contract->management_review_signature, $contract->accounting_review_signature, $contract->final_approval_signature])->filter()->count() }} <?= get_label('applied', 'Applied') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Summary Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">
                        <i class="bx bx-bar-chart-alt-2 text-success me-2"></i>
                        <?= get_label('status_analytics', 'Status Analytics') ?>
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Status Distribution Chart -->
                            <div id="statusChartContainer" class="mb-4">
                                <canvas id="statusChart" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Status Metrics -->
                            <div class="status-metrics">
                                <div class="metric-item mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-label-primary me-3">
                                            <i class="bx bx-time-five"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-primary">{{ $contract->created_at->diffInDays(now()) }} <?= get_label('days', 'days') ?></div>
                                            <small class="text-muted"><?= get_label('since_creation', 'Since Creation') ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="metric-item mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-label-success me-3">
                                            <i class="bx bx-check"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-success">
                                                {{ collect([$contract->quantity_approval_status, $contract->management_review_status, $contract->accounting_review_status, $contract->final_approval_status])->filter(fn($status) => $status === 'approved')->count() }}
                                                / 4
                                            </div>
                                            <small class="text-muted"><?= get_label('stages_completed', 'Stages Completed') ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="metric-item p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-label-info me-3">
                                            <i class="bx bx-calendar"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-info">{{ format_date($contract->end_date) }}</div>
                                            <small class="text-muted"><?= get_label('contract_end_date', 'Contract End Date') ?></small>
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
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize status chart
    if (document.getElementById('statusChart')) {
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    '<?= get_label('pending', 'Pending') ?>',
                    '<?= get_label('approved', 'Approved') ?>',
                    '<?= get_label('rejected', 'Rejected') ?>',
                    '<?= get_label('modified', 'Modified') ?>'
                ],
                datasets: [{
                    data: [
                        {{ collect([$contract->quantity_approval_status, $contract->management_review_status, $contract->accounting_review_status, $contract->final_approval_status])->filter(fn($status) => $status === 'pending')->count() }},
                        {{ collect([$contract->quantity_approval_status, $contract->management_review_status, $contract->accounting_review_status, $contract->final_approval_status])->filter(fn($status) => $status === 'approved')->count() }},
                        {{ collect([$contract->quantity_approval_status, $contract->management_review_status, $contract->accounting_review_status, $contract->final_approval_status])->filter(fn($status) => $status === 'rejected')->count() }},
                        {{ collect([$contract->quantity_approval_status, $contract->management_review_status, $contract->accounting_review_status, $contract->final_approval_status])->filter(fn($status) => $status === 'modified')->count() }}
                    ],
                    backgroundColor: [
                        '#ff9f43',
                        '#28c76f',
                        '#ea5455',
                        '#00cfe8'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush