@extends('layouts.app')

@section('title', 'System Cache Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">System Cache Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Cache Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white">Cache Status</h5>
                            <p class="card-text" id="cacheStatus">Checking...</p>
                        </div>
                        <i class="fas fa-database fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white">Environment</h5>
                            <p class="card-text">{{ strtoupper(config('app.env')) }}</p>
                        </div>
                        <i class="fas fa-server fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white">Routes Cached</h5>
                            <p class="card-text" id="routesStatus">Checking...</p>
                        </div>
                        <i class="fas fa-route fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white">Config Cached</h5>
                            <p class="card-text" id="configStatus">Checking...</p>
                        </div>
                        <i class="fas fa-cog fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Quick Cache Management Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-primary btn-lg btn-block" id="refreshAllBtn">
                                <i class="fas fa-sync-alt"></i> Refresh All Cache
                                <div class="small">Clear all caches and refresh application</div>
                            </button>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-success btn-lg btn-block" id="restartServerBtn">
                                <i class="fas fa-power-off"></i> Restart Server
                                <div class="small">Clear cache and optimize application</div>
                            </button>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-danger btn-lg btn-block" id="completeResetBtn">
                                <i class="fas fa-bomb"></i> Complete System Reset
                                <div class="small">Full reset including all files and cache</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Cache Management -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">Detailed Cache Management</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-primary btn-block cache-action-btn" data-type="cache">
                                <i class="fas fa-trash"></i> Clear Application Cache
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-success btn-block cache-action-btn" data-type="route">
                                <i class="fas fa-route"></i> Clear Route Cache
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-info btn-block cache-action-btn" data-type="config">
                                <i class="fas fa-cog"></i> Clear Config Cache
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-warning btn-block cache-action-btn" data-type="view">
                                <i class="fas fa-file-code"></i> Clear View Cache
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-danger btn-block cache-action-btn" data-type="event">
                                <i class="fas fa-calendar"></i> Clear Event Cache
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-outline-dark btn-block cache-action-btn" data-type="compiled">
                                <i class="fas fa-code"></i> Clear Compiled Files
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <div id="systemInfo">
                        <p class="text-muted">Loading system information...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Recent Cache Operations</h5>
                </div>
                <div class="card-body">
                    <div id="activityLog">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2">Loading recent activities...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage"></p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    This action will temporarily affect system performance.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the cache management system
    initializeCacheManagement();
    
    // Load system status
    loadSystemStatus();
    
    // Load system information
    loadSystemInfo();
    
    // Load activity log
    loadActivityLog();
});

function initializeCacheManagement() {
    // Quick action buttons
    document.getElementById('refreshAllBtn').addEventListener('click', function() {
        showConfirmation('Are you sure you want to refresh all cache? This will clear all cached data.', 'refreshAll');
    });
    
    document.getElementById('restartServerBtn').addEventListener('click', function() {
        showConfirmation('Are you sure you want to restart the server? This will clear cache and optimize the application.', 'restartServer');
    });
    
    document.getElementById('completeResetBtn').addEventListener('click', function() {
        showConfirmation('WARNING: This will perform a complete system reset. Are you absolutely sure?', 'completeReset');
    });
    
    // Individual cache action buttons
    document.querySelectorAll('.cache-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            const cacheType = this.getAttribute('data-type');
            const actionText = getActionText(cacheType);
            showConfirmation(`Are you sure you want to clear ${actionText}?`, `clearCache_${cacheType}`);
        });
    });
    
    // Confirm action button
    document.getElementById('confirmActionBtn').addEventListener('click', function() {
        const action = this.getAttribute('data-action');
        executeAction(action);
        $('#confirmationModal').modal('hide');
    });
}

function showConfirmation(message, action) {
    document.getElementById('confirmationMessage').textContent = message;
    document.getElementById('confirmActionBtn').setAttribute('data-action', action);
    $('#confirmationModal').modal('show');
}

