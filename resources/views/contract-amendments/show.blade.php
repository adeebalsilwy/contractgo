@extends('layout')
@section('title')
    <?= get_label('contract_amendment_details', 'Contract Amendment Details') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-amendments';
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
                            <a href="{{ route('contract-amendments.index') }}"><?= get_label('contract_amendments', 'Contract Amendments') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('details', 'Details') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-amendments.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('amendment_information', 'Amendment Information') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><?= get_label('id', 'ID') ?>:</strong></td>
                                    <td>{{ $amendment->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('contract', 'Contract') ?>:</strong></td>
                                    <td>{{ $amendment->contract ? $amendment->contract->title : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('amendment_type', 'Amendment Type') ?>:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', get_label($amendment->amendment_type, $amendment->amendment_type))) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('request_reason', 'Request Reason') ?>:</strong></td>
                                    <td>{{ $amendment->request_reason }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('details', 'Details') ?>:</strong></td>
                                    <td>{{ $amendment->details ?? 'N/A' }}</td>
                                </tr>
                                
                                @if($amendment->amendment_type == 'price')
                                <tr>
                                    <td><strong><?= get_label('original_price', 'Original Price') ?>:</strong></td>
                                    <td>{{ $amendment->original_price ? format_currency($amendment->original_price) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('new_price', 'New Price') ?>:</strong></td>
                                    <td>{{ $amendment->new_price ? format_currency($amendment->new_price) : 'N/A' }}</td>
                                </tr>
                                @elseif($amendment->amendment_type == 'quantity')
                                <tr>
                                    <td><strong><?= get_label('original_quantity', 'Original Quantity') ?>:</strong></td>
                                    <td>{{ $amendment->original_quantity ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('new_quantity', 'New Quantity') ?>:</strong></td>
                                    <td>{{ $amendment->new_quantity ?? 'N/A' }}</td>
                                </tr>
                                @elseif($amendment->amendment_type == 'specification')
                                <tr>
                                    <td><strong><?= get_label('original_description', 'Original Description') ?>:</strong></td>
                                    <td>{{ $amendment->original_description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('new_description', 'New Description') ?>:</strong></td>
                                    <td>{{ $amendment->new_description ?? 'N/A' }}</td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <td><strong><?= get_label('requested_by', 'Requested By') ?>:</strong></td>
                                    <td>{{ $amendment->requestedBy->first_name ?? 'N/A' }} {{ $amendment->requestedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('requested_at', 'Requested At') ?>:</strong></td>
                                    <td>{{ format_date($amendment->created_at, true) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('status', 'Status') ?>:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $amendment->status === 'approved' ? 'success' : ($amendment->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(get_label($amendment->status, $amendment->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($amendment->approvedBy)
                                <tr>
                                    <td><strong><?= get_label('approved_by', 'Approved By') ?>:</strong></td>
                                    <td>{{ $amendment->approvedBy->first_name ?? 'N/A' }} {{ $amendment->approvedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('approved_at', 'Approved At') ?>:</strong></td>
                                    <td>{{ $amendment->approved_at ? format_date($amendment->approved_at, true) : 'N/A' }}</td>
                                </tr>
                                @endif
                                @if($amendment->approval_comments)
                                <tr>
                                    <td><strong><?= get_label('approval_comments', 'Approval Comments') ?>:</strong></td>
                                    <td>{{ $amendment->approval_comments }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('contract-amendments.index') }}" class="btn btn-secondary"><?= get_label('back_to_list', 'Back to List') ?></a>
                            
                            @if(in_array($amendment->status, ['pending']))
                            <a href="{{ route('contract-amendments.edit', $amendment->id) }}" class="btn btn-primary"><?= get_label('approve_reject', 'Approve/Reject') ?></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-amendments.js') }}"></script>
    @endpush
@endsection