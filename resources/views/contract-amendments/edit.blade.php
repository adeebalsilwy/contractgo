@extends('layout')
@section('title', 'Approve Contract Amendment')
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
                            <h4>Approve Contract Amendment</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('contract-amendments.index') }}">Contract Amendments</a></li>
                                <li class="breadcrumb-item active">Approve</li>
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
                        <h5>Review Amendment Request</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6>Amendment Details</h6>
                            <table class="table table-borderless">
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
                                <tr>
                                    <td><strong>Contract:</strong></td>
                                    <td>{{ $amendment->contract->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested By:</strong></td>
                                    <td>{{ $amendment->requestedBy->first_name ?? 'N/A' }} {{ $amendment->requestedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested At:</strong></td>
                                    <td>{{ format_date($amendment->created_at, true) }}</td>
                                </tr>
                            </table>
                        </div>

                        <form id="amendmentApprovalForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="status">Status<span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="approval_comments">Approval Comments</label>
                                <textarea name="approval_comments" id="approval_comments" class="form-control" rows="4" placeholder="Enter approval or rejection comments..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="signature">Electronic Signature</label>
                                <div id="signature-pad" class="signature-pad">
                                    <canvas></canvas>
                                </div>
                                <input type="hidden" name="signature_data" id="signature_data">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary" id="clear-signature">Clear</button>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success">Submit Decision</button>
                                <a href="{{ route('contract-amendments.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-amendments.js') }}"></script>
    @endpush
@endsection