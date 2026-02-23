<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'unit_id',
        'price',
        'description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationship with Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relationship with Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Relationship with Task (for tasks that use this pricing)
    public function tasks()
    {
        return $this->hasMany(Task::class, 'item_pricing_id');
    }
}