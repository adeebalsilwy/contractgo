@extends('layout')
@section('title', 'Contract Approval')
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
                            <h4>Contract Approval - {{ ucfirst(str_replace('_', ' ', $stage)) }}</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contract-approvals.index') }}">Contract Approvals</a></li>
                                <li class="breadcrumb-item active">Approval Review</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('contract-approvals.index') }}" class="btn btn-secondary float-right">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Review Contract for {{ ucfirst(str_replace('_', ' ', $stage)) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6>Contract Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Title:</strong></td>
                                    <td>{{ $contract->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Value:</strong></td>
                                    <td>{{ $contract->value ? format_currency($contract->value) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Start Date:</strong></td>
                                    <td>{{ format_date($contract->start_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>End Date:</strong></td>
                                    <td>{{ format_date($contract->end_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $contract->description }}</td>
                                </tr>
                            </table>
                        </div>

                        <form id="approvalForm">
                            @csrf
                            <div class="form-group">
                                <label for="comments">Comments</label>
                                <textarea name="comments" id="comments" class="form-control" rows="4" placeholder="Enter your comments here..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="approval_signature">Electronic Signature</label>
                                <div id="signature-pad" class="signature-pad">
                                    <canvas></canvas>
                                </div>
                                <input type="hidden" name="approval_signature" id="approval_signature">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary" id="clear-signature">Clear</button>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success" id="approveBtn">Approve</button>
                                <button type="button" class="btn btn-danger ml-2" id="rejectBtn">Reject</button>
                                <a href="{{ route('contract-approvals.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-approvals.js') }}"></script>
    @endpush
@endsection