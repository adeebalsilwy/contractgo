<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractQuantity;
use App\Models\ContractApproval;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Client;
use App\Models\Item;
use App\Models\Unit;
use App\Models\ContractAmendment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class ContractQuantitiesController extends Controller
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
        // Return the view - data will be loaded via AJAX
        return view('contract-quantities.index');
    }

    /**
     * Show the form for creating a new resource without contract ID.
     */
    public function createGeneral()
    {
        // Check if user is admin or has all data access
        if (isAdminOrHasAllDataAccess()) {
            $contracts = Contract::with(['client'])->get();
        } else {
            // For non-admin users, only show contracts they have access to (as site supervisor or client)
            $userId = $this->user->id;
            $contracts = Contract::with(['client'])
                ->where(function($query) use ($userId) {
                    $query->where('site_supervisor_id', $userId)
                          ->orWhere('client_id', $userId);
                })
                ->get();
        }
        
        $clients = $this->workspace->clients;
        $items = Item::where('workspace_id', $this->workspace->id)->with(['unit', 'profession'])->get();
        $units = Unit::all(); // Units are typically global
        
        return view('contract-quantities.create', compact('contracts', 'clients', 'items', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($contractId)
    {
        $contract = Contract::with(['client'])->findOrFail($contractId);
        $clients = $this->workspace->clients;
        $items = Item::where('workspace_id', $this->workspace->id)->with(['unit', 'profession'])->get();
        $units = Unit::all(); // Units are typically global
        
        $contracts = collect([$contract]); // Pass single contract as collection for consistency
        
        return view('contract-quantities.create', compact('contract', 'contracts', 'clients', 'items', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'contract_id' => 'required|exists:contracts,id',
                'item_id' => 'nullable|exists:items,id',
                'item_description' => 'required|string|max:255',
                'requested_quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
                'unit_price' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'supporting_documents' => 'nullable|array',
                'supporting_documents.*' => 'file|mimes:pdf,jpg,png,jpeg|max:10240', // Max 10MB per file
            ]);

            $contract = Contract::findOrFail($request->contract_id);
            
            // Check if user is authorized to submit quantities for this contract
            // Allow site supervisor, client (owner), or admin
            $isAuthorized = isAdminOrHasAllDataAccess() || 
                           $this->user->id == $contract->site_supervisor_id ||
                           $this->user->id == $contract->client_id; // Allow client to upload quantities
            
            if (!$isAuthorized) {
                abort(403, 'Unauthorized to submit quantities for this contract');
            }

            // Handle item selection if provided
            $itemDescription = $request->item_description;
            $unit = $request->unit;
            $unitPrice = $request->unit_price;
            
            if ($request->filled('item_id')) {
                $item = Item::find($request->item_id);
                if ($item) {
                    // Use item details if not overridden by form
                    $itemDescription = $request->item_description ?: $item->title;
                    $unit = $request->unit ?: ($item->unit->title ?? '');
                    $unitPrice = $request->unit_price ?: $item->price;
                }
            }
            
            // Calculate total amount if unit price is provided
            $totalAmount = 0;
            if ($unitPrice !== null && $request->filled('requested_quantity')) {
                $totalAmount = $request->requested_quantity * $unitPrice;
            }

            // Handle document uploads if any
            $documentPaths = [];
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $document) {
                    $path = $document->store('contract-quantities/documents', 'public');
                    $documentPaths[] = $path;
                }
            }

            $contractQuantity = ContractQuantity::create([
                'contract_id' => $request->contract_id,
                'user_id' => $this->user->id,
                'item_id' => $request->item_id, // Store the item ID if provided
                'item_description' => $itemDescription,
                'requested_quantity' => $request->requested_quantity,
                'unit' => $unit,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'supporting_documents' => $documentPaths,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);

            // Update contract workflow status
            if ($contract->workflow_status !== 'approved') {
                $contract->update(['workflow_status' => 'quantity_approval']);
            }

            Session::flash('message', 'Contract quantity submitted successfully.');
            return response()->json(['error' => false, 'id' => $contractQuantity->id, 'message' => 'Contract quantity submitted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contractQuantity = ContractQuantity::with(['contract', 'user', 'approvedRejectedBy'])->findOrFail($id);
        return view('contract-quantities.show', compact('contractQuantity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contractQuantity = ContractQuantity::with(['contract'])->findOrFail($id);
        $contract = $contractQuantity->contract;
        $clients = $this->workspace->clients;
        $items = Item::where('workspace_id', $this->workspace->id)->with(['unit', 'profession'])->get();
        $units = Unit::all(); // Units are typically global
        
        return view('contract-quantities.edit', compact('contractQuantity', 'contract', 'clients', 'items', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $contractQuantity = ContractQuantity::findOrFail($id);

            $request->validate([
                'item_id' => 'nullable|exists:items,id',
                'item_description' => 'required|string|max:255',
                'requested_quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
                'unit_price' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'supporting_documents' => 'nullable|array',
                'supporting_documents.*' => 'file|mimes:pdf,jpg,png,jpeg|max:10240', // Max 10MB per file
            ]);

            // Check if user is authorized to edit this quantity
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contractQuantity->user_id) {
                abort(403, 'Unauthorized to edit this quantity');
            }

            // Only allow editing if status is still pending
            if ($contractQuantity->status !== 'pending') {
                abort(403, 'Cannot edit quantity after it has been processed');
            }

            // Handle item selection if provided
            $itemDescription = $request->item_description;
            $unit = $request->unit;
            $unitPrice = $request->unit_price;
            
            if ($request->filled('item_id')) {
                $item = Item::find($request->item_id);
                if ($item) {
                    // Use item details if not overridden by form
                    $itemDescription = $request->item_description ?: $item->title;
                    $unit = $request->unit ?: ($item->unit->title ?? '');
                    $unitPrice = $request->unit_price ?: $item->price;
                }
            }
            
            // Calculate total amount if unit price is provided
            $totalAmount = 0;
            if ($unitPrice !== null && $request->filled('requested_quantity')) {
                $totalAmount = $request->requested_quantity * $unitPrice;
            }

            // Handle document uploads if any
            $documentPaths = json_decode($contractQuantity->supporting_documents, true) ?: [];
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $document) {
                    $path = $document->store('contract-quantities/documents', 'public');
                    $documentPaths[] = $path;
                }
            }

            $contractQuantity->update([
                'item_id' => $request->item_id, // Update the item ID if provided
                'item_description' => $itemDescription,
                'requested_quantity' => $request->requested_quantity,
                'unit' => $unit,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'supporting_documents' => $documentPaths,
            ]);

            Session::flash('message', 'Contract quantity updated successfully.');
            return response()->json(['error' => false, 'id' => $contractQuantity->id, 'message' => 'Contract quantity updated successfully.']);
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
            $contractQuantity = ContractQuantity::findOrFail($id);
            
            // Check if user is authorized to delete this quantity
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contractQuantity->user_id) {
                abort(403, 'Unauthorized to delete this quantity');
            }
            
            // Only allow deletion if status is still pending
            if ($contractQuantity->status !== 'pending') {
                abort(403, 'Cannot delete quantity after it has been processed');
            }
            
            $contractQuantity->delete();
            
            return response()->json(['error' => false, 'message' => 'Contract quantity deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * List contract quantities with filtering and pagination.
     */
    public function list(Request $request)
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = request('status');
        $contract_ids = request('contract_ids', []);
        $user_ids = request('user_ids', []);

        $contractQuantities = ContractQuantity::with(['contract', 'user']);

        if (!isAdminOrHasAllDataAccess()) {
            // Limit access based on user permissions
            $contractQuantities = $contractQuantities->where(function ($query) {
                $query->where('user_id', $this->user->id);
            });
        }

        if (!empty($contract_ids)) {
            $contractQuantities->whereIn('contract_id', $contract_ids);
        }

        if (!empty($user_ids)) {
            $contractQuantities->whereIn('user_id', $user_ids);
        }

        if ($status) {
            $contractQuantities->where('status', $status);
        }

        if ($search) {
            $contractQuantities = $contractQuantities->where(function ($query) use ($search) {
                $query->where('item_description', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%')
                      ->orWhere('unit', 'like', '%' . $search . '%');
            });
        }

        $total = $contractQuantities->count();

        $contractQuantities = $contractQuantities->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($quantity) {
                return [
                    'id' => $quantity->id,
                    'contract' => $quantity->contract ? $quantity->contract->title : 'N/A',
                    'item_description' => $quantity->item_description,
                    'requested_quantity' => $quantity->requested_quantity,
                    'approved_quantity' => $quantity->approved_quantity ?? 'N/A',
                    'unit' => $quantity->unit,
                    'unit_price' => $quantity->unit_price ? format_currency($quantity->unit_price) : 'N/A',
                    'total_amount' => $quantity->total_amount ? format_currency($quantity->total_amount) : 'N/A',
                    'status' => '<span class="badge bg-' . ($quantity->status === 'approved' ? 'success' : ($quantity->status === 'rejected' ? 'danger' : 'warning')) . '">' . ucfirst($quantity->status) . '</span>',
                    'submitted_by' => $quantity->user ? $quantity->user->first_name . ' ' . $quantity->user->last_name : 'N/A',
                    'submitted_at' => format_date($quantity->submitted_at, true),
                    'actions' => '
                        <a href="' . route('contract-quantities.show', $quantity->id) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>
                        <a href="' . route('contract-quantities.edit', $quantity->id) . '" class="text-warning" title="Edit"><i class="bx bx-edit"></i></a>
                        <button type="button" class="btn text-danger delete" data-id="' . $quantity->id . '" data-type="contract-quantities"><i class="bx bx-trash"></i></button>
                    '
                ];
            });

        return response()->json([
            "rows" => $contractQuantities->items(),
            "total" => $total,
        ]);
    }

    /**
     * Upload quantities for a specific contract
     */
    public function uploadQuantities(Request $request, $contractId)
    {
        $contract = Contract::with(['client'])->findOrFail($contractId);
        $clients = $this->workspace->clients;
        $items = Item::where('workspace_id', $this->workspace->id)->with(['unit', 'profession'])->get();
        $units = Unit::all(); // Units are typically global
        
        // Check if user is authorized to upload quantities for this contract
        if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->site_supervisor_id) {
            abort(403, 'Unauthorized to upload quantities for this contract');
        }

        return view('contract-quantities.upload', compact('contract', 'clients', 'items', 'units'));
    }

    /**
     * Bulk upload quantities
     */
    public function bulkUpload(Request $request, $contractId)
    {
        $contract = Contract::findOrFail($contractId);
        
        // Check if user is authorized to upload quantities for this contract
        if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->site_supervisor_id) {
            abort(403, 'Unauthorized to upload quantities for this contract');
        }

        try {
            $request->validate([
                'quantities' => 'required|array|min:1',
                'quantities.*.item_description' => 'required|string|max:255',
                'quantities.*.requested_quantity' => 'required|numeric|min:0',
                'quantities.*.unit' => 'required|string|max:50',
                'quantities.*.unit_price' => 'nullable|numeric|min:0',
                'quantities.*.notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            foreach ($request->quantities as $quantityData) {
                // Calculate total amount if unit price is provided
                $totalAmount = 0;
                if (isset($quantityData['unit_price']) && !empty($quantityData['unit_price'])) {
                    $totalAmount = $quantityData['requested_quantity'] * $quantityData['unit_price'];
                }

                ContractQuantity::create([
                    'contract_id' => $contractId,
                    'user_id' => $this->user->id,
                    'item_description' => $quantityData['item_description'],
                    'requested_quantity' => $quantityData['requested_quantity'],
                    'unit' => $quantityData['unit'],
                    'unit_price' => $quantityData['unit_price'] ?? null,
                    'total_amount' => $totalAmount,
                    'notes' => $quantityData['notes'] ?? null,
                    'status' => 'pending',
                    'submitted_at' => now(),
                ]);
            }

            // Update contract workflow status
            if ($contract->workflow_status !== 'approved') {
                $contract->update(['workflow_status' => 'quantity_approval']);
            }

            DB::commit();

            Session::flash('message', 'Contract quantities uploaded successfully.');
            return response()->json(['error' => false, 'message' => 'Contract quantities uploaded successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Approve a quantity submission
     */
    public function approveQuantity(Request $request, $id)
    {
        try {
            $contractQuantity = ContractQuantity::findOrFail($id);
            
            // Check if user is authorized to approve this quantity
            $contract = $contractQuantity->contract;
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->quantity_approver_id) {
                abort(403, 'Unauthorized to approve this quantity');
            }

            $request->validate([
                'approved_quantity' => 'required|numeric|min:0',
                'approval_rejection_notes' => 'nullable|string',
                'quantity_approval_signature' => 'nullable|string', // base64 encoded signature
            ]);

            $contractQuantity->update([
                'approved_quantity' => $request->approved_quantity,
                'status' => 'approved',
                'approved_rejected_at' => now(),
                'approved_rejected_by' => $this->user->id,
                'approval_rejection_notes' => $request->approval_rejection_notes,
                'quantity_approval_signature' => $request->quantity_approval_signature,
            ]);

            // Create an approval record
            ContractApproval::create([
                'contract_id' => $contractQuantity->contract_id,
                'approval_stage' => 'quantity_approval',
                'approver_id' => $this->user->id,
                'status' => 'approved',
                'comments' => $request->approval_rejection_notes,
                'approved_rejected_at' => now(),
                'approval_signature' => $request->quantity_approval_signature,
            ]);

            // Update contract workflow status if all quantities are approved
            $pendingQuantities = $contract->quantities()->where('status', 'pending')->count();
            if ($pendingQuantities == 0) {
                $contract->update(['workflow_status' => 'management_review']);
            }

            Session::flash('message', 'Quantity approved successfully.');
            return response()->json(['error' => false, 'message' => 'Quantity approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Reject a quantity submission
     */
    public function rejectQuantity(Request $request, $id)
    {
        try {
            $contractQuantity = ContractQuantity::findOrFail($id);
            
            // Check if user is authorized to reject this quantity
            $contract = $contractQuantity->contract;
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->quantity_approver_id) {
                abort(403, 'Unauthorized to reject this quantity');
            }

            $request->validate([
                'rejection_reason' => 'required|string',
                'quantity_approval_signature' => 'nullable|string', // base64 encoded signature
            ]);

            $contractQuantity->update([
                'status' => 'rejected',
                'approved_rejected_at' => now(),
                'approved_rejected_by' => $this->user->id,
                'approval_rejection_notes' => $request->rejection_reason,
                'quantity_approval_signature' => $request->quantity_approval_signature,
            ]);

            // Create an approval record for rejection
            ContractApproval::create([
                'contract_id' => $contractQuantity->contract_id,
                'approval_stage' => 'quantity_approval',
                'approver_id' => $this->user->id,
                'status' => 'rejected',
                'comments' => $request->rejection_reason,
                'rejection_reason' => $request->rejection_reason,
                'approved_rejected_at' => now(),
                'approval_signature' => $request->quantity_approval_signature,
            ]);

            Session::flash('message', 'Quantity rejected successfully.');
            return response()->json(['error' => false, 'message' => 'Quantity rejected successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Modify a quantity submission
     */
    public function modifyQuantity(Request $request, $id)
    {
        try {
            $contractQuantity = ContractQuantity::findOrFail($id);
            
            // Check if user is authorized to modify this quantity
            $contract = $contractQuantity->contract;
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->quantity_approver_id) {
                abort(403, 'Unauthorized to modify this quantity');
            }

            $request->validate([
                'approved_quantity' => 'required|numeric|min:0',
                'approval_rejection_notes' => 'required|string',
                'quantity_approval_signature' => 'nullable|string', // base64 encoded signature
            ]);

            $contractQuantity->update([
                'approved_quantity' => $request->approved_quantity,
                'status' => 'modified',
                'approved_rejected_at' => now(),
                'approved_rejected_by' => $this->user->id,
                'approval_rejection_notes' => $request->approval_rejection_notes,
                'quantity_approval_signature' => $request->quantity_approval_signature,
            ]);

            // Create an approval record for modification
            ContractApproval::create([
                'contract_id' => $contractQuantity->contract_id,
                'approval_stage' => 'quantity_approval',
                'approver_id' => $this->user->id,
                'status' => 'approved', // Modified but approved
                'comments' => $request->approval_rejection_notes,
                'approved_rejected_at' => now(),
                'approval_signature' => $request->quantity_approval_signature,
            ]);

            // Update contract workflow status if all quantities are approved
            $pendingQuantities = $contract->quantities()->where('status', 'pending')->count();
            if ($pendingQuantities == 0) {
                $contract->update(['workflow_status' => 'management_review']);
            }

            Session::flash('message', 'Quantity modified successfully.');
            return response()->json(['error' => false, 'message' => 'Quantity modified successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get pending quantities for approval
     */
    public function pendingForApproval()
    {
        $contractQuantities = ContractQuantity::with(['contract', 'user'])
            ->where('status', 'pending')
            ->whereHas('contract', function ($query) {
                $query->where('quantity_approver_id', $this->user->id)
                      ->orWhere('workspace_id', $this->workspace->id);
            })
            ->get();

        return view('contract-quantities.pending-approval', compact('contractQuantities'));
    }
    
    /**
     * Create amendment request for a quantity
     */
    public function requestAmendment(Request $request, $id)
    {
        try {
            $contractQuantity = ContractQuantity::with('contract')->findOrFail($id);
            
            // Check if user is authorized to request amendment for this quantity
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contractQuantity->contract->final_approver_id) {
                abort(403, 'Unauthorized to request amendment for this quantity');
            }

            $request->validate([
                'amendment_reason' => 'required|string|max:1000',
            ]);

            // Create amendment request
            $amendment = ContractAmendment::create([
                'contract_id' => $contractQuantity->contract_id,
                'amendment_type' => 'quantity_modification',
                'description' => $request->amendment_reason,
                'status' => 'pending',
                'requested_by' => $this->user->id,
                'requested_at' => now(),
                'related_model' => 'ContractQuantity',
                'related_model_id' => $contractQuantity->id,
            ]);

            // Update contract workflow status
            $contractQuantity->contract->update([
                'amendment_requested' => true,
                'amendment_reason' => $request->amendment_reason,
                'amendment_requested_at' => now(),
                'amendment_requested_by' => $this->user->id,
                'workflow_status' => 'amendment_pending',
            ]);

            Session::flash('message', 'Amendment request submitted successfully.');
            return response()->json(['error' => false, 'message' => 'Amendment request submitted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Approve amendment request for a quantity
     */
    public function approveAmendment(Request $request, $id)
    {
        try {
            $amendment = ContractAmendment::findOrFail($id);
            $contractQuantity = ContractQuantity::find($amendment->related_model_id);
            
            if (!$contractQuantity) {
                throw new \Exception('Related quantity not found');
            }
            
            // Check if user is authorized to approve amendment
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contractQuantity->contract->final_approver_id) {
                abort(403, 'Unauthorized to approve amendment');
            }

            $amendment->update([
                'status' => 'approved',
                'approved_by' => $this->user->id,
                'approved_at' => now(),
            ]);

            // Update contract status
            $contractQuantity->contract->update([
                'amendment_approved' => true,
                'amendment_approved_at' => now(),
                'amendment_approved_by' => $this->user->id,
                'workflow_status' => 'quantity_approval', // Go back to quantity approval for the modified quantity
            ]);

            Session::flash('message', 'Amendment approved successfully.');
            return response()->json(['error' => false, 'message' => 'Amendment approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Reject amendment request for a quantity
     */
    public function rejectAmendment(Request $request, $id)
    {
        try {
            $amendment = ContractAmendment::findOrFail($id);
            $contractQuantity = ContractQuantity::find($amendment->related_model_id);
            
            if (!$contractQuantity) {
                throw new \Exception('Related quantity not found');
            }
            
            // Check if user is authorized to reject amendment
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $contractQuantity->contract->final_approver_id) {
                abort(403, 'Unauthorized to reject amendment');
            }

            $amendment->update([
                'status' => 'rejected',
                'rejected_by' => $this->user->id,
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason ?? 'No reason provided',
            ]);

            // Update contract status
            $contractQuantity->contract->update([
                'amendment_approved' => false,
                'workflow_status' => 'approved', // Maintain approved status since amendment was rejected
            ]);

            Session::flash('message', 'Amendment rejected successfully.');
            return response()->json(['error' => false, 'message' => 'Amendment rejected successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
}