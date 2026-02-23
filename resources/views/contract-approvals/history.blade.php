@extends('layout')
@section('title', 'Contract Approval History')
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-approvals';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Approval History for {{ $contract->title }}</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Contracts</a></li>
                                <li class="breadcrumb-item active">Approval History</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-secondary float-right">Back to Contract</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Complete Approval Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($approvals as $approval)
                            <div class="timeline-item">
                                <div class="timeline-point bg-{{ $approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'warning') }}">
                                    <i class="bx bxs-check-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>{{ ucfirst(str_replace('_', ' ', $approval->approval_stage)) }}</h6>
                                    <p class="text-muted">{{ format_date($approval->approved_rejected_at ?? $approval->created_at, true) }}</p>
                                    <p><strong>Approver:</strong> {{ $approval->approver->first_name ?? 'N/A' }} {{ $approval->approver->last_name ?? '' }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($approval->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Comments:</strong> {{ $approval->comments ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center">No approval history found for this contract.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-approvals.js') }}"></script>
    @endpush
@endsection