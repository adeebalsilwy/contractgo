@extends('layout')
@section('title', 'Pending Contract Approvals')
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
                            <h4>Pending Contract Approvals</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item">Contracts</li>
                                <li class="breadcrumb-item active">Pending Approvals</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <!-- Action buttons will go here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Contracts Awaiting Your Approval</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Contract</th>
                                        <th>Stage</th>
                                        <th>Submitted By</th>
                                        <th>Submitted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvals as $approval)
                                    <tr>
                                        <td>{{ $approval->id }}</td>
                                        <td>{{ $approval->contract->title }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $approval->approval_stage)) }}</td>
                                        <td>{{ $approval->contract->createdBy->first_name ?? 'N/A' }} {{ $approval->contract->createdBy->last_name ?? '' }}</td>
                                        <td>{{ format_date($approval->created_at, true) }}</td>
                                        <td>
                                            <a href="{{ route('contract-approvals.show', [$approval->contract_id, $approval->approval_stage]) }}" class="btn btn-primary btn-sm">Review</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No pending approvals found.</td>
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