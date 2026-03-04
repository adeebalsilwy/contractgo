<?php

namespace App\Http\Controllers;

use App\Models\ContractObligation;
use App\Models\Contract;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class ContractObligationsController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $obligations = ContractObligation::with(['contract', 'party', 'assignedTo'])
            ->whereHas('contract', function($query) {
                $query->where('workspace_id', $this->workspace->id);
            });

        // Apply filters if provided
        if ($request->has('contract_id') && $request->contract_id) {
            $obligations->where('contract_id', $request->contract_id);
        }

        if ($request->has('status') && $request->status) {
            $obligations->where('status', $request->status);
        }

        if ($request->has('obligation_type') && $request->obligation_type) {
            $obligations->where('obligation_type', $request->obligation_type);
        }

        if ($request->has('party_type') && $request->party_type) {
            $obligations->where('party_type', $request->party_type);
        }

        $obligations = $obligations->orderBy('created_at', 'desc')->get();

        $contracts = Contract::where('workspace_id', $this->workspace->id)->get();
        $users = $this->workspace->users()->get();

        return view('contract-obligations.index', compact('obligations', 'contracts', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $contracts = Contract::where('workspace_id', $this->workspace->id)->get();
        $users = $this->workspace->users()->get();

        // Pre-fill contract if passed in request
        $contract = null;
        if ($request->has('contract_id') && $request->contract_id) {
            $contract = Contract::find($request->contract_id);
        }

        return view('contract-obligations.create', compact('contracts', 'users', 'contract'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'contract_id' => 'required|exists:contracts,id',
                'party_id' => 'required|exists:users,id',
                'party_type' => 'required|in:client,contractor,consultant,supervisor,other',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'obligation_type' => 'required|in:payment,delivery,performance,compliance,reporting,other',
                'priority' => 'required|in:low,medium,high,critical',
                'due_date' => 'nullable|date',
                'assigned_to' => 'nullable|exists:users,id',
                'notes' => 'nullable|string',
                'supporting_documents' => 'nullable|array',
                'supporting_documents.*' => 'file|mimes:pdf,jpg,png,jpeg|max:10240', // Max 10MB per file
            ]);

            // Handle document uploads if any
            $documentPaths = [];
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $document) {
                    $path = $document->store('contract-obligations/documents', 'public');
                    $documentPaths[] = $path;
                }
            }

            $obligation = ContractObligation::create([
                'contract_id' => $request->contract_id,
                'party_id' => $request->party_id,
                'party_type' => $request->party_type,
                'title' => $request->title,
                'description' => $request->description,
                'obligation_type' => $request->obligation_type,
                'priority' => $request->priority,
                'status' => 'pending', // Default to pending
                'due_date' => $request->due_date,
                'assigned_to' => $request->assigned_to,
                'notes' => $request->notes,
                'supporting_documents' => $documentPaths,
            ]);

            Session::flash('message', 'Contract obligation created successfully.');
            return response()->json(['error' => false, 'id' => $obligation->id, 'message' => 'Contract obligation created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $obligation = ContractObligation::with([
            'contract', 
            'party', 
            'assignedTo', 
            'complianceCheckedBy',
            'contract.client',
            'contract.project',
            'contract.obligations',
            'contract.quantities',
            'contract.amendments',
            'contract.approvals',
            'contract.estimates',
            'contract.journalEntries'
        ])->findOrFail($id);
        
        return view('contract-obligations.show', compact('obligation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obligation = ContractObligation::findOrFail($id);
        $contracts = Contract::where('workspace_id', $this->workspace->id)->get();
        $users = $this->workspace->users()->get();

        return view('contract-obligations.edit', compact('obligation', 'contracts', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'contract_id' => 'required|exists:contracts,id',
                'party_id' => 'required|exists:users,id',
                'party_type' => 'required|in:client,contractor,consultant,supervisor,other',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'obligation_type' => 'required|in:payment,delivery,performance,compliance,reporting,other',
                'priority' => 'required|in:low,medium,high,critical',
                'status' => 'required|in:pending,in_progress,completed,overdue,cancelled',
                'due_date' => 'nullable|date',
                'completed_date' => 'nullable|date|required_if:status,completed',
                'assigned_to' => 'nullable|exists:users,id',
                'notes' => 'nullable|string',
                'supporting_documents' => 'nullable|array',
                'supporting_documents.*' => 'file|mimes:pdf,jpg,png,jpeg|max:10240', // Max 10MB per file
            ]);

            $obligation = ContractObligation::findOrFail($id);

            // Handle document uploads if any
            $documentPaths = $obligation->supporting_documents ?? [];
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $document) {
                    $path = $document->store('contract-obligations/documents', 'public');
                    $documentPaths[] = $path;
                }
            }

            $obligation->update([
                'contract_id' => $request->contract_id,
                'party_id' => $request->party_id,
                'party_type' => $request->party_type,
                'title' => $request->title,
                'description' => $request->description,
                'obligation_type' => $request->obligation_type,
                'priority' => $request->priority,
                'status' => $request->status,
                'due_date' => $request->due_date,
                'completed_date' => $request->completed_date,
                'assigned_to' => $request->assigned_to,
                'notes' => $request->notes,
                'supporting_documents' => $documentPaths,
            ]);

            Session::flash('message', 'Contract obligation updated successfully.');
            return response()->json(['error' => false, 'id' => $obligation->id, 'message' => 'Contract obligation updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $obligation = ContractObligation::findOrFail($id);
            $obligation->delete();

            return response()->json(['error' => false, 'message' => 'Contract obligation deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Mark obligation as completed
     */
    public function markCompleted(Request $request, $id)
    {
        try {
            $obligation = ContractObligation::findOrFail($id);
            
            // Check if user is authorized to update this obligation
            $authorized = isAdminOrHasAllDataAccess() || 
                         $this->user->id == $obligation->assigned_to ||
                         $this->user->id == $obligation->party_id;
            
            if (!$authorized) {
                abort(403, 'Unauthorized to update this obligation');
            }

            $obligation->update([
                'status' => 'completed',
                'completed_date' => now(),
            ]);

            Session::flash('message', 'Contract obligation marked as completed successfully.');
            return response()->json(['error' => false, 'message' => 'Contract obligation marked as completed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update compliance status
     */
    public function updateCompliance(Request $request, $id)
    {
        try {
            $request->validate([
                'compliance_status' => 'required|in:compliant,non_compliant,partially_compliant',
                'compliance_notes' => 'nullable|string',
            ]);

            $obligation = ContractObligation::findOrFail($id);

            $obligation->update([
                'compliance_status' => $request->compliance_status,
                'compliance_notes' => $request->compliance_notes,
                'compliance_checked_by' => $this->user->id,
                'compliance_checked_at' => now(),
            ]);

            Session::flash('message', 'Contract obligation compliance status updated successfully.');
            return response()->json(['error' => false, 'message' => 'Contract obligation compliance status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Archive a contract obligation
     */
    public function archive(Request $request, $id)
    {
        try {
            $obligation = ContractObligation::findOrFail($id);
            
            // Note: ContractObligation model doesn't typically have an archived status
            // This is just a placeholder in case you want to implement archiving
            $obligation->update(['status' => 'archived']);
            
            Session::flash('message', 'Contract obligation archived successfully.');
            return response()->json(['error' => false, 'message' => 'Contract obligation archived successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
}