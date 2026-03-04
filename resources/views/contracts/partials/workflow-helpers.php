@php
function getWorkflowStatusColor($status) {
    $statusColors = [
        'draft' => 'secondary',
        'quantity_approval' => 'warning',
        'management_review' => 'info',
        'accounting_review' => 'primary',
        'final_approval' => 'dark',
        'approved' => 'success',
        'archived' => 'secondary',
        'amendment_pending' => 'warning',
        'amendment_approved' => 'info'
    ];
    return $statusColors[$status] ?? 'secondary';
}

function getWorkflowStatusGradient($status) {
    $gradients = [
        'draft' => 'linear-gradient(45deg, #6c757d, #495057)',
        'quantity_approval' => 'linear-gradient(45deg, #ffc107, #e0a800)',
        'management_review' => 'linear-gradient(45deg, #17a2b8, #138496)',
        'accounting_review' => 'linear-gradient(45deg, #007bff, #0069d9)',
        'final_approval' => 'linear-gradient(45deg, #343a40, #23272b)',
        'approved' => 'linear-gradient(45deg, #28a745, #218838)',
        'archived' => 'linear-gradient(45deg, #6c757d, #545b62)',
        'amendment_pending' => 'linear-gradient(45deg, #ffc107, #e0a800)',
        'amendment_approved' => 'linear-gradient(45deg, #17a2b8, #138496)'
    ];
    return $gradients[$status] ?? 'linear-gradient(45deg, #6c757d, #495057)';
}

function getWorkflowStatusIcon($status) {
    $statusIcons = [
        'draft' => 'bx-edit',
        'quantity_approval' => 'bx-list-check',
        'management_review' => 'bx-group',
        'accounting_review' => 'bx-calculator',
        'final_approval' => 'bx-check-shield',
        'approved' => 'bx-badge-check',
        'archived' => 'bx-archive',
        'amendment_pending' => 'bx-edit',
        'amendment_approved' => 'bx-check-circle'
    ];
    return $statusIcons[$status] ?? 'bx-help-circle';
}

function getWorkflowStatusText($status) {
    return ucfirst(str_replace('_', ' ', $status));
}

function getProgressColor($percentage) {
    if ($percentage >= 80) return 'success';
    if ($percentage >= 50) return 'warning';
    if ($percentage >= 20) return 'info';
    return 'secondary';
}

function getApprovalStageStatus($contract, $stage) {
    $statusField = $stage . '_status';
    return $contract->$statusField ?? 'pending';
}

function getApprovalStageColor($status) {
    $statusColors = [
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'modified' => 'info'
    ];
    return $statusColors[$status] ?? 'secondary';
}

function getPriorityColor($priority) {
    $priorityColors = [
        'low' => 'info',
        'medium' => 'warning',
        'high' => 'danger',
        'critical' => 'dark'
    ];
    return $priorityColors[$priority] ?? 'secondary';
}

function getRiskLevelColor($risk) {
    $riskColors = [
        'low' => 'success',
        'medium' => 'warning',
        'high' => 'danger'
    ];
    return $riskColors[$risk] ?? 'secondary';
}

function getContractTypeBadge($type) {
    $typeBadges = [
        'construction' => 'primary',
        'maintenance' => 'success',
        'consulting' => 'info',
        'supply' => 'warning',
        'service' => 'danger'
    ];
    return $typeBadges[$type] ?? 'secondary';
}

function getContractHealthStatus($progress, $deadlineDays) {
    if ($progress >= 90) return ['status' => 'excellent', 'color' => 'success', 'icon' => 'bx-check-circle'];
    if ($progress >= 70 && $deadlineDays > 30) return ['status' => 'good', 'color' => 'info', 'icon' => 'bx-check'];
    if ($progress >= 50 && $deadlineDays > 15) return ['status' => 'fair', 'color' => 'warning', 'icon' => 'bx-error'];
    if ($progress < 50 || $deadlineDays <= 15) return ['status' => 'poor', 'color' => 'danger', 'icon' => 'bx-x-circle'];
    return ['status' => 'unknown', 'color' => 'secondary', 'icon' => 'bx-help-circle'];
}

function getFinancialHealthColor($percentage) {
    if ($percentage >= 90) return 'success';
    if ($percentage >= 70) return 'info';
    if ($percentage >= 50) return 'warning';
    return 'danger';
}

function formatPercentage($value, $total) {
    return $total > 0 ? round(($value / $total) * 100, 2) : 0;
}

function getTimelineStatus($startDate, $endDate, $currentDate = null) {
    $currentDate = $currentDate ?? now();
    $start = is_string($startDate) ? strtotime($startDate) : $startDate;
    $end = is_string($endDate) ? strtotime($endDate) : $endDate;
    $current = is_string($currentDate) ? strtotime($currentDate) : $currentDate;
    
    if ($current < $start) return ['status' => 'upcoming', 'color' => 'info', 'text' => 'Upcoming'];
    if ($current > $end) return ['status' => 'completed', 'color' => 'success', 'text' => 'Completed'];
    return ['status' => 'active', 'color' => 'warning', 'text' => 'Active'];
}

function getDocumentStatusBadge($status) {
    $statusBadges = [
        'draft' => 'secondary',
        'submitted' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'archived' => 'dark'
    ];
    return $statusBadges[$status] ?? 'secondary';
}

function getActionButtonsConfig($contract) {
    $buttons = [];
    
    // Edit button
    if (auth()->user()->can('edit_contracts')) {
        $buttons['edit'] = [
            'url' => route('contracts.edit', $contract->id),
            'icon' => 'bx-edit',
            'text' => get_label('edit', 'Edit'),
            'class' => 'btn-warning',
            'tooltip' => get_label('edit_contract', 'Edit Contract')
        ];
    }
    
    // Delete button
    if (auth()->user()->can('delete_contracts') && $contract->workflow_status === 'draft') {
        $buttons['delete'] = [
            'url' => 'javascript:void(0);',
            'icon' => 'bx-trash',
            'text' => get_label('delete', 'Delete'),
            'class' => 'btn-danger delete',
            'tooltip' => get_label('delete_contract', 'Delete Contract'),
            'data' => ['id' => $contract->id, 'type' => 'contracts']
        ];
    }
    
    // Archive button
    if (auth()->user()->can('manage_contracts') && $contract->workflow_status === 'approved') {
        $buttons['archive'] = [
            'url' => 'javascript:void(0);',
            'icon' => 'bx-archive',
            'text' => get_label('archive', 'Archive'),
            'class' => 'btn-outline-dark archive-contract',
            'tooltip' => get_label('archive_contract', 'Archive Contract'),
            'data' => ['id' => $contract->id]
        ];
    }
    
    // Print button
    $buttons['print'] = [
        'url' => 'javascript:void(0);',
        'icon' => 'bx-printer',
        'text' => get_label('print', 'Print'),
        'class' => 'btn-outline-secondary',
        'tooltip' => get_label('print_contract', 'Print Contract'),
        'id' => 'print-contract'
    ];
    
    // Mind map button
    $buttons['mind_map'] = [
        'url' => url('contracts/mind-map/' . $contract->id),
        'icon' => 'bx-sitemap',
        'text' => get_label('mind_map', 'Mind Map'),
        'class' => 'btn-primary',
        'tooltip' => get_label('view_mind_map', 'View Mind Map')
    ];
    
    return $buttons;
}
@endphp