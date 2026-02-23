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
                        <h5>Approval Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Stage</th>
                                        <th>Approver</th>
                                        <th>Status</th>
                                        <th>Comments</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvals as $approval)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $approval->approval_stage)) }}</td>
                                        <td>{{ $approval->approver->first_name ?? 'N/A' }} {{ $approval->approver->last_name ?? '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($approval->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $approval->comments ?? 'N/A' }}</td>
                                        <td>{{ format_date($approval->approved_rejected_at ?? $approval->created_at, true) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No approval records found for this contract.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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