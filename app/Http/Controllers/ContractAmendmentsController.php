<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractAmendment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractAmendmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the amendments.
     */
    public function index(Request $request)
    {
        $query = ContractAmendment::with(['contract', 'requestedBy', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by amendment type
        if ($request->filled('amendment_type')) {
            $query->where('amendment_type', $request->amendment_type);
        }

        // Filter by contract
        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }

        $amendments = $query->latest()->paginate(10);

        return view('contract-amendments.index', compact('amendments'));
    }

    /**
     * Show the form for creating a new amendment request.
     */
    public function create(Request $request)
    {
        $contractId = $request->query('contract_id');
        $contract = Contract::findOrFail($contractId);

        return view('contract-amendments.create', compact('contract'));
    }

    /**
     * Store a newly created amendment request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'amendment_type' => 'required|string|max:255',
            'request_reason' => 'required|string',
            'details' => 'nullable|string',
            'original_price' => 'nullable|numeric',
            'new_price' => 'nullable|numeric',
            'original_quantity' => 'nullable|numeric',
            'new_quantity' => 'nullable|numeric',
            'original_unit' => 'nullable|string|max:255',
            'new_unit' => 'nullable|string|max:255',
            'original_description' => 'nullable|string',
            'new_description' => 'nullable|string',
        ]);

        $amendment = ContractAmendment::create([
            'contract_id' => $request->contract_id,
            'requested_by_user_id' => Auth::id(),
            'amendment_type' => $request->amendment_type,
            'request_reason' => $request->request_reason,
            'details' => $request->details,
            'original_price' => $request->original_price,
            'new_price' => $request->new_price,
            'original_quantity' => $request->original_quantity,
            'new_quantity' => $request->new_quantity,
            'original_unit' => $request->original_unit,
            'new_unit' => $request->new_unit,
            'original_description' => $request->original_description,
            'new_description' => $request->new_description,
        ]);

        return redirect()->route('contracts.show', $amendment->contract->id)
                         ->with('success', 'طلب التعديل تم إرساله بنجاح.');
    }

    /**
     * Display the specified amendment.
     */
    public function show(ContractAmendment $amendment)
    {
        $amendment->load(['contract', 'requestedBy', 'approvedBy', 'signedBy']);

        return view('contract-amendments.show', compact('amendment'));
    }

    /**
     * Show the form for editing the amendment (admin/approval view).
     */
    public function edit(ContractAmendment $amendment)
    {
        // Check if user has permission to approve amendments
        if (!Auth::user()->can('approve', $amendment)) {
            abort(403, 'غير مصرح لك بتعديل حالة طلب التعديل هذا.');
        }

        return view('contract-amendments.edit', compact('amendment'));
    }

    /**
     * Update the amendment status (approve/reject).
     */
    public function update(Request $request, ContractAmendment $amendment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'approval_comments' => 'nullable|string',
        ]);

        $amendment->update([
            'status' => $request->status,
            'approval_comments' => $request->approval_comments,
            'approved_by_user_id' => Auth::id(),
        ]);

        if ($request->status === 'approved') {
            $amendment->update(['approved_at' => now()]);
            // Trigger the re-flow of the workflow here
            $this->triggerWorkflowReflow($amendment);
        } elseif ($request->status === 'rejected') {
            $amendment->update(['rejected_at' => now()]);
        }

        return redirect()->route('contracts.show', $amendment->contract->id)
                         ->with('success', 'تم تحديث حالة طلب التعديل بنجاح.');
    }

    /**
     * Handle digital signature for the amendment.
     */
    public function sign(Request $request, ContractAmendment $amendment)
    {
        // Check if user has permission to sign
        if (!Auth::user()->can('sign', $amendment)) {
            abort(403, 'غير مصرح لك بالتوقيع على طلب التعديل هذا.');
        }

        $request->validate([
            'signature_data' => 'required|string',
        ]);

        // In a real application, you would save the signature image/file
        // For now, we'll just simulate saving the signature
        $filename = 'signature_' . $amendment->id . '_' . time() . '.png';
        $path = 'signatures/' . $filename;
        
        // Here you would typically save the signature image
        // Storage::put($path, base64_decode($request->signature_data));

        $amendment->update([
            'digital_signature_path' => $path,
            'signed_at' => now(),
            'signed_by_user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'تم التوقيع على طلب التعديل بنجاح']);
    }

    /**
     * Trigger re-flow of the workflow after amendment approval.
     */
    private function triggerWorkflowReflow(ContractAmendment $amendment)
    {
        // Update the original contract with the new values from the amendment
        $contract = $amendment->contract;
        
        // Update contract fields based on the amendment type
        switch ($amendment->amendment_type) {
            case 'price':
                if ($amendment->new_price) {
                    // Update contract pricing information
                    $contract->update(['value' => $amendment->new_price]);
                }
                break;
            case 'quantity':
                // We might need to update related ContractQuantity records
                $contract->quantities()->update([
                    'quantity_requested' => $amendment->new_quantity,
                    'unit_price' => $amendment->new_price ?? $amendment->original_price,
                ]);
                break;
            case 'specification':
                // Update contract description/specifications
                $contract->update(['description' => $amendment->new_description]);
                break;
        }

        // Reset workflow status to restart the process
        $contract->update([
            'workflow_status' => 'amendment_approved',
            'amendment_requested' => false,
            'quantity_approval_status' => 'pending', // Reset to initial state
            'management_review_status' => 'pending',
            'accounting_review_status' => 'pending',
            'final_approval_status' => 'pending',
        ]);

        // Log the amendment event
        activity()
            ->causedBy(auth()->user())
            ->performedOn($contract)
            ->withProperties([
                'amendment_id' => $amendment->id,
                'amendment_type' => $amendment->amendment_type,
                'change_details' => $amendment->details,
            ])
            ->log('تمت الموافقة على تعديل وبدأ إعادة تدفق العملية');
    }
}