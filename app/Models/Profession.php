<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'workspace_id'
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}