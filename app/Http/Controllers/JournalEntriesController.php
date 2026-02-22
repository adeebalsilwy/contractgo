<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\EstimatesInvoice;
use App\Models\JournalEntry;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class JournalEntriesController extends Controller
{
    protected $workspace;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->workspace = Workspace::find(getWorkspaceId());
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    /**
     * Display a listing of journal entries.
     */
    public function index()
    {
        $journalEntries = isAdminOrHasAllDataAccess() 
            ? $this->workspace->journalEntries() 
            : $this->user->journalEntries();
        
        $journalEntries = $journalEntries->count();
        return view('journal-entries.index', ['journalEntries' => $journalEntries]);
    }

    /**
     * Show the form for creating a new journal entry.
     */
    public function create()
    {
        $contracts = isAdminOrHasAllDataAccess() 
            ? Contract::where('workspace_id', $this->workspace->id)->get()
            : Contract::where('workspace_id', $this->workspace->id)
                       ->where(function ($query) {
                           $query->where('created_by', 'u_' . $this->user->id)
                                 ->orWhere('client_id', $this->user->id);
                       })->get();
        
        $invoices = isAdminOrHasAllDataAccess() 
            ? EstimatesInvoice::where('workspace_id', $this->workspace->id)->get()
            : EstimatesInvoice::where('workspace_id', $this->workspace->id)
                             ->where(function ($query) {
                                 $query->where('created_by', 'u_' . $this->user->id)
                                       ->orWhere('client_id', $this->user->id);
                             })->get();

        return view('journal-entries.create', compact('contracts', 'invoices'));
    }

    /**
     * Store a newly created journal entry.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'contract_id' => 'nullable|exists:contracts,id',
                'invoice_id' => 'nullable|exists:estimates_invoices,id',
                'entry_number' => 'required|string|max:255',
                'entry_type' => 'required|in:journal,invoice,payment,receipt',
                'entry_date' => 'required|date',
                'reference_number' => 'nullable|string|max:255',
                'description' => 'required|string',
                'debit_amount' => 'required|numeric|min:0',
                'credit_amount' => 'required|numeric|min:0',
                'account_code' => 'required|string|max:50',
                'account_name' => 'required|string|max:255',
                'status' => 'required|in:pending,posted,reversed,cancelled',
            ]);

            $journalEntry = JournalEntry::create([
                'contract_id' => $request->contract_id,
                'invoice_id' => $request->invoice_id,
                'entry_number' => $request->entry_number,
                'entry_type' => $request->entry_type,
                'entry_date' => $request->entry_date,
                'reference_number' => $request->reference_number,
                'description' => $request->description,
                'debit_amount' => $request->debit_amount,
                'credit_amount' => $request->credit_amount,
                'account_code' => $request->account_code,
                'account_name' => $request->account_name,
                'created_by' => $this->user->id,
                'status' => $request->status,
                'integration_data' => ['onyx_pro_synced' => false],
            ]);

            // Update the contract's journal entry tracking
            if ($request->contract_id) {
                $contract = Contract::findOrFail($request->contract_id);
                $contract->update([
                    'journal_entry_number' => $request->entry_number,
                    'journal_entry_date' => $request->entry_date,
                ]);
            }

            Session::flash('message', 'Journal entry created successfully.');
            return response()->json(['error' => false, 'id' => $journalEntry->id, 'message' => 'Journal entry created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified journal entry.
     */
    public function show($id)
    {
        $journalEntry = JournalEntry::with(['contract', 'invoice', 'createdBy', 'postedBy'])->findOrFail($id);
        return view('journal-entries.show', compact('journalEntry'));
    }

    /**
     * Show the form for editing the specified journal entry.
     */
    public function edit($id)
    {
        $journalEntry = JournalEntry::findOrFail($id);
        $contracts = isAdminOrHasAllDataAccess() 
            ? Contract::where('workspace_id', $this->workspace->id)->get()
            : Contract::where('workspace_id', $this->workspace->id)
                       ->where(function ($query) {
                           $query->where('created_by', 'u_' . $this->user->id)
                                 ->orWhere('client_id', $this->user->id);
                       })->get();
        
        $invoices = isAdminOrHasAllDataAccess() 
            ? EstimatesInvoice::where('workspace_id', $this->workspace->id)->get()
            : EstimatesInvoice::where('workspace_id', $this->workspace->id)
                             ->where(function ($query) {
                                 $query->where('created_by', 'u_' . $this->user->id)
                                       ->orWhere('client_id', $this->user->id);
                             })->get();

        return view('journal-entries.edit', compact('journalEntry', 'contracts', 'invoices'));
    }

    /**
     * Update the specified journal entry.
     */
    public function update(Request $request, $id)
    {
        try {
            $journalEntry = JournalEntry::findOrFail($id);

            $request->validate([
                'contract_id' => 'nullable|exists:contracts,id',
                'invoice_id' => 'nullable|exists:estimates_invoices,id',
                'entry_number' => 'required|string|max:255',
                'entry_type' => 'required|in:journal,invoice,payment,receipt',
                'entry_date' => 'required|date',
                'reference_number' => 'nullable|string|max:255',
                'description' => 'required|string',
                'debit_amount' => 'required|numeric|min:0',
                'credit_amount' => 'required|numeric|min:0',
                'account_code' => 'required|string|max:50',
                'account_name' => 'required|string|max:255',
                'status' => 'required|in:pending,posted,reversed,cancelled',
            ]);

            $journalEntry->update([
                'contract_id' => $request->contract_id,
                'invoice_id' => $request->invoice_id,
                'entry_number' => $request->entry_number,
                'entry_type' => $request->entry_type,
                'entry_date' => $request->entry_date,
                'reference_number' => $request->reference_number,
                'description' => $request->description,
                'debit_amount' => $request->debit_amount,
                'credit_amount' => $request->credit_amount,
                'account_code' => $request->account_code,
                'account_name' => $request->account_name,
                'status' => $request->status,
            ]);

            // Update the contract's journal entry tracking if applicable
            if ($request->contract_id) {
                $contract = Contract::findOrFail($request->contract_id);
                $contract->update([
                    'journal_entry_number' => $request->entry_number,
                    'journal_entry_date' => $request->entry_date,
                ]);
            }

            Session::flash('message', 'Journal entry updated successfully.');
            return response()->json(['error' => false, 'id' => $journalEntry->id, 'message' => 'Journal entry updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified journal entry.
     */
    public function destroy($id)
    {
        try {
            $journalEntry = JournalEntry::findOrFail($id);
            $journalEntry->delete();
            
            return response()->json(['error' => false, 'message' => 'Journal entry deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * List journal entries with filtering and pagination.
     */
    public function list(Request $request)
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = request('status');
        $entry_type = request('entry_type');
        $contract_ids = request('contract_ids', []);
        $invoice_ids = request('invoice_ids', []);

        $journalEntries = JournalEntry::with(['contract', 'invoice', 'createdBy']);

        if (!isAdminOrHasAllDataAccess()) {
            // Limit access based on user permissions
            $journalEntries = $journalEntries->where('created_by', $this->user->id);
        }

        if (!empty($contract_ids)) {
            $journalEntries->whereIn('contract_id', $contract_ids);
        }

        if (!empty($invoice_ids)) {
            $journalEntries->whereIn('invoice_id', $invoice_ids);
        }

        if ($status) {
            $journalEntries->where('status', $status);
        }

        if ($entry_type) {
            $journalEntries->where('entry_type', $entry_type);
        }

        if ($search) {
            $journalEntries = $journalEntries->where(function ($query) use ($search) {
                $query->where('entry_number', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%')
                      ->orWhere('account_code', 'like', '%' . $search . '%')
                      ->orWhere('account_name', 'like', '%' . $search . '%');
            });
        }

        $total = $journalEntries->count();

        $journalEntries = $journalEntries->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($entry) {
                return [
                    'id' => $entry->id,
                    'entry_number' => $entry->entry_number,
                    'entry_type' => ucfirst($entry->entry_type),
                    'entry_date' => format_date($entry->entry_date),
                    'reference_number' => $entry->reference_number ?? 'N/A',
                    'description' => $entry->description,
                    'debit_amount' => format_currency($entry->debit_amount),
                    'credit_amount' => format_currency($entry->credit_amount),
                    'account_code' => $entry->account_code,
                    'account_name' => $entry->account_name,
                    'contract' => $entry->contract ? $entry->contract->title : 'N/A',
                    'invoice' => $entry->invoice ? $entry->invoice->name : 'N/A',
                    'status' => '<span class="badge bg-' . ($entry->status === 'posted' ? 'success' : ($entry->status === 'pending' ? 'warning' : 'secondary')) . '">' . ucfirst($entry->status) . '</span>',
                    'created_by' => $entry->createdBy ? $entry->createdBy->first_name . ' ' . $entry->createdBy->last_name : 'N/A',
                    'created_at' => format_date($entry->created_at, true),
                    'actions' => '
                        <a href="' . route('journal-entries.show', $entry->id) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>
                        <a href="' . route('journal-entries.edit', $entry->id) . '" class="text-warning" title="Edit"><i class="bx bx-edit"></i></a>
                        <button type="button" class="btn text-danger delete" data-id="' . $entry->id . '" data-type="journal-entries"><i class="bx bx-trash"></i></button>
                    '
                ];
            });

        return response()->json([
            "rows" => $journalEntries->items(),
            "total" => $total,
        ]);
    }

    /**
     * Post a journal entry to accounting system (simulate Onyx Pro integration).
     */
    public function postToAccounting($id)
    {
        try {
            $journalEntry = JournalEntry::findOrFail($id);
            
            // Check if user is authorized to post entries
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $journalEntry->contract->accountant_id) {
                abort(403, 'Unauthorized to post journal entry');
            }

            // Simulate posting to Onyx Pro
            $journalEntry->update([
                'status' => 'posted',
                'posted_at' => now(),
                'posted_by' => $this->user->id,
                'posting_notes' => 'Posted to Onyx Pro accounting system',
                'integration_data' => array_merge($journalEntry->integration_data ?? [], [
                    'onyx_pro_synced' => true,
                    'onyx_pro_reference' => 'ONYX-' . time(), // Simulated Onyx Pro reference
                    'sync_date' => now()->toISOString()
                ])
            ]);

            Session::flash('message', 'Journal entry posted to accounting system successfully.');
            return response()->json(['error' => false, 'message' => 'Journal entry posted to accounting system successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Sync with Onyx Pro accounting system.
     */
    public function syncWithOnyxPro()
    {
        try {
            // In a real implementation, this would connect to the Onyx Pro API
            // For now, we'll simulate the sync process
            
            $pendingEntries = JournalEntry::where('status', '!=', 'posted')
                                         ->whereJsonContains('integration_data->onyx_pro_synced', false)
                                         ->get();

            foreach ($pendingEntries as $entry) {
                // Simulate sync with Onyx Pro
                $entry->update([
                    'integration_data' => array_merge($entry->integration_data ?? [], [
                        'onyx_pro_synced' => true,
                        'onyx_pro_reference' => 'ONYX-' . time() . '-' . $entry->id,
                        'sync_date' => now()->toISOString()
                    ])
                ]);
            }

            Session::flash('message', count($pendingEntries) . ' journal entries synced with Onyx Pro successfully.');
            return response()->json(['error' => false, 'message' => count($pendingEntries) . ' journal entries synced with Onyx Pro successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Generate journal entries from approved contract quantities.
     */
    public function generateFromContract($contractId)
    {
        try {
            $contract = Contract::findOrFail($contractId);
            
            // Get approved quantities for this contract
            $approvedQuantities = \App\Models\ContractQuantity::where('contract_id', $contractId)
                                                              ->where('status', 'approved')
                                                              ->get();

            if ($approvedQuantities->isEmpty()) {
                return response()->json(['error' => true, 'message' => 'No approved quantities found for this contract.']);
            }

            $totalAmount = $approvedQuantities->sum('total_amount');

            // Create a journal entry for the contract
            $journalEntry = JournalEntry::create([
                'contract_id' => $contractId,
                'entry_number' => 'JE-' . date('Ym') . '-' . rand(1000, 9999),
                'entry_type' => 'journal',
                'entry_date' => now(),
                'description' => 'Revenue recognition for contract: ' . $contract->title,
                'debit_amount' => 0,
                'credit_amount' => $totalAmount,
                'account_code' => '4000', // Revenue account
                'account_name' => 'Sales Revenue',
                'created_by' => $this->user->id,
                'status' => 'pending',
                'integration_data' => ['onyx_pro_synced' => false],
            ]);

            // Also create debit entry for Accounts Receivable
            $journalEntryDebit = JournalEntry::create([
                'contract_id' => $contractId,
                'entry_number' => 'JE-' . date('Ym') . '-' . rand(1000, 9999),
                'entry_type' => 'journal',
                'entry_date' => now(),
                'description' => 'Accounts receivable for contract: ' . $contract->title,
                'debit_amount' => $totalAmount,
                'credit_amount' => 0,
                'account_code' => '1100', // Accounts Receivable
                'account_name' => 'Accounts Receivable',
                'created_by' => $this->user->id,
                'status' => 'pending',
                'integration_data' => ['onyx_pro_synced' => false],
            ]);

            Session::flash('message', 'Journal entries generated from contract successfully.');
            return response()->json(['error' => false, 'message' => 'Journal entries generated from contract successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
}