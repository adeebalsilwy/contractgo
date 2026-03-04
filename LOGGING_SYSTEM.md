# Professional Logging System Documentation

## Overview

The Professional Logging System is a comprehensive solution for tracking, monitoring, and auditing all activities within the Laravel Contract Management System. This system provides structured logging capabilities with different log levels, audit trails, security monitoring, and performance tracking.

## Architecture

The logging system consists of several components:

1. **ActivityLog Model**: Primary database model for storing log entries
2. **LoggingService**: Centralized service for all logging operations
3. **Helper Functions**: Convenient functions for easy logging access
4. **File Logging**: Integration with Laravel's file-based logging
5. **Configuration**: Flexible configuration for different environments

## Components

### 1. ActivityLog Model

The `ActivityLog` model extends Laravel's Eloquent model and provides the following fields:

- `id`: Primary key
- `user_id`: ID of the user who performed the action
- `workspace_id`: ID of the workspace where the action occurred
- `action`: Type of action performed
- `entity_type`: Type of entity involved
- `entity_id`: ID of the specific entity
- `description`: Human-readable description of the action
- `metadata`: JSON field for additional structured data
- `created_at`: Timestamp of when the log entry was created

### 2. LoggingService

The `LoggingService` provides centralized methods for different types of logging:

#### Activity Logging
```php
logActivity(string $action, string $entityType, $entityId, string $description, array $metadata = [], string $level = 'info')
```

#### Error Logging
```php
logError(string $context, string $message, \Exception $exception = null, array $metadata = [])
```

#### Security Event Logging
```php
logSecurityEvent(string $eventType, string $description, array $metadata = [])
```

#### Audit Trail Logging
```php
logAuditTrail(string $action, string $entityType, $entityId, array $oldValues = [], array $newValues = [], array $metadata = [])
```

### 3. Helper Functions

Convenient helper functions are available throughout the application:

```php
// Log an activity
logActivity($action, $entityType, $entityId, $description, $metadata, $level);

// Log an error
logError($context, $message, $exception, $metadata);

// Log a security event
logSecurityEvent($eventType, $description, $metadata);

// Log an audit trail
logAuditTrail($action, $entityType, $entityId, $oldValues, $newValues, $metadata);

// Get activity logs
getActivityLogs($filters, $limit);
```

## Log Categories

The system supports different categories of logs:

### Activity Logs
Track user actions and system events:
- User logins and logouts
- Entity creation, updates, and deletions
- Form submissions
- Report generations
- File uploads/downloads

### Security Logs
Monitor security-related events:
- Failed login attempts
- Unauthorized access attempts
- Password resets
- Permission changes
- Session management events

### Audit Trail Logs
Track changes to important data:
- Before and after values of changed fields
- Who made the change and when
- Context of the change
- Impact assessment

### Error Logs
Capture system errors and exceptions:
- Application errors
- Database errors
- External API errors
- Validation errors

## Configuration

The logging system is configured in `config/logging.php` with multiple channels:

- `activity`: Daily rotating logs for general activities
- `security`: Long-term storage for security events (90 days)
- `audit`: Extended retention for audit trails (180 days)
- `error`: Dedicated error logs (90 days)
- `performance`: Performance monitoring logs (30 days)

## Usage Examples

### Basic Activity Logging
```php
logActivity(
    'contract_created', 
    'contract', 
    $contract->id, 
    'New contract created successfully',
    [
        'user_id' => auth()->id(),
        'contract_value' => $contract->value,
        'client_id' => $contract->client_id
    ]
);
```

### Error Logging with Exception
```php
try {
    // Some operation that might fail
    $result = $someService->process($data);
} catch (\Exception $e) {
    logError(
        'Contract Processing', 
        'Failed to process contract data', 
        $e,
        [
            'contract_id' => $contract->id,
            'user_id' => auth()->id(),
            'input_data' => $data
        ]
    );
    throw $e; // Re-throw if needed
}
```

### Audit Trail with Change Tracking
```php
// Before updating
$oldValues = [
    'status' => $contract->status,
    'value' => $contract->value
];

// Perform update
$contract->update($newData);

// After updating
$newValues = [
    'status' => $contract->fresh()->status,
    'value' => $contract->fresh()->value
];

logAuditTrail(
    'updated',
    'contract',
    $contract->id,
    $oldValues,
    $newValues,
    [
        'user_id' => auth()->id(),
        'field_count' => count($newData)
    ]
);
```

### Security Event Logging
```php
logSecurityEvent(
    'failed_login_attempt',
    'Multiple failed login attempts detected',
    [
        'username' => $username,
        'ip_address' => request()->ip(),
        'attempt_count' => $attempts,
        'timestamp' => now()->toISOString()
    ]
);
```

## Filtering and Retrieval

Activity logs can be filtered by various criteria:

```php
// Filter by user
$logs = getActivityLogs(['user_id' => auth()->id()], 50);

// Filter by action type
$logs = getActivityLogs(['action' => 'contract_created'], 100);

// Filter by entity type
$logs = getActivityLogs(['entity_type' => 'contract'], 50);

// Date range filtering
$logs = getActivityLogs([
    'date_from' => '2023-01-01',
    'date_to' => '2023-12-31'
], 200);

// Search within logs
$logs = getActivityLogs([
    'search' => 'contract',
    'entity_type' => 'contract'
], 100);
```

## Performance Considerations

- Logs are written asynchronously when possible
- Database indexes are created on frequently queried fields
- Old logs are cleaned automatically based on retention policy
- Metadata is stored efficiently as JSON
- Bulk operations are optimized

## Security Considerations

- Sensitive data is not logged
- IP addresses and user agents are captured for security analysis
- Personal information is masked when appropriate
- Log access is restricted based on user permissions
- Logs are tamper-evident where possible

## Best Practices

1. **Be Descriptive**: Use clear, informative descriptions
2. **Include Context**: Add relevant metadata for troubleshooting
3. **Use Appropriate Levels**: Choose the right log level for each situation
4. **Respect Privacy**: Don't log sensitive personal information
5. **Monitor Performance**: Be mindful of logging impact on performance
6. **Structure Data**: Use consistent metadata structures
7. **Retention Planning**: Plan for log retention and cleanup

## Maintenance

### Log Cleanup
Old logs are automatically cleaned based on retention periods:
- Activity logs: 30 days
- Security logs: 90 days
- Audit trails: 180 days
- Error logs: 90 days

### Manual Cleanup
```php
// Clean logs older than 60 days
$deletedCount = app('logging')->cleanOldLogs(60);
```

## Troubleshooting

### Common Issues

1. **Database Connection Errors**: Ensure database connection is stable
2. **Disk Space**: Monitor log file sizes and retention
3. **Performance**: Too much logging can impact performance
4. **Permissions**: Ensure proper file permissions for log directories

### Monitoring

Monitor the following aspects:
- Log file sizes
- Database performance
- Disk space usage
- Error rates
- Security events frequency

## Integration Points

The logging system integrates with:
- Authentication system
- Authorization system
- Error handling
- API endpoints
- Background jobs
- Email notifications
- System health checks

## Extensibility

The system is designed to be extensible:
- Custom log types can be added
- Additional metadata fields are supported
- New log channels can be configured
- Custom formatters can be implemented
- External logging services can be integrated

## Conclusion

This professional logging system provides comprehensive monitoring and auditing capabilities for the contract management system. It ensures transparency, accountability, and security while maintaining good performance and usability.