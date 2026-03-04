<div class="professional-header mb-4">
    <div class="card border-0 shadow-lg">
        <div class="card-body p-0">
            <!-- Header with Gradient Background -->
            <div class="header-gradient" style="background: {{ getWorkflowStatusGradient($contract->workflow_status) }}; padding: 2rem;">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div class="flex-grow-1 me-3">
                        <div class="d-flex align-items-center mb-3">
                            <h1 class="text-white mb-0 me-3">{{ $contract->title }}</h1>
                            <div class="d-flex gap-2">
                                <span class="badge bg-white text-dark fs-6">
                                    <i class="bx bx-hash me-1"></i>ID: {{ $contract->id }}
                                </span>
                                <span class="badge bg-white text-dark fs-6">
                                    <i class="bx bx-calendar me-1"></i>{{ format_date($contract->created_at) }}
                                </span>
                                <span class="badge bg-{{ getWorkflowStatusColor($contract->workflow_status) }} text-white fs-6">
                                    <i class="{{ getWorkflowStatusIcon($contract->workflow_status) }} me-1"></i>
                                    {{ getWorkflowStatusText($contract->workflow_status) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-4 text-white-50">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-calendar-check me-2"></i>
                                <span>{{ format_date($contract->start_date) }} - {{ format_date($contract->end_date) }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-money me-2"></i>
                                <span>{{ format_currency($contract->value) }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-task me-2"></i>
                                <span>{{ $contract->tasks_count ?? 0 }} {{ get_label('tasks', 'Tasks') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-user me-2"></i>
                                <span>{{ $contract->users_count ?? 0 }} {{ get_label('users', 'Users') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <!-- Favorite and Pin Icons -->
                        <div class="d-flex flex-column gap-2">
                            <a href="javascript:void(0);" class="text-white" data-bs-toggle="tooltip" 
                               data-bs-placement="left" 
                               data-bs-original-title="{{ getFavoriteStatus($contract->id) ? get_label('remove_favorite', 'Remove from favorites') : get_label('add_favorite', 'Add to favorites') }}">
                                <i class='bx {{ getFavoriteStatus($contract->id) ? "bxs" : "bx" }}-star favorite-icon' 
                                   data-id="{{ $contract->id }}" 
                                   data-favorite="{{ getFavoriteStatus($contract->id) ? 1 : 0 }}"
                                   style="font-size: 1.5rem;"></i>
                            </a>
                            <a href="javascript:void(0);" class="text-white" data-bs-toggle="tooltip" 
                               data-bs-placement="left" 
                               data-bs-original-title="{{ getPinnedStatus($contract->id) ? get_label('unpin', 'Unpin') : get_label('pin', 'Pin') }}">
                                <i class='bx {{ getPinnedStatus($contract->id) ? "bxs" : "bx" }}-pin pinned-icon' 
                                   data-id="{{ $contract->id }}" 
                                   data-pinned="{{ getPinnedStatus($contract->id) }}"
                                   style="font-size: 1.5rem;"></i>
                            </a>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex flex-column gap-2">
                            @php $actionButtons = getActionButtonsConfig($contract); @endphp
                            @foreach($actionButtons as $button)
                            <a href="{{ $button['url'] }}" 
                               class="btn btn-sm {{ $button['class'] }} d-flex align-items-center justify-content-center"
                               @if(isset($button['id'])) id="{{ $button['id'] }}" @endif
                               @if(isset($button['data']))
                                   @foreach($button['data'] as $key => $value)
                                   data-{{ $key }}="{{ $value }}"
                                   @endforeach
                               @endif
                               data-bs-toggle="tooltip" 
                               data-bs-placement="left" 
                               data-bs-original-title="{{ $button['tooltip'] }}"
                               style="width: 40px; height: 40px;">
                                <i class="{{ $button['icon'] }}"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Bar -->
            <div class="quick-stats bg-light p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <div class="stat-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 50px; height: 50px;">
                                <i class="bx bx-task bx-md"></i>
                            </div>
                            <h4 class="mb-1">{{ $contract->tasks_count ?? 0 }}</h4>
                            <small class="text-muted">{{ get_label('total_tasks', 'Total Tasks') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <div class="stat-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 50px; height: 50px;">
                                <i class="bx bx-check-circle bx-md"></i>
                            </div>
                            <h4 class="mb-1">{{ $contract->completed_tasks_count ?? 0 }}</h4>
                            <small class="text-muted">{{ get_label('completed_tasks', 'Completed Tasks') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <div class="stat-icon bg-{{ getFinancialHealthColor($contract->progress_percentage) }} text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 50px; height: 50px;">
                                <i class="bx bx-line-chart bx-md"></i>
                            </div>
                            <h4 class="mb-1">{{ $contract->progress_percentage }}%</h4>
                            <small class="text-muted">{{ get_label('progress', 'Progress') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            @php 
                                $health = getContractHealthStatus($contract->progress_percentage, $contract->days_until_deadline ?? 30);
                            @endphp
                            <div class="stat-icon bg-{{ $health['color'] }} text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 50px; height: 50px;">
                                <i class="bx {{ $health['icon'] }} bx-md"></i>
                            </div>
                            <h4 class="mb-1 text-capitalize">{{ $health['status'] }}</h4>
                            <small class="text-muted">{{ get_label('health_status', 'Health Status') }}</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Progress Section -->
            <div class="progress-section p-4 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ get_label('project_progress', 'Project Progress') }}</h5>
                    <span class="badge bg-{{ getProgressColor($contract->progress_percentage) }} fs-6">
                        {{ $contract->progress_percentage }}% {{ get_label('complete', 'Complete') }}
                    </span>
                </div>
                <div class="progress" style="height: 12px;">
                    <div class="progress-bar bg-{{ getProgressColor($contract->progress_percentage) }}" 
                         role="progressbar" 
                         style="width: {{ $contract->progress_percentage }}%;" 
                         aria-valuenow="{{ $contract->progress_percentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2 text-muted small">
                    <span>{{ get_label('start_date', 'Start Date') }}: {{ format_date($contract->start_date) }}</span>
                    <span>{{ get_label('end_date', 'End Date') }}: {{ format_date($contract->end_date) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>