<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimatesInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'client_id',
        'contract_id',  // Adding contract_id to the fillable array
        'name',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'phone',
        'type',
        'status',
        'note',
        'personal_note',
        'from_date',
        'to_date',
        'total',
        'tax_amount',
        'final_total',
        'created_by'
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'estimates_invoice_item')
            ->withPivot('qty', 'unit_id', 'rate', 'tax_id', 'amount');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    
    // Enhanced relationship to get the project through the contract
    public function project()
    {
        return $this->hasOneThrough(Project::class, Contract::class, 'id', 'id', 'contract_id', 'project_id');
    }
    
    // Enhanced relationship to get the clients through the contract
    public function contractClients()
    {
        $clients = collect();
        if ($this->contract && $this->contract->client) {
            $clients->push($this->contract->client);
        } elseif ($this->client) {
            $clients->push($this->client);
        }
        return $clients;
    }
}