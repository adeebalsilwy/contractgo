<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractQuantity;
use App\Models\ContractApproval;
use App\Models\Workspace;
use App\Models\User;
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
     * Show the form for creating a new resource.
     */
    public function create($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        return view('contract-quantities.create', compact('contract'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'contract_id' => 'required|exists:contracts,id',
                'item_description' => 'required|string|max:255',
                'requested_quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
                'unit_price' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'supporting_documents' => 'nullable|array',
                'supporting_documents.*' => 'file|mimes:pdf,jpg,png,jpeg|max:10240', // Max 10MB per file
            ]);

            // Calculate total amount if unit price is provided
            $totalAmount = 0;
            if ($request->filled('unit_price')) {
                $totalAmount = $request->requested_quantity * $request->unit_price;
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
                'item_description' => $request->item_description,
                'requested_quantity' => $request->requested_quantity,
                'unit' => $request->unit,
                'unit_price' => $request->unit_price,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'supporting_documents' => $documentPaths,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);

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
        $contractQuantity = ContractQuantity::with(['contract', 'user'])->findOrFail($id);
        return view('contract-quantities.show', compact('contractQuantity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contractQuantity = ContractQuantity::findOrFail($id);
        $contract = $contractQuantity->contract;
        return view('contract-quantities.edit', compact('contractQuantity', 'contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $contractQuantity = ContractQuantity::findOrFail($id);

            $request->validate([
                'item_description' => 'required|string|max:255',
                'requested_quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
                'unit_price' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'supporting_documents' => 'nullable|array',
                'supporting_documents.*' => 'file|mimes:pdf,jpg,png,jpeg|max:10240', // Max 10MB per file
            ]);

            // Calculate total amount if unit price is provided
            $totalAmount = 0;
            if ($request->filled('unit_price')) {
                $totalAmount = $request->requested_quantity * $request->unit_price;
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
                'item_description' => $request->item_description,
                'requested_quantity' => $request->requested_quantity,
                'unit' => $request->unit,
                'unit_price' => $request->unit_price,
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
        $contract = Contract::findOrFail($contractId);
        
        // Check if user is authorized to upload quantities for this contract
        if (!isAdminOrHasAllDataAccess() && $this->user->id != $contract->site_supervisor_id) {
            abort(403, 'Unauthorized to upload quantities for this contract');
        }

        return view('contract-quantities.upload', compact('contract'));
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
}