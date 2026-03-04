<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateNotification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'event_type',
        'notification_type',
        'subject_template',
        'content_template',
        'variables',
        'is_active',
        'workspace_id',
        'created_by',
        'is_default',
        'version'
    ];
    
    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }
    
    public function scopeByNotificationType($query, $notificationType)
    {
        return $query->where('notification_type', $notificationType);
    }
    
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
    
    public function scopeByWorkspace($query, $workspaceId)
    {
        return $query->where('workspace_id', $workspaceId);
    }
    
    // Helper methods
    public function getAvailableVariables()
    {
        return $this->variables ?? [];
    }
    
    public function isEditable()
    {
        return !$this->is_default;
    }
    
    public function duplicate($newName = null)
    {
        $newTemplate = $this->replicate();
        $newTemplate->name = $newName ?? $this->name . ' (Copy)';
        $newTemplate->is_default = false;
        $newTemplate->is_active = true;
        $newTemplate->version = '1.0';
        $newTemplate->save();
        
        return $newTemplate;
    }
}
