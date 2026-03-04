<div class="analytics-dashboard">
    <div class="row">
        <!-- Financial Overview Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-money me-2"></i>{{ get_label('financial_overview', 'Financial Overview') }}</h5>
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="financial-summary">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <small class="text-muted">{{ get_label('contract_value', 'Contract Value') }}</small>
                                <h4 class="mb-0">{{ format_currency($contract->value) }}</h4>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ get_label('currency', 'YER') }}</small>
                            </div>
                        </div>
                        
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-{{ getFinancialHealthColor($contract->progress_percentage) }}" 
                                 style="width: {{ $contract->progress_percentage }}%"></div>
                        </div>
                        
                        <div class="financial-breakdown">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ get_label('total_invoiced', 'Total Invoiced') }}</span>
                                <span class="fw-medium">{{ format_currency($contract->estimates->sum('final_total')) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ get_label('total_paid', 'Total Paid') }}</span>
                                <span class="fw-medium text-success">{{ format_currency($contract->payments_sum ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ get_label('outstanding_balance', 'Outstanding Balance') }}</span>
                                <span class="fw-medium text-danger">{{ format_currency(($contract->value ?? 0) - ($contract->payments_sum ?? 0)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Task Analytics Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-task me-2"></i>{{ get_label('task_analytics', 'Task Analytics') }}</h5>
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="task-analytics">
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="analytics-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                     style="width: 60px; height: 60px;">
                                    <span class="fw-bold">{{ $contract->tasks_count ?? 0 }}</span>
                                </div>
                                <small class="text-muted">{{ get_label('total', 'Total') }}</small>
                            </div>
                            <div class="col-4">
                                <div class="analytics-circle bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                     style="width: 60px; height: 60px;">
                                    <span class="fw-bold">{{ $contract->completed_tasks_count ?? 0 }}</span>
                                </div>
                                <small class="text-muted">{{ get_label('completed', 'Completed') }}</small>
                            </div>
                            <div class="col-4">
                                <div class="analytics-circle bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                     style="width: 60px; height: 60px;">
                                    <span class="fw-bold">{{ ($contract->tasks_count ?? 0) - ($contract->completed_tasks_count ?? 0) }}</span>
                                </div>
                                <small class="text-muted">{{ get_label('pending', 'Pending') }}</small>
                            </div>
                        </div>
                        
                        <div class="task-status-breakdown">
                            @php
                            $taskStatuses = $statuses ?? collect([]);
                            $tasks = $tasks ?? collect([]);
                            @endphp
                            @foreach($taskStatuses as $status)
                                @php
                                $statusId = is_object($status) ? $status->id : ($status['id'] ?? null);
                                $count = $tasks->where('status_id', $statusId)->count();
                                $percentage = ($contract->tasks_count ?? 1) > 0 ? ($count / $contract->tasks_count) * 100 : 0;
                                $color = is_object($status) ? $status->color : ($status['color'] ?? 'secondary');
                                $title = is_object($status) ? $status->title : ($status['title'] ?? 'Unknown');
                                @endphp
                                @if($count > 0)
                                <div class="status-item mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <div class="status-indicator bg-{{ $color }} me-2" style="width: 12px; height: 12px; border-radius: 50%;"></div>
                                            <span class="small">{{ $title }}</span>
                                        </div>
                                        <span class="small fw-medium">{{ $count }}</span>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-{{ $color }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timeline Health Card -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-time me-2"></i>{{ get_label('timeline_health', 'Timeline Health') }}</h5>
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline-health">
                        @php
                        $startDate = $contract->start_date ? strtotime($contract->start_date) : time();
                        $endDate = $contract->end_date ? strtotime($contract->end_date) : strtotime('+30 days');
                        $currentDate = time();
                        $totalDays = ($endDate - $startDate) / (60 * 60 * 24);
                        $elapsedDays = ($currentDate - $startDate) / (60 * 60 * 24);
                        $remainingDays = $totalDays - $elapsedDays;
                        $timelinePercentage = $totalDays > 0 ? ($elapsedDays / $totalDays) * 100 : 0;
                        
                        $timelineStatus = getTimelineStatus($startDate, $endDate, $currentDate);
                        @endphp
                        
                        <div class="timeline-indicator text-center mb-4">
                            <div class="status-badge bg-{{ $timelineStatus['color'] }} text-white rounded-pill px-3 py-2 d-inline-block mb-2">
                                <i class="bx {{ $timelineStatus['status'] === 'active' ? 'bx-play-circle' : ($timelineStatus['status'] === 'completed' ? 'bx-check-circle' : 'bx-calendar') }} me-1"></i>
                                {{ $timelineStatus['text'] }}
                            </div>
                            <div class="d-flex justify-content-center gap-3 text-muted small">
                                <span><i class="bx bx-calendar-check me-1"></i>{{ format_date($contract->start_date) }}</span>
                                <span><i class="bx bx-calendar-x me-1"></i>{{ format_date($contract->end_date) }}</span>
                            </div>
                        </div>
                        
                        <div class="timeline-progress mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">{{ get_label('time_elapsed', 'Time Elapsed') }}</small>
                                <small class="fw-medium">{{ round($timelinePercentage, 1) }}%</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ $timelineStatus['color'] }}" 
                                     style="width: {{ min($timelinePercentage, 100) }}%"></div>
                            </div>
                        </div>
                        
                        <div class="timeline-metrics">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="metric-card p-2 rounded bg-light">
                                        <h5 class="mb-1">{{ round($totalDays) }}</h5>
                                        <small class="text-muted">{{ get_label('total_days', 'Total Days') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metric-card p-2 rounded bg-light">
                                        <h5 class="mb-1 {{ $remainingDays < 0 ? 'text-danger' : 'text-warning' }}">{{ round($remainingDays) }}</h5>
                                        <small class="text-muted">{{ get_label('days_remaining', 'Days Remaining') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metric-card p-2 rounded bg-light">
                                        <h5 class="mb-1">{{ $contract->milestones_count ?? 0 }}</h5>
                                        <small class="text-muted">{{ get_label('milestones', 'Milestones') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="row">
        <div class="col-xl-8 col-md-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0"><i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>{{ get_label('performance_trends', 'Performance Trends') }}</h5>
                </div>
                <div class="card-body">
                    <div id="performanceChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0"><i class="bx bx-pie-chart-alt me-2 text-success"></i>{{ get_label('resource_allocation', 'Resource Allocation') }}</h5>
                </div>
                <div class="card-body">
                    <div id="resourceChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>