function executeAction(action) {
    showLoading();
    
    let url, method = 'POST';
    
    switch(action) {
        case 'refreshAll':
            url = '{{ route("cache.refresh.all") }}';
            break;
        case 'restartServer':
            url = '{{ route("system.restart.server") }}';
            break;
        case 'completeReset':
            url = '{{ route("system.reset.complete") }}';
            method = 'GET';
            break;
        default:
            if (action.startsWith('clearCache_')) {
                const cacheType = action.split('_')[1];
                url = `{{ url("admin/cache/clear") }}/${cacheType}`;
            }
            break;
    }
    
    if (url) {
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            showNotification(data.message || 'Operation completed', data.status === 'success' ? 'success' : 'error');
            
            if (data.status === 'success') {
                // Reload system status after successful operation
                setTimeout(() => {
                    loadSystemStatus();
                    loadActivityLog();
                }, 1000);
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Operation failed: ' + error.message, 'error');
        });
    }
}

function loadSystemStatus() {
    fetch('{{ route("cache.status") }}', {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const cacheData = data.data;
            
            // Update status cards
            document.getElementById('cacheStatus').textContent = cacheData.cache_enabled ? 'Enabled' : 'Disabled';
            document.getElementById('routesStatus').textContent = cacheData.route_cached ? 'Yes' : 'No';
            document.getElementById('configStatus').textContent = cacheData.config_cached ? 'Yes' : 'No';
            
            // Update card backgrounds based on status
            updateStatusCard('cacheStatus', cacheData.cache_enabled);
            updateStatusCard('routesStatus', cacheData.route_cached);
            updateStatusCard('configStatus', cacheData.config_cached);
        }
    })
    .catch(error => {
        console.error('Failed to load system status:', error);
    });
}

function loadSystemInfo() {
    fetch('{{ route("cache.status") }}', {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const infoHtml = `
                <ul class="list-unstyled">
                    <li><strong>Cache Driver:</strong> ${data.data.cache_driver}</li>
                    <li><strong>Storage Path:</strong> ${data.data.storage_path}</li>
                    <li><strong>Cache Path:</strong> ${data.data.cache_path}</li>
                    <li><strong>View Path:</strong> ${data.data.view_path}</li>
                    <li><strong>Session Path:</strong> ${data.data.session_path}</li>
                </ul>
            `;
            document.getElementById('systemInfo').innerHTML = infoHtml;
        }
    })
    .catch(error => {
        document.getElementById('systemInfo').innerHTML = '<p class="text-danger">Failed to load system information</p>';
    });
}

function loadActivityLog() {
    // Simulate activity log (in real implementation, this would fetch from a log file or database)
    const activities = [
        { time: '2 minutes ago', action: 'Cache cleared', user: 'Admin' },
        { time: '15 minutes ago', action: 'Routes optimized', user: 'System' },
        { time: '1 hour ago', action: 'Configuration cached', user: 'Admin' }
    ];
    
    const logHtml = activities.map(activity => `
        <div class="activity-item mb-3 pb-3 border-bottom">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${activity.action}</strong>
                    <div class="small text-muted">by ${activity.user}</div>
                </div>
                <div class="small text-muted">${activity.time}</div>
            </div>
        </div>
    `).join('');
    
    document.getElementById('activityLog').innerHTML = logHtml || '<p class="text-muted text-center">No recent activities</p>';
}

function updateStatusCard(elementId, status) {
    const element = document.getElementById(elementId).closest('.card');
    if (status) {
        element.className = element.className.replace(/bg-\w+/, 'bg-success');
    } else {
        element.className = element.className.replace(/bg-\w+/, 'bg-danger');
    }
}

function getActionText(cacheType) {
    const actions = {
        'cache': 'application cache',
        'route': 'route cache',
        'config': 'configuration cache',
        'view': 'view cache',
        'event': 'event cache',
        'compiled': 'compiled files'
    };
    return actions[cacheType] || cacheType;
}

function showLoading() {
    // Show loading indicator
    document.body.style.cursor = 'wait';
}

function hideLoading() {
    // Hide loading indicator
    document.body.style.cursor = 'default';
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endsection