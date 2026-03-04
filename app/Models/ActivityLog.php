<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'workspace_id',
        'user_id',
        'actor_id',
        'actor_type',
        'type_id',
        'type',
        'parent_type_id',
        'parent_type',
        'activity',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'message',
        'metadata'
    ];
    
    protected $casts = [
        'metadata' => 'array',
    ];
    
    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Scope to filter by action type
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
    
    // Scope to filter by entity type
    public function scopeByEntityType($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }
    
    // Scope to filter by entity ID
    public function scopeByEntityId($query, $entityId)
    {
        return $query->where('entity_id', $entityId);
    }
    
    // Scope to filter by user ID
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}