@extends('layout')
@section('title')
    <?= get_label('contract_quantity_details', 'Contract Quantity Details') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-quantities';
    @endphp

    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('home') }}"><?= get_label('home', 'Home') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('contract-quantities.index') }}"><?= get_label('contract_quantities', 'Contract Quantities') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('details', 'Details') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-quantities.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i> <?= get_label('back', 'Back') ?>
                    </button>
                </a>
                @if(checkPermission('edit_contract_quantities'))
                <a href="{{ route('contract-quantities.edit', $contractQuantity->id) }}">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('edit', 'Edit') ?>">
                        <i class='bx bx-edit'></i>
                    </button>
                </a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('contract_quantity_information', 'Contract Quantity Information') ?></h5>
                        <span class="badge bg-{{ $contractQuantity->status === 'approved' ? 'success' : ($contractQuantity->status === 'rejected' ? 'danger' : ($contractQuantity->status === 'modified' ? 'warning' : 'primary')) }}">
                            {{ ucfirst(get_label($contractQuantity->status, $contractQuantity->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong><?= get_label('id', 'ID') ?>:</strong></td>
                                        <td>{{ $contractQuantity->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('contract', 'Contract') ?>:</strong></td>
                                        <td>
                                            <a href="{{ route('contracts.show', $contractQuantity->contract->id) }}">
                                                {{ $contractQuantity->contract->title ?? 'N/A' }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('item_description', 'Item Description') ?>:</strong></td>
                                        <td>{{ $contractQuantity->item_description }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('requested_quantity', 'Requested Quantity') ?>:</strong></td>
                                        <td>{{ $contractQuantity->requested_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('approved_quantity', 'Approved Quantity') ?>:</strong></td>
                                        <td>{{ $contractQuantity->approved_quantity ?? get_label('not_yet_approved', 'Not Yet Approved') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('unit', 'Unit') ?>:</strong></td>
                                        <td>{{ $contractQuantity->unit }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('unit_price', 'Unit Price') ?>:</strong></td>
                                        <td>{{ $contractQuantity->unit_price ? format_currency($contractQuantity->unit_price) : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong><?= get_label('total_amount', 'Total Amount') ?>:</strong></td>
                                        <td>{{ $contractQuantity->total_amount ? format_currency($contractQuantity->total_amount) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('submitted_by', 'Submitted By') ?>:</strong></td>
                                        <td>{{ $contractQuantity->user->first_name ?? 'N/A' }} {{ $contractQuantity->user->last_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('submitted_at', 'Submitted At') ?>:</strong></td>
                                        <td>{{ format_date($contractQuantity->submitted_at, true) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('approved_rejected_by', 'Approved/Rejected By') ?>:</strong></td>
                                        <td>{{ $contractQuantity->approvedRejectedBy->first_name ?? 'N/A' }} {{ $contractQuantity->approvedRejectedBy->last_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('approved_rejected_at', 'Approved/Rejected At') ?>:</strong></td>
                                        <td>{{ $contractQuantity->approved_rejected_at ? format_date($contractQuantity->approved_rejected_at, true) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= get_label('approval_rejection_notes', 'Approval/Rejection Notes') ?>:</strong></td>
                                        <td>{{ $contractQuantity->approval_rejection_notes ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($contractQuantity->notes)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h6><strong><?= get_label('notes', 'Notes') ?>:</strong></h6>
                                <p>{{ $contractQuantity->notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($contractQuantity->supporting_documents)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h6><strong><?= get_label('supporting_documents', 'Supporting Documents') ?>:</strong></h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach(json_decode($contractQuantity->supporting_documents) as $document)
                                        <a href="{{ asset('storage/' . $document) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="bx bx-file"></i> {{ basename($document) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Quantity Approval Workflow Section -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h6><strong><?= get_label('quantity_approval_workflow', 'Quantity Approval Workflow') ?></strong></h6>
                                <div class="timeline">
                                    <!-- Site Supervisor Upload Stage -->
                                    <div class="timeline-item">
                                        <div class="timeline-point bg-primary">
                                            <i class="bx bx-upload"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0"><?= get_label('site_supervisor_upload', 'Site Supervisor Upload') ?></h6>
                                            <p class="text-muted mb-1">
                                                {{ $contractQuantity->user->first_name ?? 'N/A' }} {{ $contractQuantity->user->last_name ?? '' }}
                                                <?php $uploaded_at = $contractQuantity->submitted_at ? format_date($contractQuantity->submitted_at, true) : 'N/A'; ?>
                                                - <?= get_label('uploaded_at', 'Uploaded at') ?>: {{ $uploaded_at }}
                                            </p>
                                            <span class="badge bg-{{ $contractQuantity->submitted_at ? 'success' : 'secondary' }}">
                                                {{ $contractQuantity->submitted_at ? get_label('completed', 'Completed') : get_label('pending', 'Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Quantity Approver Stage -->
                                    <div class="timeline-item">
                                        <div class="timeline-point bg-warning">
                                            <i class="bx bx-check-circle"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0"><?= get_label('quantity_approval', 'Quantity Approval') ?></h6>
                                            <p class="text-muted mb-1">
                                                <?php 
                                                $approver = $contractQuantity->contract->quantityApprover;
                                                $approved_at = $contractQuantity->approved_rejected_at ? format_date($contractQuantity->approved_rejected_at, true) : 'N/A';
                                                ?>
                                                <?= get_label('assigned_to', 'Assigned to') ?>: {{ $approver->first_name ?? 'N/A' }} {{ $approver->last_name ?? '' }}
                                                <?php if($approved_at !== 'N/A'): ?>
                                                    - <?= get_label('approved_at', 'Approved at') ?>: {{ $approved_at }}
                                                <?php endif; ?>
                                            </p>
                                            <span class="badge bg-{{ $contractQuantity->status === 'pending' ? 'warning' : ($contractQuantity->status === 'approved' ? 'success' : ($contractQuantity->status === 'rejected' ? 'danger' : 'warning')) }}">
                                                {{ ucfirst(get_label($contractQuantity->status, $contractQuantity->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Management Review Stage -->
                                    <div class="timeline-item">
                                        <div class="timeline-point bg-info">
                                            <i class="bx bx-buildings"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0"><?= get_label('management_review', 'Management Review') ?></h6>
                                            <p class="text-muted mb-1">
                                                <?php 
                                                $reviewer = $contractQuantity->contract->reviewer;
                                                ?>
                                                <?= get_label('assigned_to', 'Assigned to') ?>: {{ $reviewer->first_name ?? 'N/A' }} {{ $reviewer->last_name ?? '' }}
                                            </p>
                                            <span class="badge bg-{{ $contractQuantity->contract->management_review_status === 'approved' ? 'success' : ($contractQuantity->contract->management_review_status === 'rejected' ? 'danger' : 'secondary') }}">
                                                {{ $contractQuantity->contract->management_review_status ? ucfirst(str_replace('_', ' ', $contractQuantity->contract->management_review_status)) : get_label('pending', 'Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Accounting Review Stage -->
                                    <div class="timeline-item">
                                        <div class="timeline-point bg-success">
                                            <i class="bx bx-calculator"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0"><?= get_label('accounting_review', 'Accounting Review') ?></h6>
                                            <p class="text-muted mb-1">
                                                <?php 
                                                $accountant = $contractQuantity->contract->accountant;
                                                ?>
                                                <?= get_label('assigned_to', 'Assigned to') ?>: {{ $accountant->first_name ?? 'N/A' }} {{ $accountant->last_name ?? '' }}
                                            </p>
                                            <span class="badge bg-{{ $contractQuantity->contract->accounting_review_status === 'approved' ? 'success' : ($contractQuantity->contract->accounting_review_status === 'rejected' ? 'danger' : 'secondary') }}">
                                                {{ $contractQuantity->contract->accounting_review_status ? ucfirst(str_replace('_', ' ', $contractQuantity->contract->accounting_review_status)) : get_label('pending', 'Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('contract-quantities.index') }}" class="btn btn-secondary"><?= get_label('back_to_list', 'Back to List') ?></a>
                            
                            @if(checkPermission('edit_contract_quantities'))
                            <div class="btn-group">
                                <a href="{{ route('contract-quantities.edit', $contractQuantity->id) }}" class="btn btn-primary"><?= get_label('edit', 'Edit') ?></a>
                                
                                @if($contractQuantity->status === 'pending' && $contractQuantity->contract->quantity_approver_id === auth()->user()->id)
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= get_label('approve', 'Approve') ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="approveQuantity({{ $contractQuantity->id }})">
                                                <?= get_label('approve_quantity', 'Approve Quantity') ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="rejectQuantity({{ $contractQuantity->id }})">
                                                <?= get_label('reject_quantity', 'Reject Quantity') ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="modifyQuantity({{ $contractQuantity->id }})">
                                                <?= get_label('modify_quantity', 'Modify Quantity') ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Approving Quantity -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= get_label('approve_quantity', 'Approve Quantity') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approveForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"><?= get_label('approved_quantity', 'Approved Quantity') ?></label>
                            <input type="number" class="form-control" id="approved_quantity_input" name="approved_quantity" min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= get_label('approval_notes', 'Approval Notes') ?></label>
                            <textarea class="form-control" name="approval_rejection_notes" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= get_label('signature', 'Signature') ?></label>
                            <input type="hidden" name="quantity_approval_signature" id="signature_input">
                            <canvas id="signature_pad" width="400" height="100" style="border: 1px solid #ccc; width: 100%;;"></canvas>
                            <div class="mt-2">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="clearSignature()"><?= get_label('clear_signature', 'Clear Signature') ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                        <button type="submit" class="btn btn-success"><?= get_label('approve', 'Approve') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-point {
            position: absolute;
            left: -20px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 2;
        }
        .timeline-content {
            padding-left: 20px;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-quantities.js') }}"></script>
    <script>
        let signaturePad;
        let currentQuantityId;
        
        $(document).ready(function() {
            // Initialize signature pad if canvas exists
            if ($('#signature_pad')[0]) {
                const canvas = $('#signature_pad')[0];
                signaturePad = new SignaturePad(canvas);
            }
            
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
        
        function approveQuantity(id) {
            currentQuantityId = id;
            $('#approved_quantity_input').val('');
            $('#approveModal').modal('show');
        }
        
        function rejectQuantity(id) {
            currentQuantityId = id;
            const notes = prompt('<?= get_label('enter_rejection_reason', 'Enter rejection reason') ?>:');
            if (notes !== null) {
                $.ajax({
                    url: `/contract-quantities/${id}/reject`,
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'rejection_reason': notes,
                        'quantity_approval_signature': signaturePad ? signaturePad.toDataURL() : null
                    },
                    success: function(response) {
                        if (!response.error) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                    }
                });
            }
        }
        
        function modifyQuantity(id) {
            currentQuantityId = id;
            const newQuantity = prompt('<?= get_label('enter_new_quantity', 'Enter new quantity') ?>:');
            if (newQuantity !== null && !isNaN(newQuantity)) {
                const notes = prompt('<?= get_label('enter_modification_reason', 'Enter modification reason') ?>:');
                if (notes !== null) {
                    $.ajax({
                        url: `/contract-quantities/${id}/modify`,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'approved_quantity': newQuantity,
                            'approval_rejection_notes': notes,
                            'quantity_approval_signature': signaturePad ? signaturePad.toDataURL() : null
                        },
                        success: function(response) {
                            if (!response.error) {
                                toastr.success(response.message);
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                        }
                    });
                }
            }
        }
        
        function clearSignature() {
            if (signaturePad) {
                signaturePad.clear();
            }
        }
        
        $('#approveForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                '_token': '{{ csrf_token() }}',
                'approved_quantity': $('#approved_quantity_input').val(),
                'approval_rejection_notes': $('textarea[name="approval_rejection_notes"]').val(),
                'quantity_approval_signature': signaturePad ? signaturePad.toDataURL() : null
            };
            
            $.ajax({
                url: `/contract-quantities/${currentQuantityId}/approve`,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (!response.error) {
                        toastr.success(response.message);
                        $('#approveModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('<?= get_label('error_occurred', 'An error occurred') ?>');
                }
            });
        });
    </script>
    @endpush
@endsection