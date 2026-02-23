@extends('layout')
@section('title', 'Contract Amendment Details')
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-amendments';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Contract Amendment Details</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contract-amendments.index') }}">Contract Amendments</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('contract-amendments.index') }}" class="btn btn-secondary float-right">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Amendment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $amendment->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contract:</strong></td>
                                    <td>{{ $amendment->contract->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amendment Type:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $amendment->amendment_type)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Request Reason:</strong></td>
                                    <td>{{ $amendment->request_reason }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Details:</strong></td>
                                    <td>{{ $amendment->details ?? 'N/A' }}</td>
                                </tr>
                                
                                @if($amendment->amendment_type == 'price')
                                <tr>
                                    <td><strong>Original Price:</strong></td>
                                    <td>{{ $amendment->original_price ? format_currency($amendment->original_price) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>New Price:</strong></td>
                                    <td>{{ $amendment->new_price ? format_currency($amendment->new_price) : 'N/A' }}</td>
                                </tr>
                                @elseif($amendment->amendment_type == 'quantity')
                                <tr>
                                    <td><strong>Original Quantity:</strong></td>
                                    <td>{{ $amendment->original_quantity ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>New Quantity:</strong></td>
                                    <td>{{ $amendment->new_quantity ?? 'N/A' }}</td>
                                </tr>
                                @elseif($amendment->amendment_type == 'specification')
                                <tr>
                                    <td><strong>Original Description:</strong></td>
                                    <td>{{ $amendment->original_description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>New Description:</strong></td>
                                    <td>{{ $amendment->new_description ?? 'N/A' }}</td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <td><strong>Requested By:</strong></td>
                                    <td>{{ $amendment->requestedBy->first_name ?? 'N/A' }} {{ $amendment->requestedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested At:</strong></td>
                                    <td>{{ format_date($amendment->created_at, true) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $amendment->status === 'approved' ? 'success' : ($amendment->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($amendment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($amendment->approvedBy)
                                <tr>
                                    <td><strong>Approved By:</strong></td>
                                    <td>{{ $amendment->approvedBy->first_name ?? 'N/A' }} {{ $amendment->approvedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Approved At:</strong></td>
                                    <td>{{ $amendment->approved_at ? format_date($amendment->approved_at, true) : 'N/A' }}</td>
                                </tr>
                                @endif
                                @if($amendment->approval_comments)
                                <tr>
                                    <td><strong>Approval Comments:</strong></td>
                                    <td>{{ $amendment->approval_comments }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('contract-amendments.index') }}" class="btn btn-secondary">Back to List</a>
                            
                            @if(in_array($amendment->status, ['pending']))
                            <a href="{{ route('contract-amendments.edit', $amendment->id) }}" class="btn btn-primary">Approve/Reject</a>
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