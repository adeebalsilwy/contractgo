<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtractTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'workflow_template_id',
        'pdf_template_id',
        'notification_template_id',
        'title_template',
        'description_template',
        'default_items',
        'numbering_pattern',
        'workflow_assignments',
        'status',
        'workspace_id',
        'created_by',
        'is_default',
        'version',
        'preview_image'
    ];
    
    protected $casts = [
        'default_items' => 'array',
        'workflow_assignments' => 'array',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Relationships
    public function workflowTemplate()
    {
        return $this->belongsTo(WorkflowTemplate::class);
    }
    
    public function pdfTemplate()
    {
        return $this->belongsTo(Template::class, 'pdf_template_id');
    }
    
    public function notificationTemplate()
    {
        return $this->belongsTo(Template::class, 'notification_template_id');
    }
    
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
        return $query->where('status', 'active');
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
    public function getDefaultItems()
    {
        return $this->default_items ?? [];
    }
    
    public function getWorkflowAssignments()
    {
        return $this->workflow_assignments ?? [];
    }
    
    public function generateNumber($year = null)
    {
        $year = $year ?? date('Y');
        $sequence = $this->getNextSequence($year);
        return str_replace(['{YEAR}', '{SEQUENCE}'], [$year, str_pad($sequence, 3, '0', STR_PAD_LEFT)], $this->numbering_pattern);
    }
    
    private function getNextSequence($year)
    {
        // This would typically query the database for the next sequence number
        // For now, returning a simple increment
        return 1;
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
