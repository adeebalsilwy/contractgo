<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OnyxProService
{
    protected $baseUrl;
    protected $apiKey;
    protected $enabled;
    
    public function __construct()
    {
        $this->baseUrl = config('services.onyx_pro.base_url', env('ONYX_PRO_BASE_URL', 'http://localhost:8080'));
        $this->apiKey = config('services.onyx_pro.api_key', env('ONYX_PRO_API_KEY', ''));
        $this->enabled = config('services.onyx_pro.enabled', env('ONYX_PRO_ENABLED', false));
    }
    
    /**
     * Check if Onyx Pro integration is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->apiKey);
    }
    
    /**
     * Create journal entry in Onyx Pro
     * 
     * @param array $data Journal entry data
     * @return array Success status and entry number or error
     */
    public function createJournalEntry(array $data)
    {
        if (!$this->isEnabled()) {
            Log::warning('Onyx Pro integration is not enabled');
            return [
                'success' => false,
                'error' => 'Onyx Pro integration is not enabled',
                'entry_number' => null
            ];
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post($this->baseUrl . '/api/journal-entries', $data);
            
            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'entry_number' => $responseData['entry_number'] ?? $responseData['data']['entry_number'] ?? null,
                    'data' => $responseData,
                    'message' => 'Journal entry created successfully in Onyx Pro'
                ];
            }
            
            $errorMessage = $response->json('message', 'Unknown error occurred');
            Log::error('Onyx Pro API Error: ' . $errorMessage, [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return [
                'success' => false,
                'error' => $errorMessage,
                'entry_number' => null
            ];
            
        } catch (Exception $e) {
            Log::error('Onyx Pro API Exception: ' . $e->getMessage(), [
                'url' => $this->baseUrl . '/api/journal-entries',
                'data' => $data
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to connect to Onyx Pro: ' . $e->getMessage(),
                'entry_number' => null
            ];
        }
    }
    
    /**
     * Verify journal entry exists in Onyx Pro
     * 
     * @param string $entryNumber Entry number to verify
     * @return bool Verification status
     */
    public function verifyEntry(string $entryNumber): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/api/journal-entries/' . $entryNumber);
            
            return $response->successful();
        } catch (Exception $e) {
            Log::error('Onyx Pro Verification Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get journal entry details from Onyx Pro
     * 
     * @param string $entryNumber Entry number
     * @return array|null Entry details or null if not found
     */
    public function getEntry(string $entryNumber): ?array
    {
        if (!$this->isEnabled()) {
            return null;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/api/journal-entries/' . $entryNumber);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        } catch (Exception $e) {
            Log::error('Onyx Pro Get Entry Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Sync local journal entry with Onyx Pro
     * 
     * @param \App\Models\JournalEntry $journalEntry Local journal entry
     * @return array Success status and sync result
     */
    public function syncJournalEntry($journalEntry)
    {
        $data = [
            'entry_date' => $journalEntry->entry_date->format('Y-m-d'),
            'reference_number' => $journalEntry->reference_number,
            'description' => $journalEntry->description,
            'debit_amount' => (float) $journalEntry->debit_amount,
            'credit_amount' => (float) $journalEntry->credit_amount,
            'account_code' => $journalEntry->account_code,
            'account_name' => $journalEntry->account_name,
            'entry_type' => $journalEntry->entry_type,
            'contract_reference' => $journalEntry->contract ? $journalEntry->contract->title : null,
            'invoice_reference' => $journalEntry->invoice ? $journalEntry->invoice->invoice_number : null,
            'external_id' => $journalEntry->id,
            'workspace_id' => $journalEntry->workspace_id,
        ];
        
        $result = $this->createJournalEntry($data);
        
        if ($result['success']) {
            // Update local journal entry with Onyx Pro entry number
            if ($result['entry_number']) {
                $journalEntry->update([
                    'entry_number' => $result['entry_number'],
                    'status' => 'posted',
                    'posted_at' => now(),
                    'posted_by' => auth()->id(),
                    'integration_data' => array_merge(
                        $journalEntry->integration_data ?? [],
                        ['onyx_pro_synced' => true, 'onyx_pro_entry_number' => $result['entry_number']]
                    ),
                ]);
                
                // Update contract's journal entry tracking
                if ($journalEntry->contract) {
                    $journalEntry->contract->update([
                        'journal_entry_number' => $result['entry_number'],
                        'journal_entry_date' => $journalEntry->entry_date,
                        'financial_status' => 'accounting_integrated',
                    ]);
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Test connection to Onyx Pro
     * 
     * @return array Connection test result
     */
    public function testConnection()
    {
        if (!$this->isEnabled()) {
            return [
                'success' => false,
                'message' => 'Onyx Pro integration is not enabled'
            ];
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/api/health');
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connected to Onyx Pro successfully',
                    'status' => $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Connection failed with status: ' . $response->status()
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
