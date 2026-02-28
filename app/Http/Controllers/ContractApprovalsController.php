<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractApproval;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class ContractApprovalsController extends Controller
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
     * Display a listing of contract approvals with tabbed interface.
     */
    public function index()
    {
        // Return the view - data will be loaded via AJAX
        return view('contract-approvals.index');
    }

    /**
     * Show approval form for a specific contract and stage.
     */
    public function show($contractId, $stage)
    {
        $contract = Contract::findOrFail($contractId);
        $currentApproval = ContractApproval::where('contract_id', $contractId)
            ->where('approval_stage', $stage)
            ->where('approver_id', $this->user->id)
            ->first();

        if (!$currentApproval) {
            abort(404, 'Approval record not found or you are not authorized to approve this stage.');
        }

        return view('contract-approvals.show', compact('contract', 'currentApproval', 'stage'));
    }

    /**
     * Approve a contract approval.
     */
    public function approveApproval(Request $request, $id)
    {
        try {
            $approval = ContractApproval::findOrFail($id);
            
            // Check if user is authorized to approve this approval
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $approval->approver_id) {
                abort(403, 'Unauthorized to approve this approval');
            }

            $request->validate([
                'comments' => 'nullable|string',
                'approval_signature' => 'nullable|string', // base64 encoded signature
            ]);

            $approval->update([
                'status' => 'approved',
                'comments' => $request->comments,
                'approved_rejected_at' => now(),
                'approval_signature' => $request->approval_signature,
            ]);

            // Update the main contract's workflow status
            $this->updateContractWorkflowStatus($approval->contract, $approval->approval_stage);

            Session::flash('message', 'Approval processed successfully.');
            return response()->json(['error' => false, 'message' => 'Approval approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Reject a contract approval.
     */
    public function rejectApproval(Request $request, $id)
    {
        try {
            $approval = ContractApproval::findOrFail($id);
            
            // Check if user is authorized to reject this approval
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $approval->approver_id) {
                abort(403, 'Unauthorized to reject this approval');
            }

            $request->validate([
                'rejection_reason' => 'required|string',
                'approval_signature' => 'nullable|string', // base64 encoded signature
            ]);

            $approval->update([
                'status' => 'rejected',
                'comments' => $request->rejection_reason,
                'rejection_reason' => $request->rejection_reason,
                'approved_rejected_at' => now(),
                'approval_signature' => $request->approval_signature,
            ]);

            // Update the main contract's workflow status to reflect rejection
            $approval->contract->update([
                'workflow_status' => 'amendment_pending',
                'workflow_notes' => ($approval->contract->workflow_notes ?? '') . "\nRejected at " . $approval->approval_stage . " stage on " . now() . " by " . $this->user->first_name . " " . $this->user->last_name . ". Reason: " . $request->rejection_reason
            ]);

            Session::flash('message', 'Approval rejected successfully.');
            return response()->json(['error' => false, 'message' => 'Approval rejected successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Approve a contract at a specific stage.
     */
    public function approve(Request $request, $contractId, $stage)
    {
        try {
            $contract = Contract::findOrFail($contractId);
            
            // Check if user is authorized to approve at this stage
            $approverField = $this->getApproverFieldByStage($stage);
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->$approverField) {
                abort(403, 'Unauthorized to approve at this stage');
            }

            $request->validate([
                'comments' => 'nullable|string',
                'approval_signature' => 'nullable|string', // base64 encoded signature
            ]);

            // Check if there's already an approval record for this stage
            $approval = ContractApproval::firstOrCreate([
                'contract_id' => $contractId,
                'approval_stage' => $stage,
                'approver_id' => $this->user->id,
            ], [
                'status' => 'pending',
            ]);

            $approval->update([
                'status' => 'approved',
                'comments' => $request->comments,
                'approved_rejected_at' => now(),
                'approval_signature' => $request->approval_signature,
            ]);

            // Update the main contract's workflow status
            $this->updateContractWorkflowStatus($contract, $stage);

            Session::flash('message', 'Contract approved successfully at ' . $stage . ' stage.');
            return response()->json(['error' => false, 'message' => 'Contract approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Reject a contract at a specific stage.
     */
    public function reject(Request $request, $contractId, $stage)
    {
        try {
            $contract = Contract::findOrFail($contractId);
            
            // Check if user is authorized to reject at this stage
            $approverField = $this->getApproverFieldByStage($stage);
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->$approverField) {
                abort(403, 'Unauthorized to reject at this stage');
            }

            $request->validate([
                'rejection_reason' => 'required|string',
                'approval_signature' => 'nullable|string', // base64 encoded signature
            ]);

            // Check if there's already an approval record for this stage
            $approval = ContractApproval::firstOrCreate([
                'contract_id' => $contractId,
                'approval_stage' => $stage,
                'approver_id' => $this->user->id,
            ], [
                'status' => 'pending',
            ]);

            $approval->update([
                'status' => 'rejected',
                'comments' => $request->rejection_reason,
                'rejection_reason' => $request->rejection_reason,
                'approved_rejected_at' => now(),
                'approval_signature' => $request->approval_signature,
            ]);

            // Update the main contract's workflow status to reflect rejection
            $contract->update([
                'workflow_status' => 'amendment_pending',
                'workflow_notes' => ($contract->workflow_notes ?? '') . "\nRejected at " . $stage . " stage on " . now() . " by " . $this->user->first_name . " " . $this->user->last_name . ". Reason: " . $request->rejection_reason
            ]);

            Session::flash('message', 'Contract rejected at ' . $stage . ' stage.');
            return response()->json(['error' => false, 'message' => 'Contract rejected successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get the approver field name based on the approval stage.
     */
    private function getApproverFieldByStage($stage)
    {
        switch ($stage) {
            case 'quantity_approval':
                return 'quantity_approver_id';
            case 'management_review':
                return 'final_approver_id'; // Management review typically done by final approver
            case 'accounting_review':
                return 'accountant_id';
            case 'final_approval':
                return 'final_approver_id';
            default:
                return 'final_approver_id';
        }
    }

    /**
     * Update the contract's workflow status based on the approval stage.
     */
    private function updateContractWorkflowStatus($contract, $stage)
    {
        $newStatus = '';
        
        switch ($stage) {
            case 'quantity_approval':
                $newStatus = 'quantity_approval';
                break;
            case 'management_review':
                $newStatus = 'management_review';
                break;
            case 'accounting_review':
                $newStatus = 'accounting_processing';
                break;
            case 'final_approval':
                $newStatus = 'approved';
                break;
            default:
                $newStatus = $contract->workflow_status;
        }
        
        // Update the contract's workflow status
        $contract->update([
            'workflow_status' => $newStatus,
            'workflow_notes' => ($contract->workflow_notes ?? '') . "\nApproved at " . $stage . " stage on " . now() . " by " . $this->user->first_name . " " . $this->user->last_name
        ]);
    }

    /**
     * List all approvals for a specific contract.
     */
    public function listForContract($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        $approvals = ContractApproval::with(['approver'])
            ->where('contract_id', $contractId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('contract-approvals.list-for-contract', compact('contract', 'approvals'));
    }

    /**
     * Get pending approvals for the current user.
     */
    public function pendingApprovals()
    {
        $approvals = ContractApproval::with(['contract', 'approver'])
            ->where('approver_id', $this->user->id)
            ->where('status', 'pending')
            ->get();

        return view('contract-approvals.pending', compact('approvals'));
    }

    /**
     * List contract approvals with filtering and pagination.
     */
    public function list(Request $request)
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = request('status');
        $approval_stage = request('approval_stage');

        $approvals = ContractApproval::with(['contract', 'approver']);

        if (!isAdminOrHasAllDataAccess()) {
            // Limit access based on user permissions
            $approvals = $approvals->where(function ($query) {
                $query->where('approver_id', $this->user->id);
            });
        }

        if ($status) {
            $approvals->where('status', $status);
        }

        if ($approval_stage) {
            $approvals->where('approval_stage', $approval_stage);
        }

        if ($search) {
            $approvals = $approvals->where(function ($query) use ($search) {
                $query->where('id', 'like', '%' . $search . '%')
                      ->orWhereHas('contract', function ($subQuery) use ($search) {
                          $subQuery->where('title', 'like', '%' . $search . '%');
                      });
            });
        }

        $total = $approvals->count();

        $approvals = $approvals->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($approval) {
                return [
                    'id' => $approval->id,
                    'contract' => $approval->contract ? $approval->contract->title : 'N/A',
                    'approval_stage' => '<span class="badge bg-info">' . ucfirst(str_replace('_', ' ', $approval->approval_stage)) . '</span>',
                    'approver' => $approval->approver ? $approval->approver->first_name . ' ' . $approval->approver->last_name : 'N/A',
                    'status' => '<span class="badge bg-' . ($approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'warning')) . '">' . ucfirst($approval->status) . '</span>',
                    'comments' => $approval->comments ?? 'N/A',
                    'submitted_at' => format_date($approval->created_at, true),
                    'approved_rejected_at' => $approval->approved_rejected_at ? format_date($approval->approved_rejected_at, true) : 'N/A',
                    'actions' => '
                        <a href="' . route('contract-approvals.show', [$approval->contract_id, $approval->approval_stage]) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>
                        ' . ($approval->status === 'pending' ? '<button type="button" class="btn text-success approve-btn" data-id="' . $approval->id . '" title="Approve"><i class="bx bx-check"></i></button>
                        <button type="button" class="btn text-danger reject-btn" data-id="' . $approval->id . '" title="Reject"><i class="bx bx-x"></i></button>' : '') . '
                    '
                ];
            });

        return response()->json([
            "rows" => $approvals->items(),
            "total" => $total,
        ]);
    }

    /**
     * View contract approval history.
     */
    public function history($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        $approvals = ContractApproval::with(['approver'])
            ->where('contract_id', $contractId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('contract-approvals.history', compact('contract', 'approvals'));
    }
}