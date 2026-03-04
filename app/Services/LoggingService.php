<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LaravelLog;
use Illuminate\Support\Facades\Request;

class LoggingService
{
    /**
     * Log an activity with different log levels
     *
     * @param string $action The action being performed
     * @param string $entityType The type of entity being acted upon
     * @param mixed $entityId The ID of the entity
     * @param string $description Description of the action
     * @param array $metadata Additional metadata to store
     * @param string $level Log level (info, warning, error, debug)
     * @return ActivityLog
     */
    public function logActivity(string $action, string $entityType, $entityId, string $description, array $metadata = [], string $level = 'info'): ActivityLog
    {
        // Log to both database and file
        $this->logToFile($level, $description, $metadata, 'activity');
        
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'workspace_id' => getWorkspaceId(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'metadata' => array_merge($metadata, [
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'timestamp' => now()->toISOString(),
            ]),
        ]);
    }

    /**
     * Log a system event
     *
     * @param string $event The system event
     * @param string $description Description of the event
     * @param array $metadata Additional metadata
     * @return ActivityLog
     */
    public function logSystemEvent(string $event, string $description, array $metadata = []): ActivityLog
    {
        $this->logToFile('info', "System Event: {$event}", $metadata, 'activity');
        
        return ActivityLog::create([
            'user_id' => null, // System event, no user
            'workspace_id' => null, // Global system event
            'action' => $event,
            'entity_type' => 'system',
            'entity_id' => null,
            'description' => $description,
            'metadata' => array_merge($metadata, [
                'event_type' => 'system',
                'timestamp' => now()->toISOString(),
            ]),
        ]);
    }

    /**
     * Log an error
     *
     * @param string $context Context where the error occurred
     * @param string $message Error message
     * @param \Exception|null $exception Exception object if available
     * @param array $metadata Additional metadata
     * @return ActivityLog
     */
    public function logError(string $context, string $message, \Exception $exception = null, array $metadata = []): ActivityLog
    {
        $errorDetails = [
            'context' => $context,
            'message' => $message,
        ];

        if ($exception) {
            $errorDetails['exception_class'] = get_class($exception);
            $errorDetails['exception_message'] = $exception->getMessage();
            $errorDetails['exception_code'] = $exception->getCode();
            $errorDetails['file'] = $exception->getFile();
            $errorDetails['line'] = $exception->getLine();
            $errorDetails['trace'] = $exception->getTraceAsString();
        }

        $this->logToFile('error', $message, array_merge($metadata, $errorDetails), 'error');

        return ActivityLog::create([
            'user_id' => Auth::id(),
            'workspace_id' => getWorkspaceId(),
            'action' => 'error_occurred',
            'entity_type' => 'system_error',
            'entity_id' => null,
            'description' => $message,
            'metadata' => array_merge($metadata, $errorDetails, [
                'error_context' => $context,
                'severity' => 'high',
                'timestamp' => now()->toISOString(),
            ]),
        ]);
    }

    /**
     * Log a security event
     *
     * @param string $eventType Type of security event
     * @param string $description Description of the event
     * @param array $metadata Additional metadata
     * @return ActivityLog
     */
    public function logSecurityEvent(string $eventType, string $description, array $metadata = []): ActivityLog
    {
        $this->logToFile('warning', "Security Event: {$eventType}", $metadata, 'security');
        
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'workspace_id' => getWorkspaceId(),
            'action' => $eventType,
            'entity_type' => 'security',
            'entity_id' => null,
            'description' => $description,
            'metadata' => array_merge($metadata, [
                'security_event' => true,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'timestamp' => now()->toISOString(),
            ]),
        ]);
    }

    /**
     * Log an audit trail event
     *
     * @param string $action The audit action
     * @param string $entityType Entity type
     * @param mixed $entityId Entity ID
     * @param array $oldValues Old values before change
     * @param array $newValues New values after change
     * @param array $metadata Additional metadata
     * @return ActivityLog
     */
    public function logAuditTrail(string $action, string $entityType, $entityId, array $oldValues = [], array $newValues = [], array $metadata = []): ActivityLog
    {
        $auditDescription = $this->generateAuditDescription($action, $entityType, $entityId, $oldValues, $newValues);
        
        $this->logToFile('info', "Audit Trail: {$auditDescription}", $metadata, 'audit');
        
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'workspace_id' => getWorkspaceId(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $auditDescription,
            'metadata' => array_merge($metadata, [
                'audit_trail' => true,
                'changes' => [
                    'old_values' => $oldValues,
                    'new_values' => $newValues,
                ],
                'timestamp' => now()->toISOString(),
            ]),
        ]);
    }

    /**
     * Generate audit description based on changes
     *
     * @param string $action
     * @param string $entityType
     * @param mixed $entityId
     * @param array $oldValues
     * @param array $newValues
     * @return string
     */
    private function generateAuditDescription(string $action, string $entityType, $entityId, array $oldValues, array $newValues): string
    {
        $entityTypeName = $this->formatEntityName($entityType);
        
        switch ($action) {
            case 'created':
                return "Created new {$entityTypeName}";
                
            case 'updated':
                if (!empty($oldValues) && !empty($newValues)) {
                    $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
                    if (!empty($changedFields)) {
                        return "Updated {$entityTypeName} (ID: {$entityId}): " . implode(', ', $changedFields) . " fields changed";
                    }
                }
                return "Updated {$entityTypeName} (ID: {$entityId})";
                
            case 'deleted':
                return "Deleted {$entityTypeName} (ID: {$entityId})";
                
            default:
                return "{$action} {$entityTypeName} (ID: {$entityId})";
        }
    }

    /**
     * Format entity name for display
     *
     * @param string $entityType
     * @return string
     */
    private function formatEntityName(string $entityType): string
    {
        return ucfirst(str_replace('_', ' ', $entityType));
    }

    /**
     * Log to Laravel's file logging system
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @param string $channel
     */
    private function logToFile(string $level, string $message, array $context = [], string $channel = 'single'): void
    {
        LaravelLog::channel($channel)->$level($message, $context);
    }

    /**
     * Get activity logs with filtering options
     *
     * @param array $filters
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getActivityLogs(array $filters = [], int $limit = 50)
    {
        $query = ActivityLog::orderBy('created_at', 'desc');

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['entity_type'])) {
            $query->where('entity_type', $filters['entity_type']);
        }

        if (isset($filters['entity_id'])) {
            $query->where('entity_id', $filters['entity_id']);
        }

        if (isset($filters['workspace_id'])) {
            $query->where('workspace_id', $filters['workspace_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('action', 'LIKE', "%{$search}%")
                  ->orWhereJsonContains('metadata', $search);
            });
        }

        return $query->limit($limit)->get();
    }

    /**
     * Clean old logs based on retention policy
     *
     * @param int $days Number of days to retain logs
     * @return int Number of deleted records
     */
    public function cleanOldLogs(int $days = 90): int
    {
        $cutoffDate = now()->subDays($days);
        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();
        
        $this->logSystemEvent(
            'log_cleanup',
            "Cleaned up {$deletedCount} old activity logs older than {$days} days",
            ['retention_days' => $days, 'cutoff_date' => $cutoffDate->toISOString()]
        );
        
        return $deletedCount;
    }
}