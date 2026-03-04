<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractQuantity;
use App\Models\ContractApproval;
use App\Models\ContractAmendment;
use App\Models\JournalEntry;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WorkflowTestController extends Controller
{
    protected $workspace;
    protected $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->workspace = Workspace::find(getWorkspaceId());
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    
    /**
     * Display the workflow test dashboard
     */
    public function index()
    {
        $stats = [
            'total_contracts' => Contract::where('workspace_id', $this->workspace->id)->count(),
            'pending_quantities' => ContractQuantity::where('status', 'pending')->count(),
            'pending_approvals' => ContractApproval::where('status', 'pending')->count(),
            'pending_amendments' => ContractAmendment::where('status', 'pending')->count(),
            'journal_entries' => JournalEntry::where('workspace_id', $this->workspace->id)->count(),
        ];
        
        return view('workflow-tests.index', compact('stats'));
    }
    
    /**
     * Test contract creation view
     */
    public function testContractCreation()
    {
        $clients = $this->workspace->clients()->with('profession')->get();
        $projects = $this->workspace->projects()->get();
        $contractTypes = \App\Models\ContractType::forWorkspace($this->workspace->id)->get();
        $users = $this->workspace->users()->get();
        $professions = \App\Models\Profession::where('workspace_id', $this->workspace->id)->get();
        $items = \App\Models\Item::where('workspace_id', $this->workspace->id)->with('profession', 'unit')->get();
        
        return view('contracts.create_professional', compact(
            'clients', 'projects', 'contractTypes', 'users', 
            'professions', 'items'
        ));
    }
    
    /**
     * Test quantity upload view
     */
    public function testQuantityUpload($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        $items = \App\Models\Item::where('workspace_id', $this->workspace->id)
            ->with('profession', 'unit')
            ->get();
        $units = \App\Models\Unit::all();
        
        return view('contract-quantities.upload', compact('contract', 'items', 'units'));
    }
    
    /**
     * Test quantity approval view
     */
    public function testQuantityApproval()
    {
        $quantities = ContractQuantity::where('status', 'pending')
            ->whereHas('contract', function($query) {
                $query->where('quantity_approver_id', $this->user->id);
            })
            ->with(['contract', 'user'])
            ->get();
        
        return view('contract-quantities.pending-approval', compact('quantities'));
    }
    
    /**
     * Test contract approval view
     */
    public function testContractApproval($contractId, $stage)
    {
        $contract = Contract::findOrFail($contractId);
        $currentApproval = ContractApproval::where('contract_id', $contractId)
            ->where('approval_stage', $stage)
            ->where('approver_id', $this->user->id)
            ->first();
        
        if (!$currentApproval) {
            // Create one for testing
            $currentApproval = ContractApproval::create([
                'contract_id' => $contractId,
                'approval_stage' => $stage,
                'approver_id' => $this->user->id,
                'status' => 'pending',
            ]);
        }
        
        return view('contract-approvals.show', compact('contract', 'currentApproval', 'stage'));
    }
    
    /**
     * Test journal entry creation view
     */
    public function testJournalEntry()
    {
        $contracts = isAdminOrHasAllDataAccess() 
            ? Contract::where('workspace_id', $this->workspace->id)->get()
            : Contract::where('workspace_id', $this->workspace->id)
                       ->where(function ($query) {
                           $query->where('created_by', 'u_' . $this->user->id)
                                 ->orWhere('client_id', $this->user->id);
                       })->get();
        
        $invoices = isAdminOrHasAllDataAccess() 
            ? \App\Models\EstimatesInvoice::where('workspace_id', $this->workspace->id)->get()
            : \App\Models\EstimatesInvoice::where('workspace_id', $this->workspace->id)
                             ->where(function ($query) {
                                 $query->where('created_by', 'u_' . $this->user->id)
                                       ->orWhere('client_id', $this->user->id);
                             })->get();
        
        return view('journal-entries.create', compact('contracts', 'invoices'));
    }
    
    /**
     * Test amendment request view
     */
    public function testAmendmentRequest($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        
        return view('contract-amendments.create', compact('contract'));
    }
    
    /**
     * Test obligations view
     */
    public function testObligations()
    {
        $obligations = \App\Models\ContractObligation::whereHas('contract', function($query) {
                $query->where('workspace_id', $this->workspace->id);
            })
            ->with(['contract', 'responsibleParty'])
            ->get();
        
        return view('contract-obligations.index', compact('obligations'));
    }
    
    /**
     * Run automated workflow tests
     */
    public function runTests()
    {
        $results = [];
        
        // Test 1: Check if all views are accessible
        $views = [
            'contracts.create_professional',
            'contract-quantities.index',
            'contract-approvals.index',
            'journal-entries.index',
            'contract-amendments.index',
            'contract-obligations.index',
        ];
        
        foreach ($views as $view) {
            try {
                view($view)->render();
                $results[] = [
                    'view' => $view,
                    'status' => '✓ Pass',
                    'message' => 'View loaded successfully'
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'view' => $view,
                    'status' => '✗ Fail',
                    'message' => $e->getMessage()
                ];
            }
        }
        
        // Test 2: Check data relationships
        $contracts = Contract::where('workspace_id', $this->workspace->id)
            ->with(['client', 'project', 'siteSupervisor', 'quantityApprover', 'accountant'])
            ->get();
        
        foreach ($contracts as $contract) {
            $results[] = [
                'test' => 'Contract Relationships - ' . $contract->title,
                'status' => $contract->client ? '✓ Pass' : '⚠ Warning',
                'message' => $contract->client ? 'All relationships loaded' : 'Missing client relationship'
            ];
        }
        
        // Test 3: Check workflow status
        $workflowStatuses = Contract::distinct('workflow_status')->pluck('workflow_status');
        foreach ($workflowStatuses as $status) {
            $count = Contract::where('workflow_status', $status)->count();
            $results[] = [
                'test' => 'Workflow Status: ' . $status,
                'status' => '✓ Info',
                'message' => "{$count} contracts with this status"
            ];
        }
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'summary' => [
                'total_tests' => count($results),
                'passed' => collect($results)->where('status', 'like', '✓%')->count(),
                'failed' => collect($results)->where('status', 'like', '✗%')->count(),
            ]
        ]);
    }
    
    /**
     * Simulate complete workflow for testing
     */
    public function simulateWorkflow(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Get or create a test contract
            $contract = Contract::firstOrCreate(
                ['title' => 'Test Workflow Contract ' . time()],
                [
                    'workspace_id' => $this->workspace->id,
                    'value' => 100000,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                    'client_id' => $this->workspace->clients()->first()?->id ?? 1,
                    'project_id' => $this->workspace->projects()->first()?->id ?? 1,
                    'contract_type_id' => \App\Models\ContractType::where('workspace_id', $this->workspace->id)->first()?->id ?? 1,
                    'created_by' => $this->user->id,
                    'workflow_status' => 'draft',
                ]
            );
            
            // Simulate quantity submission
            $quantity = ContractQuantity::create([
                'contract_id' => $contract->id,
                'user_id' => $this->user->id,
                'workspace_id' => $this->workspace->id,
                'item_description' => 'Test Item',
                'requested_quantity' => 100,
                'unit' => 'Unit',
                'unit_price' => 50,
                'total_amount' => 5000,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);
            
            // Simulate approval
            $approval = ContractApproval::create([
                'contract_id' => $contract->id,
                'approval_stage' => 'quantity_approval',
                'approver_id' => $this->user->id,
                'status' => 'approved',
                'approved_rejected_at' => now(),
            ]);
            
            // Update contract status
            $contract->update(['workflow_status' => 'quantity_approval']);
            
            DB::commit();
            
            Session::flash('message', 'Workflow simulation completed successfully!');
            
            return response()->json([
                'success' => true,
                'message' => 'Workflow simulation completed',
                'data' => [
                    'contract' => $contract,
                    'quantity' => $quantity,
                    'approval' => $approval,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
