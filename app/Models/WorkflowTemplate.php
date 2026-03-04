<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'type',
        'category',
        'content',
        'variables',
        'workflow_steps',
        'status',
        'workspace_id',
        'created_by',
        'is_default',
        'version',
        'preview_image'
    ];
    
    protected $casts = [
        'variables' => 'array',
        'workflow_steps' => 'array',
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
    
    public function contractTemplates()
    {
        return $this->hasMany(ContractTemplate::class);
    }
    
    public function extractTemplates()
    {
        return $this->hasMany(ExtractTemplate::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
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
    
    public function getWorkflowSteps()
    {
        return $this->workflow_steps ?? [];
    }
    
    public function isEditable()
    {
        return $this->status === 'draft' || !$this->is_default;
    }
    
    public function duplicate($newName = null)
    {
        $newTemplate = $this->replicate();
        $newTemplate->name = $newName ?? $this->name . ' (Copy)';
        $newTemplate->is_default = false;
        $newTemplate->status = 'draft';
        $newTemplate->version = '1.0';
        $newTemplate->save();
        
        return $newTemplate;
    }
}
